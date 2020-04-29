<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Lctype extends Model
{
    protected $table = 'lctype';
    public $timestamps = false;
    protected $fillable = ['lctype_name'
      ,'lctype_active'
      ,'lctype_created_at'
      ,'lctype_created_by'
      ,'lctype_modified_at'
      ,'lctype_modified_by'];
  
      public static function getFields($model){
  
          $model->id = null;
          $model->lctype_name = null;
          $model->lctype_active = null;
          $model->lctype_created_at = null;
          $model->lctype_created_by = null;
          $model->lctype_modified_at = null;
          $model->lctype_modified_by = null;
    
          return $model;
      }
}
