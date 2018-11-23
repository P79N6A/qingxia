<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthPermission extends Model
{
  protected $connection = 'mysql_local';
    protected $table = 'auth_permissions';
    public function roles()
    {
        return $this->belongsToMany('App\AuthRole','auth_permission_auth_role','permission_id','role_id');
    }
}
