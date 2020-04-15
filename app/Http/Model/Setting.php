<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
  public $timestamps = false;
  protected $fillable = ['setting_category'
    ,'setting_key'
    ,'setting_value'
    ,'setting_active'
    ,'setting_created_at'
    ,'setting_created_by'
    ,'setting_modified_at'
    ,'setting_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->setting_category = null;
        $model->setting_key = null;
        $model->setting_value = null;
        $model->setting_active = null;
        $model->setting_created_at = null;
        $model->setting_created_by = null;
        $model->setting_modified_at = null;
        $model->setting_modified_by = null;
  
        return $model;
    }
}
