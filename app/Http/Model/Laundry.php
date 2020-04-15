<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

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

  public function scopeLaundryReport($query, $filter)
  {
    $q = $query
      ->join('users as u', 'u.id', 'laundry_created_by')
      ->join('customers as cs', 'cs.id', 'laundry_customer_id')
      ->join(DB::raw('(select count(ldetail_lcategory_id) as total_item, sum(ldetail_total) as total_trx, ldetail_laundry_id from ldetails l2
      where ldetail_active = \'1\'
      group by ldetail_laundry_id) as ld'), 'laundries.id', 'ld.ldetail_laundry_id')
      ->where('laundry_active', '1')
      ->where('laundry_created_at', '>=', $filter->startDate)
      ->where('laundry_created_at', '<', $filter->endDate);
      if($filter->statusBayar && $filter->statusBayar != null){
        $q = $q->where('laundry_paidoff', $filter->statusBayar);
      }

      if($filter->status && $filter->status != null){
        switch($filter->status){
          case "draft":
            $q = $q->whereNull('laundry_executed_at');
          break;
          case "proses":
            $q = $q->whereNotNull('laundry_executed_at')
              ->whereNull('laundry_finished_at');
          break;
          case "selesai":
            $q = $q->whereNotNull('laundry_finished_at')
              ->whereNull('laundry_delivered_at')
              ->whereNull('laundry_taken_at');
          break;
          case "antar":
            $q = $q->whereNotNull('laundry_delivered_at');
          break;
          case "ambil":
            $q = $q->whereNotNull('laundry_taken_at');
          break;
          default:
        }
      }

      $q = $q->select(
        'laundries.id as id',
        'laundry_invoice',
        'cs.customer_name',
        'total_item',
        DB::raw('case when laundry_paidoff is true then \'Lunas\' else \'Belum Lunas\' end as status_bayar'),
        'laundry_paid',
        DB::raw('total_trx - laundry_paid as selisih'),
          'laundry_created_at',
          'u.user_name as sales',
        DB::raw('case when laundry_executed_at is null then \'Draft\' 
          when laundry_executed_at is not null and laundry_finished_at is null then \'Diproses\'
          when laundry_finished_at is not null and (laundry_delivered_at is null and laundry_taken_at is null) then \'Selesai\'
          when laundry_delivered_at is not null then \'Diantar\'
          when laundry_taken_at is not null then \'Diambil\'
          else \' \' end as status')
      );
  }
}
