<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LCategory extends Model
{
  protected $table = 'lcategories';
  public $timestamps = false;
  protected $fillable = ['lcategory_name'
    ,'lcategory_detail'
    ,'lcategory_days'
    ,'lcategory_price'
    ,'lcategory_active'
    ,'lcategory_created_at'
    ,'lcategory_created_by'
    ,'lcategory_modified_at'
    ,'lcategory_modified_by'];

  public static function getFields($model){

      $model->id = null;
      $model->lcategory_name = null;
      $model->lcategory_detail = null;
      $model->lcategory_days = null;
      $model->lcategory_price = null;
      $model->lcategory_created_at = null;
      $model->lcategory_created_by = null;
      $model->lcategory_modified_at = null;
      $model->lcategory_modified_by = null;

      return $model;
  }
}
