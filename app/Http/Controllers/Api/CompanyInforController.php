<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Resources\CompanyInforResource;
use App\Model\CompanyInfor;
use Exception;

class CompanyInforController extends Controller
{
    public function index()
    {
        return CompanyInfor::find(1);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_en' => 'required',
            'name_km' => 'required',
            'contact_number' => 'required',
            'image' => 'required',
            'address' => 'required',
            'email' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $com_info = CompanyInfor::updateOrCreate(['id' => $this->getRequest($request)['id']],$this->getRequest($request));
            DB::commit();
            $res = [
                'status' => 1,
                'message'=> "Update success",
                'data'   => new CompanyInforResource($com_info),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $res = [
                'status'  => 0,
                'message' => 'Create not success',
                'data'    => $e->getMessage(),
            ];
        }
        return response()->json($res,200);
    }

    public function getRequest($request)
    {
        if ($request->image) {
            if (!empty(explode('data:image/', $request->image)[0])) {
                $image = CompanyInfor::find($request->id)->image;
            } else {
                $name   = now()->format('dmYhis') . '-' . time() . 'company.' .
                    explode('/', explode(':', substr($request->image, 0, strpos($request->image, ";")))[1])[1];
                $img    = Image::make($request->image)->save(public_path('uploads/company/') . $name);
                $img->resize(150, 150);
                $image  = "uploads/company/$name";
                if (!empty($request->id)) {
                    Helper::deleteImage(CompanyInfor::find($request->id)->image);
                }
            }
        }

        $data = [
            'id'                => $request->id,
            'name_en'           => $request->name_en,
            'name_km'           => $request->name_km,
            'contact_number'    => $request->contact_number,
            'image'             => $image,
            'address'           => $request->address,
            'email'             => $request->address,
            'website'           => $request->website,
        ];
        return $data;
    }
}
