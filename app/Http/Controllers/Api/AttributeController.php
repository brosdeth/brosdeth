<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Model\Attribute;

class AttributeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Attribute List|Attribute Create|Attribute Edit|Attribute Delete', ['only' => ['index', 'store', 'destroy']]);
        $this->middleware('permission:Attribute Create|Attribute Edit', ['only' => ['store']]);
        $this->middleware('permission:Attribute Delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $page_size = $request->page_size == null ? 100 : $request->page_size;
        return Attribute::orderBy('id', 'DESC')->paginate($page_size);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'nullable|max:255',
            'attr_name'          => isset(request()->id) ? "required|max:50|unique:attributes,id," . request()->id : [
                'required', 'max:50',
                Rule::unique('attributes')->where(function ($query) {
                    $query->where('deleted_at', '=', NULL);
                }),
            ],
        ]);
        DB::beginTransaction();
        try {
            $category =  Attribute::updateOrCreate([
                'id' => $request->id,
            ], [
                'attr_name' => $request->attr_name,
                'description' => $request->description,
            ]);
            DB::commit();
            if ($category->wasRecentlyCreated) {
                return response()->json(["message" => "Category $request->attr_name successfully created."], 200);
            } else {
                return response()->json(["message" => "Category $request->attr_name successfully updated."], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Something wrong, Please check it !"], 200);
        }
    }

    public function destroy($id)
    {
        Attribute::find($id)->delete();
    }
}
