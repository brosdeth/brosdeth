<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\categoryExportExcel;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Model\Categories;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class CategoriesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Category List|Category Create|Category Edit|Category Delete', ['only' => ['index', 'store', 'destroy']]);
        $this->middleware('permission:Category Create', ['only' => ['store']]);
        $this->middleware('permission:Category Edit', ['only' => ['store']]);
        $this->middleware('permission:Category Delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $page_size = $request->page_size == null ? 100 : $request->page_size;
        return Categories::orderBy('id', 'DESC')
            ->paginate($page_size);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'desc' => 'nullable|max:255',
            'cat_name' => [
                'required', 'max:50',
                Rule::unique('categories')->where(function ($query) {
                    $query->where('deleted_at', '=', NULL);
                })->ignore($request->id),
            ],
        ]);
        DB::beginTransaction();
        try {
            Categories::updateOrCreate([
                'id' => $request->id,
            ], [
                'cat_name' => $request->cat_name,
                'desc' => $request->desc,
            ]);
            DB::commit();
            $res = [
                'status' => 1,
                "message" => "successfully.",
                'data' => true,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $res = [
                'status' => 0,
                'message' => 'Something went wrong, Please try again or check again.',
                'data' => $e->getMessage(),
            ];
        }
        return response()->json($res, 200);
    }



    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Categories::find($id)->delete();
            DB::commit();
            $res = [
                'status' => 1,
                'message' => 'Delete successfull!',
                'data' => true,
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
    public function categoryExportExcel(Request $request)
    {
        $data = Categories::get();
        return Excel::download(new categoryExportExcel($data), 'CategoriesExportExcel.xlsx');
    }
}
