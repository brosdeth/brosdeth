<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ItemStockFilterResource;
use App\Imports\ItemImportExcel;
use App\Model\Item;
use App\Model\ItemAttribute;
use App\Model\ItemStock;
use App\Model\PurchaseDetail;
use App\Model\SaleDetail;
use App\Model\StockBalance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:Item List|Item Create|Item Edit|Item Delete|Item Show', ['only' => ['index','ItemShow', 'store', 'destroy']]);
        $this->middleware('permission:Item Create', ['only' => ['ItemShow','store']]);
        $this->middleware('permission:Item Edit', ['only' => ['ItemShow','store']]);
        $this->middleware('permission:Item Delete', ['only' => ['destroy']]);
        $this->middleware('permission:Item Show', ['only' => ['ItemShow']]);
        $this->middleware('permission:Item Import', ['only' => ['ItemImport']]);
    }

    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 100;
        $data = Item::when(request('querys', ''), function ($query) use ($request) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('categories.cat_name', 'LIKE', "$request->querys%");
            });
            $query->orWhere('item_code', 'LIKE', "$request->querys%");
            $query->orWhere('item_name', 'LIKE', "$request->querys%");
            $query->orWhere('unit_name', 'LIKE', "$request->querys%");
            $query->orWhere('barcode', 'LIKE', "$request->querys%");
        });
        if ($perPage == 'ALL') {
            $data = $data->paginate(Item::count());
        } else {
            $data = $data->paginate($perPage);
        }
        return ItemResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'item_code'   => 'nullable|unique:items,item_code,' . $request->id,
            // 'barcode'     => 'nullable|unique:items,barcode,' . $request->id,
            'barcode'     => [
                'nullable', 'max:13',
                Rule::unique('items')->where(function ($query) {
                    $query->where('deleted_at', '=', NULL);
                })->ignore($request->id),
            ],
            'item_name'   => 'required',
            'item_split.*.unit_name' => 'required',
            'item_split.*.divide' => 'required|integer|between:2,1000',
        ]);
        try {
            DB::beginTransaction();
            $item = Item::updateOrCreate(['id' => $this->getRequest($request)['id']], $this->getRequest($request));
            if ($item) {
                $stockId = [];
                if (count($request->item_attribute) > 0) {
                    foreach ($request->item_attribute as $key => $attribute) {
                        ItemAttribute::updateOrCreate(['id' => $attribute['id']], [
                            'item_id' => $item->id,
                            'attr_name' => $attribute['attr_name'],
                            'attr_value' => $attribute['attr_value'],
                        ]);
                    }
                }
                if (count($request->item_attribute_value) > 0) {
                    foreach ($request->item_attribute_value as $key => $attributeValue) {
                        $stock = ItemStock::updateOrCreate(['id' => $attributeValue['id']], [
                            'item_id' => $item->id,
                            'item_split_id' => NULL,
                            'unit_name' => $request->unit_name,
                            'code' => $attributeValue['code'] ?? Helper::itemStockCodeGenerate(),
                            'barcode' => $attributeValue['barcode'] ?? $request->barcode ?? '',
                            'price' => $attributeValue['price'] ?? $request->price,
                            'cost' => $attributeValue['cost'] ?? $request->cost,
                            'attrs' => $attributeValue['attrs'],
                            'is_expired' => $request->is_expired ?? 1,
                            'have_attr' => 2,
                            'parent_id' => $attributeValue['parent_id'] ?? 0,
                            'units' => count($request->item_split) > 0 ?  Arr::pluck($request->item_split, 'unit_name') : NULL,
                        ]);
                        $balance = StockBalance::updateOrCreate(['stock_id' => $stock->id], [
                            'balance_stock' => $attributeValue['balance_stock'] ?? 0
                        ]);
                        $stockId[] = $stock->id;
                        if (count($request->item_split) > 0) {
                            foreach ($request->item_split as $key => $split) {
                                $itemS = ItemStock::where('item_id', $item->id)->where('item_split_key', $split['item_split_key'])->get();
                                if (count($itemS) > 0) {
                                    foreach ($itemS as $keyA => $val) {
                                        $stockSplit = ItemStock::updateOrCreate([
                                            'id' => $val['id'],
                                        ], [
                                            'parent_id' => $stock->id,
                                            'item_id' => $item->id,
                                            'item_split_key' => $key,
                                            'unit_name' => $split['unit_name'] ?? '',
                                            'code' => $val['code'],
                                            'barcode' => $split['barcode'] ?? $request->barcode,
                                            'price' => $split['price'] ?? 0,
                                            'cost' => $request['cost'] * $split['divide'],
                                            'attrs' => $attributeValue['attrs'] ?? NULL,
                                            'have_attr' => 1,
                                            'is_expired' => $request->is_expired ?? 1,
                                            'divide' => $split['divide'] ?? 0,
                                        ]);
                                        $stockId[] = $stockSplit->id;
                                    }
                                } else {
                                    $stockSplit = $this->createItemStockSplit($split, $stock, $item, $request, $key, $attributeValue);
                                    $stockId[] = $stockSplit->id;
                                }
                            }
                        }
                    }
                } else {
                    if (count($request->stock) > 0) {
                        $stock = ItemStock::updateOrCreate(['id' => $request->stock[0]['id']], [
                            'item_id' => $item->id,
                            'unit_name' => $request->unit_name,
                            'units' => count($request->item_split) > 0 ?  Arr::pluck($request->item_split, 'unit_name') : NULL,
                            'code' => $request->item_code ?? Helper::itemStockCodeGenerate(),
                            'barcode' => $request->barcode ?? '',
                            'price' => $request->price ?? 0,
                            'cost' => $request->cost ?? 0,
                            'attrs' => null,
                            'have_attr' => 1,
                            'parent_id' => 0,
                            'is_expired' => $request->is_expired ?? 1,
                        ]);
                        // $balance = StockBalance::updateOrCreate(['stock_id' => $stock->id], [
                        //     'balance_stock' => $request->stock[0]['balance_stock'] ?? 0
                        // ]);
                        $balance = StockBalance::updateOrCreate(['stock_id' => $stock->id], [
                            'balance_stock' => $request->balance_stock ?? 0
                        ]);
                        $stockId[] = $stock->id;
                        if (count($request->item_split) > 0) {
                            foreach ($request->item_split as $key => $split) {
                                $itemS = ItemStock::where('item_id', $item->id)->where('item_split_key', $split['item_split_key'])->get();
                                if (count($itemS) > 0) {
                                    foreach ($itemS as $keyA => $val) {
                                        $stockSplit = ItemStock::updateOrCreate([
                                            'id' => $val['id'],
                                        ], [
                                            'parent_id' => $stock->id,
                                            'item_id' => $item->id,
                                            'item_split_key' => $key,
                                            'unit_name' => $split['unit_name'] ?? '',
                                            'code' => $val['code'],
                                            'barcode' => $split['barcode'] ?? $request->barcode,
                                            'price' => $split['price'] ?? 0,
                                            'cost' => $request['cost'] * $split['divide'],
                                            'attrs' => $attributeValue['attrs'] ?? NULL,
                                            'have_attr' => 1,
                                            'is_expired' => $request->is_expired ?? 1,
                                            'divide' => $split['divide'] ?? 0,
                                        ]);
                                        $stockId[] = $stockSplit->id;
                                    }
                                } else {
                                    $stockSplit = $this->createItemStockSplit($split, $stock, $item, $request, $key);
                                    $stockId[] = $stockSplit->id;
                                }
                                // $stockSplit = $this->createItemStockSplit($split, $stock, $item, $request, $key);
                                // $stockId[] = $stockSplit->id;
                            }
                        }
                    } else {
                        $stock = ItemStock::create([
                            'item_id' => $item->id,
                            'unit_name' => $request->unit_name,
                            'units' => count($request->item_split) > 0 ?  Arr::pluck($request->item_split, 'unit_name') : NULL,
                            'code' => $request->item_code ?? Helper::itemStockCodeGenerate(),
                            'barcode' => $request->barcode ?? '',
                            'price' => $request->price ?? 0,
                            'cost' => $request->cost ?? 0,
                            'attrs' => null,
                            'have_attr' => 1,
                            'parent_id' => 0,
                            'is_expired' => $request->is_expired ?? 1,
                        ]);
                        $balance = StockBalance::updateOrCreate(['stock_id' => $stock->id], [
                            'balance_stock' => $request->balance_stock ?? 0
                        ]);
                        $stockId[] = $stock->id;
                        if (count($request->item_split) > 0) {
                            foreach ($request->item_split as $key => $split) {
                                $stockSplit = $this->createItemStockSplit($split, $stock, $item, $request, $key);
                                $stockId[] = $stockSplit->id;
                            }
                        }
                    }
                }
            }
            if (!$item->wasRecentlyCreated) {
                ItemStock::where('item_id', $item->id)->whereNotIn('id', $stockId)->delete();
            }
            DB::commit();
            $res = [
                'status' => 1,
                'message' => $item->wasRecentlyCreated ? 'Create successfull!' : 'Update successfull!',
                'data' => $item,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $res = [
                'status' => 0,
                'message' => 'Something went wrong, Please try again or check again.',
                'data' => $e->getMessage(),
            ];
        }
        return response()->json($res, 200);
    }

    private function getRequest($request)
    {
        if ($request->image) {
            if (!empty(explode('data:image/', $request->image)[0])) {
                $image = Item::find($request->id)->image;
            } else {
                $name   = now()->format('dmYhis') . '-' . time() . 'items.' .
                    explode('/', explode(':', substr($request->image, 0, strpos($request->image, ";")))[1])[1];
                $img    = Image::make($request->image)->save(public_path('uploads/items/') . $name);
                $img->resize(150, 150);
                $image  = "uploads/items/$name";
                if (!empty($request->id)) {
                    Helper::deleteImage(Item::find($request->id)->image);
                }
            }
        }

        return [
            'id' => $request->id,
            'category_id' => $request->category_id,
            'item_code' => $request->item_code ?? Helper::itemStockCodeGenerate(),
            'barcode' => $request->barcode ?? '',
            'item_name' => $request->item_name,
            'unit_name' => $request->unit_name ?? '',
            'price' => $request->price ?? 0,
            'cost' => $request->cost ?? 0,
            'image' => $image ?? '',
            'description' => $request->description,
            'is_expired' => $request->is_expired ?? 1,
        ];
    }

    private function createItemStockSplit($split, $stock, $item, $request, $key, $attributeValue = NULL)
    {
        return ItemStock::updateOrCreate([
            'id' => $split['id'],
        ], [
            'parent_id' => $stock->id,
            'item_id' => $item->id,
            'item_split_key' => $key,
            'unit_name' => $split['unit_name'] ?? '',
            'code' => $split['code'] ?? Helper::itemStockCodeGenerate(),
            'barcode' => $split['barcode'] ?? $request->barcode,
            'price' => $split['price'] ?? 0,
            'cost' => $request['cost'] * $split['divide'],
            'attrs' => $attributeValue['attrs'] ?? NULL,
            'have_attr' => 1,
            'is_expired' => $request->is_expired ?? 1,
            'divide' => $split['divide'] ?? 0,
        ]);
    }

    public function ItemImport(Request $request)
    {
        $this->validate($request, [
            'file'   => 'required',
        ]);
        $import = new ItemImportExcel;
        Excel::import($import, $request->file('file'));
        return response()->json($import->res, 200);
    }

    public function ItemShow(Request $request)
    {
        $data = Item::find($request->id);
        if ($data) {
            $res = [
                'status' => 1,
                'message' => 'Show item successfull!',
                'data' => new ItemResource($data),
            ];
        } else {
            $res = [
                'status' => 1,
                'message' => 'Item find not found!',
                'data' => null,
            ];
        }

        return response()->json($res, 200);
    }

    public function itemFilter(Request $request)
    {
        $data = null;
        if ($request->type == 'typing') {
            if (isset($request->querys)) {
                $data = ItemStock::whereHas('item', function ($q) use ($request) {
                    $q->where('item_name', 'LIKE', "$request->querys%");
                })
                    ->orWhere('code', 'LIKE', "$request->querys%")
                    ->orWhere('barcode', 'LIKE', "$request->querys%")
                    ->take(20)->get();
            }
        } else if ($request->type == 'change') {
            $data = ItemStock::where('item_id', $request->item_id)->get();
        } else {
            $data = ItemStock::where('code', $request->querys)
                ->orWhere('barcode', $request->querys)
                ->first();
        }
        if ($data) {
            $res = [
                'status' => 1,
                'message' => 'filter item successfull!',
                'data' => ($request->type == 'typing' || $request->type == 'change') ? ItemStockFilterResource::collection($data) : new ItemStockFilterResource($data),
            ];
        } else {
            $res = [
                'status' => 0,
                'message' => 'Item find not found!',
                'data' => [],
            ];
        }

        return response()->json($res, 200);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $item = Item::find($id);
            if ($item) {
                if (PurchaseDetail::whereIn('stock_id', $item->stock->pluck('id'))->exists()) {
                    $res = [
                        'status' => 0,
                        'message' => 'ផលិតផលមិនអាចលុបបានទេ ពីព្រោះលោកអ្នកបានធ្វើប្រត្តិ​បត្តិ​ការ​រួចហើយ',
                        'data' => '',
                    ];
                } else if (SaleDetail::whereIn('stock_id', $item->stock->pluck('id'))->exists()) {
                    $res = [
                        'status' => 0,
                        'message' => 'ផលិតផលមិនអាចលុបបានទេ ពីព្រោះលោកអ្នកបានធ្វើប្រត្តិ​បត្តិ​ការ​រួចហើយ',
                        'data' => '',
                    ];
                } else {
                    $item->stock->map(function ($val) {
                        if ($val->balanceStock) {
                            $val->balanceStock->delete();
                        }
                    });
                    $item->stock->each->delete();
                    $item->delete();
                    DB::commit();
                    $res = [
                        'status' => 1,
                        'message' => 'Item deleted success.',
                        'data' => $item,
                    ];
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            $res = [
                'status' => 0,
                'message' => 'Something went wrong, Please try again or check again.',
                'data' => $e->getMessage(),
            ];
        }
        return response()->json($res, 200);
    }

    public function deleteSub($stockId)
    {
        try {
            $item = ItemStock::find($stockId);
            if ($item) {
                if (PurchaseDetail::where('stock_id', $item->id)->exists()) {
                    $res = [
                        'status' => 0,
                        'message' => 'ផលិតផលមិនអាចលុបបានទេ ពីព្រោះលោកអ្នកបានធ្វើប្រត្តិ​បត្តិ​ការ​រួចហើយ',
                        'data' => '',
                    ];
                } else if (SaleDetail::where('stock_id', $item->id)->exists()) {
                    $res = [
                        'status' => 0,
                        'message' => 'ផលិតផលមិនអាចលុបបានទេ ពីព្រោះលោកអ្នកបានធ្វើប្រត្តិ​បត្តិ​ការ​រួចហើយ',
                        'data' => '',
                    ];
                } else {
                    $res = [
                        'status' => 1,
                        'message' => 'Item deleted success.',
                        'data' => $item,
                    ];
                }
            } else {
                $res = [
                    'status' => 0,
                    'message' => 'Item not found.',
                    'data' => '',
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            $res = [
                'status' => 0,
                'message' => 'Something went wrong, Please try again or check again.',
                'data' => $e->getMessage(),
            ];
        }
        return response()->json($res, 200);
    }
}
