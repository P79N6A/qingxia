<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrePluginWorkbookKnow extends Model
{
  protected $table = 'pre_plugin_workbook_know';

  public function questions()
  {
    return $this->hasMany('App\PrePluginWorkbookQuestion','chapterid','id');
  }
}
