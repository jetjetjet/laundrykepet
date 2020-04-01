<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LaundryDetail extends Model
{
  protected $table = 'ldetails';
  public $timestamps = false;
  protected $fillable = ['ldetail_laundry_id'
    ,'ldetail_lcategory_id'
    ,'ldetail_qty'
    ,'ldetail_total'
    ,'ldetail_active'
    ,'ldetail_created_at'
    ,'ldetail_created_by'
    ,'ldetail_modified_at'
    ,'ldetail_modified_by'];

  public static function getFields($model){

      $model->id = null;
      $model->ldetail_ldetail_id = null;
      $model->ldetail_lcategory_id = null;
      $model->ldetail_qty = null;
      $model->ldetail_total = null;
      $model->ldetail_active = null;
      $model->ldetail_created_at = null;
      $model->ldetail_created_by = null;
      $model->ldetail_modified_at = null;
      $model->ldetail_modified_by = null;

      return $model;
  }
}
