<?php

namespace App\Http\Controllers\Manage;

use App\AuthPermission;
use App\AuthRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class SystemController extends Controller
{
    public function index(){

        $data['all_user'] = User::where('status',1)->select('id','name')->with('roles')->get();

        $data['all_roles'] = AuthRole::select('id','name','label')->with('permissions')->get();
        $data['all_permissions'] = AuthPermission::all('id','name','label');
        foreach ($data['all_user'] as $value){
            $data['role_about'][$value->id] = $value->roles;
        }
        foreach ($data['all_roles'] as $value){
            $data['permission_about'][$value->id] = $value->permissions;
        }
        return view('manage.system',compact('data'));
    }
}
