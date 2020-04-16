<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Lexpenses extends Model
{
    protected $table = 'lexpenses';
  public $timestamps = false;
  protected $fillable = ['lexpenses_name'
    ,'lexpenses_detail'
    ,'lexpenses_price'
    ,'lexpenses_active'
    ,'lexpenses_created_at'
    ,'lexpenses_created_by'
    ,'lexpenses_modified_at'
    ,'lexpenses_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->lexpenses_name = null;
        $model->lexpenses_detail = null;
        $model->lexpenses_price = null;
        $model->lexpenses_active = null;
        $model->lexpenses_created_at = null;
        $model->lexpenses_created_by = null;
        $model->lexpenses_modified_at = null;
        $model->lexpenses_modified_by = null;
  
        return $model;
    }
}
