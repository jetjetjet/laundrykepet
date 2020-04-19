<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SteamDetail extends Model
{
  protected $table = 'sdetails';
  public $timestamps = false;
  protected $fillable = ['sdetail_steam_id'
    ,'sdetail_scategory_id'
    ,'sdetail_plate'
    ,'sdetail_price'
    ,'sdetail_qty'
    ,'sdetail_active'
    ,'sdetail_created_at'
    ,'sdetail_created_by'
    ,'sdetail_modified_at'
    ,'sdetail_mofified_by'];

  public static function getFields($model){

    $model->id = null;
    $model->sdetail_steam_id = null;
    $model->sdetail_scategory_id = null;
    $model->sdetail_plate = null;
    $model->sdetail_price = null;
    $model->sdetail_qty = null;
    $model->sdetail_active = null;
    $model->sdetail_created_at = null;
    $model->sdetail_created_by = null;
    $model->sdetail_modified_at = null;
    $model->sdetail_modified_by = null;
    $model->sub = Array();

    return $model;
  }

  public function scopeDetailSteam($query, $id)
  {
    $q = $query->join('scategories as sc', 'sc.id', 'sdetail_scategory_id')
      ->where([
        'sdetail_active' => '1',
        'sdetail_steam_id' => $id
      ])
      ->where('scategory_active', '1');
    return $q;
  }
}
