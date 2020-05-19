<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LCategory extends Model
{
  protected $table = 'lcategories';
  public $timestamps = false;
  protected $fillable = ['lcategory_name'
    ,'lcategory_detail'
    ,'lcategory_price'
    ,'lcategory_lctype_id'
    ,'lcategory_lctype_name'
    ,'lcategory_lctype_unit'
    ,'lcategory_days'
    ,'lcategory_active'
    ,'lcategory_active'
    ,'lcategory_created_at'
    ,'lcategory_created_by'
    ,'lcategory_modified_at'
    ,'lcategory_modified_by'];

  public static function getFields($model)
  {
    $model->id = null;
    $model->lcategory_name = null;
    $model->lcategory_detail = null;
    $model->lcategory_lctype_id = null;
    $model->lcategory_lctype_name = null;
    $model->lcategory_lctype_unit = null;
    $model->lcategory_price = null;
    $model->lcategory_days = null;
    $model->lcategory_created_at = null;
    $model->lcategory_created_by = null;
    $model->lcategory_modified_at = null;
    $model->lcategory_modified_by = null;

    return $model;
  }

  public function scopeSearchCategory($query, $filter, $searchQuery)
  {
    $q = $query->join('lctypes as lc', 'lc.id', 'lcategory_lctype_id')
      ->where('lcategory_active', '1');
    foreach($filter as $key => $f)
    {
      $q = $q->where($key, $f);
    }
    if($searchQuery)
      $q = $q->whereRaw('UPPER(lcategory_name) LIKE UPPER(\'%'. $searchQuery .'%\')');
    
    $q = $q->select('lcategories.id', 'lcategory_name', 'lcategory_price', 'lcategory_days', 'lc.lctype_name as lcategory_type');
    return $q;
  }
}
