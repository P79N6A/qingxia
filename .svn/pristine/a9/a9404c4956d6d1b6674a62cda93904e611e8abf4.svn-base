<?php

namespace App\Http\Controllers\Manage\Api;

use App\AuthPermission;
use App\AuthPermissionRole;
use App\AuthRoleUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\AuthRole;
use Illuminate\Support\Facades\Auth;


class ApiSystemManageController extends Controller
{

    public function grant_role_permission(Request $request)
    {
        $role = $request->get('role_name');
        $did = $request->get('did');
        $type = $request->get('type');
        if ($type == 'role') {
            $ok = User::find($did)->assignRole($role);
        } else {
            $ok = AuthRole::find($did)->givePermissionTo(AuthPermission::whereName($role)->firstOrFail());
        }

        if ($ok) {
            return response()->json(['status' => 1, 'msg' => '新增成功']);
        } else {
            return response()->json(['status' => 0, 'msg' => '新增失败']);
        }

    }

    public function add_role_permission(Request $request)
    {
        $name = $request->get('name');
        $label = $request->get('label');
        $type = $request->get('type');
        if ($type == 'add_role') {
            $add_now = new AuthRole();
        }else{
            $add_now = new AuthPermission();
        }
        $add_now->name = $name;
        $add_now->label = $label;
        if($add_now->save()){
            return response()->json(['status' => 1, 'msg' => '新增成功','new_id'=>$add_now->id]);
        }
        return response()->json(['status' => 0, 'msg' => '新增失败']);
    }

    public function del_role_permission(Request $request){
        $primary_id = $request->get('primary_id');
        $now_id = $request->get('now_id');
        $type = $request->get('type');
        if ($type == 'role') {
            $del_now = new AuthRoleUser();
            $del_status = $del_now->where('user_id',$primary_id)->where('role_id',$now_id)->delete();
        }else{
            $del_now = new AuthPermissionRole();
            $del_status = $del_now->where('role_id',$primary_id)->where('permission_id',$now_id)->delete();
        }
        if($del_status){
            return response()->json(['status' => 1, 'msg' => '删除成功']);
        }
        return response()->json(['status' => 0, 'msg' => '删除失败']);
    }
}
