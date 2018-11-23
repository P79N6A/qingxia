<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthPermissionRole extends Model
{
  protected $connection = 'mysql_local';
    protected $table = 'auth_permission_auth_role';
}
