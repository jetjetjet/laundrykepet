<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Laundry extends Model
{
  public $timestamps = false;
  protected $fillable = ['laundry_invoice'
    ,'laundry_customer_id'
    ,'laundry_est_date'
    ,'laundry_paid'
    ,'laundry_paidoff'
    ,'laundry_delivery'
    ,'laundry_active'
    ,'laundry_executed_at'
    ,'laundry_executed_by'
    ,'laundry_finished_at'
    ,'laundry_finished_by'
    ,'laundry_taken_at'
    ,'laundry_taken_by'
    ,'laundry_delivered_at'
    ,'laundry_delivered_by'
    ,'laundry_created_at'
    ,'laundry_created_by'
    ,'laundry_modified_at'
    ,'laundry_modified_by'];

  public static function getFields($model){

      $model->id = null;
      $model->laundry_invoice = null;
      $model->laundry_customer_id = null;
      $model->laundry_customer_name = null;
      $model->laundry_est_date = null;
      $model->laundry_paid = null;
      $model->laundry_paidoff = null;
      $model->laundry_delivery = null;
      $model->laundry_finished_at = null;
      $model->laundry_finished_by = null;
      $model->laundry_delivered_at = null;
      $model->laundry_delivered_by = null;
      $model->laundry_created_at = null;
      $model->laundry_created_by = null;
      $model->laundry_modified_at = null;
      $model->laundry_modified_by = null;
      $model->sub = Array();

      return $model;
  }

  public function scopeLaundry($query)
  {
    return $query->where('laundry_active', '1');
  }

  public function scopeLaundryById($query, $id)
  {
    $q = $query->join('users as cr', 'laundry_created_by', 'cr.id')
      ->join('customers as cus', 'laundry_customer_id', 'cus.id')
      ->leftJoin('employees as dlv', 'laundry_delivered_by', 'dlv.id')
      ->leftJoin('users as exe', 'laundry_executed_by', 'exe.id')
      ->leftJoin('users as mod', 'laundry_modified_by', 'mod.id')
      ->leftJoin('users as fin', 'laundry_finished_by', 'fin.id')
      ->where([
        'laundry_active' => '1',
        'laundries.id' => $id]);
    return $q;
  }
}
