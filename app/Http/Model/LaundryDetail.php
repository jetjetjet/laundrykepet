<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LaundryDetail extends Model
{
  protected $table = 'ldetails';
  public $timestamps = false;
  protected $fillable = ['ldetail_laundry_id'
    ,'ldetail_lcategory_id'
    ,'ldetail_start_date'
    ,'ldetail_end_date'
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
      $model->ldetail_end_date = null;
      $model->ldetail_qty = null;
      $model->ldetail_total = null;
      $model->ldetail_active = null;
      $model->ldetail_created_at = null;
      $model->ldetail_created_by = null;
      $model->ldetail_modified_at = null;
      $model->ldetail_modified_by = null;

      return $model;
  }

  public function scopeDetailLaundry($query, $id)
  {
    $q = $query->join('lcategories as lc', 'lc.id', 'ldetail_lcategory_id')
      ->where([
        'ldetail_active' => '1',
        'ldetail_laundry_id' => $id
      ])
      ->where('lcategory_active', '1');
    return $q;
  }
}
