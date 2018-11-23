<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreCommonMember extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'pre_common_member';
}
