<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:User List|User Create|User Edit|User Delete', ['only' => ['index','create','store','destroy']]);
         $this->middleware('permission:User Create', ['only' => ['create','store']]);
         $this->middleware('permission:User Edit', ['only' => ['create','store']]);
         $this->middleware('permission:User Delete', ['only' => ['destroy']]);
    }
    public function checkUserValid(){
        $data = User::find(Auth::user()->id);
        return new UserResource($data);
    }
    public function index(Request $request)
    {   
        $page_size = $request->page_size == null?25: $request->page_size;
        $data = User::search($request->search_text)
            ->orderBy('id','DESC')
            ->paginate($page_size);
        return UserResource::collection($data);
    }

    public function create(Request $request){
        $user = User::find($request->id);
        if($user){
            $res = [
                'status' => 1,
                'message'=> 'Get user success',
                'data'   => new UserResource($user),
            ];
        }else{
            $res = [
                'status'  => 0,
                'message' => 'Get user not found',
                'data'    => [],
            ];
        }
        return response()->json($res,200);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email|unique:users,email,'.request()->id,
            'password'  => isset(request()->id) ? '' : 'required|same:confirm_password',
            'confirm_password'  => isset(request()->id) ? '' : 'required|same:password',
            'role_id' => 'required',
        ]);
        try{
            DB::beginTransaction();
            $user = User::updateOrCreate(['id' => $this->getRequest($request)['id']],$this->getRequest($request));
            $user->syncRoles($request->role_id);
            DB::commit();
            $res = [
                'status' => 1,
                'message'=> $user->wasRecentlyCreated?'Create user success':'Update user success',
                'data'   => new UserResource($user),
            ];
        }catch(Exception $e){
            DB::rollBack();
            $res = [
                'status'  => 0,
                'message' => 'Create user not success',
                'data'    => $e->getMessage(),
            ];
        }
        return response()->json($res,200);
    }

    public function getRequest($request)
    {
        $data = [
            'id'        => $request->id,
            'name'      => $request->name,
            'email'     => $request->email,
            'contact_number'=> $request->contact_number,
            'is_active' => $request->is_active??1,
            'password'  => Hash::make($request->password),
        ];
        if(empty($request->password)){
            $data = Arr::except($data,array('password'));
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = User::find($id);
        if($find->is_active == 1){
            User::where('id',$id)->update(['is_active' => 2]);
            return response()->json(["message" => "User's is inactive."], 200);
        }
        else{
            User::where('id',$id)->update(['is_active' => 1]);
            return response()->json(["message" => "User's active"], 200);
        }
    }
}
