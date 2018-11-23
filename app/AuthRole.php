<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthRole extends Model
{
  protected $connection = 'mysql_local';
    protected $table = 'auth_roles';
    public function permissions()
    {
        return $this->belongsToMany('App\AuthPermission','auth_permission_auth_role','role_id','permission_id');
    }

    public function givePermissionTo(AuthPermission $permission)
    {
        return $this->permissions()->save($permission);
    }
}
