<?php


namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:Role List|Role Create|Role Edit|Role Delete', ['only' => ['index','create','destroy','store']]);
         $this->middleware('permission:Role Create', ['only' => ['create','store']]);
         $this->middleware('permission:Role Edit', ['only' => ['create','store']]);
         $this->middleware('permission:Role Delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Role::where(function($query)use($request){
            if(!empty($request->searching)){
                $query->where('name','LIKE',"$request->searching%");
            }
        })->get()->map(function($value){
            return [
                'id'        => $value->id,
                'value'     => $value->id,
                'name'      => $value->name,
                'created_at'=> date('Y-m-d h:i A',strtotime($value->created_at)),
            ];
        });
        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermission($id)
    {
        $permissionGroup = Permission::select('name','group_prefix')->groupBy('group_prefix')->orderby('id','asc')->get();
        $role = Role::find($id);
        $data = [];
        $countA = 0;
        $countB = 0;
        foreach($permissionGroup as $key => $value) {
            $count = 0;
            $permissions = Permission::where('group_prefix',$value->group_prefix)->get();
            $permission = [];
            foreach ($permissions as $key => $val) {
                if($role){
                    $PermissionAllow = DB::table('role_has_permissions')->where('role_id', $role->id)->where('permission_id',$val->id)->first();
                    if($PermissionAllow){
                        $count+=1;
                        $countA +=1;
                    }
                }
                $permission []=[
                    'id' => $val->id,
                    'name' => $val->name,
                    'group_prefix' => $val->group_prefix,
                    'allow' => isset($PermissionAllow)?true:false,
                ];
            }
            $countB += count($permission);
            $data []= [
                'group_prefix' => $value->group_prefix,
                'children' => $permission,
                'check_group' => (count($permission) == $count)?true: false,
            ];
        }
        $res =[
            'id' => $role->id??'',
            'name' => $role->name??'',
            'check_all' => ($countA == $countB )?true:false,
            'permission' => $data
        ];
        return $res;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$request->id,
            // 'permission.*.children.*.allow' => 'required_if:anotherfield,value',
        ]);

        try{
            DB::beginTransaction();
            $role = Role::updateOrCreate(['id' => $request->id],[
                'name' => $request->name
            ]);
            $permissionAllow = [];
            foreach ($request->permission as $key => $value) {
                foreach ($value['children'] as $key => $val) {
                    if($val['allow'] == true){
                        $permissionAllow []=$val['id'];
                    }
                }
            }
            $role->syncPermissions($permissionAllow);
            DB::commit();
            $res = [
                'status' => 1,
                'message'=> $role->wasRecentlyCreated?'Create role success':'Update role success',
                'data'   => $role,
            ];
        }catch(Exception $e){
            DB::rollBack();
            $res = [
                'status'  => 0,
                'message' => 'Create role not success',
                'data'    => $e->getMessage(),
            ];
        }
        return response()->json($res,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::beginTransaction();
            $role = Role::find($id);
            if($role){
                $perm = DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id');
                Permission::whereIn('id',$perm)->get()->map(function($value) use($role){
                    $role->revokePermissionTo($value->name);
                    $value->removeRole($role);
                });
                $role->delete();
            }
            DB::commit();
            $res = [
                'status' => 1,
                'message'=> 'delete role success',
                'data'   => $role,
            ];
        }catch(Exception $e){
            DB::rollBack();
            $res = [
                'status'  => 0,
                'message' => 'delete not success',
                'data'    => $e->getMessage(),
            ];
        }
        return response()->json($res,200);
    }
}