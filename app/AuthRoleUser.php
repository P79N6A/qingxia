<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthRoleUser extends Model
{
  protected $connection = 'mysql_local';
    protected $table = 'auth_role_user';
}
