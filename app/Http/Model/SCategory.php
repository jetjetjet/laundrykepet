<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SCategory extends Model
{
    protected $table = 'scategories';
  public $timestamps = false;
  protected $fillable = ['scategory_name'
    ,'scategory_detail'
    ,'scategory_type'
    ,'scategory_price'
    ,'scategory_active'
    ,'scategory_created_at'
    ,'scategory_created_by'
    ,'scategory_modified_at'
    ,'scategory_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->scategory_name = null;
        $model->scategory_detail = null;
        $model->scategory_type = null;
        $model->scategory_price = null;
        $model->scategory_active = null;
        $model->scategory_created_at = null;
        $model->scategory_created_by = null;
        $model->scategory_modified_at = null;
        $model->scategory_modified_by = null;
  
        return $model;
    }
}
