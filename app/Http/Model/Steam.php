<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Steam extends Model
{
  public $timestamps = false;
  protected $fillable = ['steam_customer_id'
    ,'steam_invoice'
    ,'steam_date'
    ,'steam_total_price'
    ,'steam_active'
    ,'steam_paid'
    ,'steam_paidoff'
    ,'steam_executed_by'
    ,'steam_executed_at'
    ,'steam_finished_at'
    ,'steam_finished_by'
    ,'steam_taken_at'
    ,'steam_taken_by'
    ,'steam_created_at'
    ,'steam_created_by'
    ,'steam_modified_at'
    ,'steam_modified_by'];

  public static function getFields($model){

    $model->id = null;
    $model->steam_customer_id = null;
    $model->steam_invoice = null;
    $model->steam_date = null;
    $model->steam_total_price = null;
    $model->steam_active = null;
    $model->steam_paid = null;
    $model->steam_paidoff = null;
    $model->steam_executed_by = null;
    $model->steam_executed_at = null;
    $model->steam_finished_at = null;
    $model->steam_finished_by = null;
    $model->steam_taken_at = null;
    $model->steam_taken_by = null;
    $model->steam_created_at = null;
    $model->steam_created_by = null;
    $model->steam_modified_at = null;
    $model->steam_modified_by = null;
    $model->sub = Array();

    return $model;
  }

  public function scopeSteam($query)
  {
    return $query->where('steam_active', '1');
  }

  public function scopeSteamById($query, $id)
  {
    $q = $query->join('users as cr', 'steam_created_by', 'cr.id')
      ->join('customers as cus', 'steam_customer_id', 'cus.id')
      ->leftJoin('users as exe', 'steam_executed_by', 'exe.id')
      ->leftJoin('users as mod', 'steam_modified_by', 'mod.id')
      ->leftJoin('users as fin', 'steam_finished_by', 'fin.id')
      ->where([
        'steam_active' => '1',
        'steams.id' => $id]);
    return $q;
  }

  public function scopeSteamReport($query, $filter)
  {
    $q = $query
      ->join('users as u', 'u.id', 'steam_created_by')
      ->join('customers as cs', 'cs.id', 'steam_customer_id')
      ->join(DB::raw('(select count(sdetail_scategory_id) as total_item, sum(sdetail_price) as total_trx, sdetail_steam_id from sdetails s2
      where sdetail_active = \'1\'
      group by sdetail_steam_id) as sd'), 'steams.id', 'sd.sdetail_steam_id')
      ->where('steam_active', '1')
      ->where('steam_created_at', '>=', $filter->startDate)
      ->where('steam_created_at', '<', $filter->endDate);
      if($filter->statusBayar && $filter->statusBayar != null){
        $q = $q->where('steam_paidoff', $filter->statusBayar);
      }

      if($filter->status && $filter->status != null){
        switch($filter->status){
          case "draft":
            $q = $q->whereNull('steam_executed_at');
          break;
          case "proses":
            $q = $q->whereNotNull('steam_executed_at')
              ->whereNull('steam_finished_at');
          break;
          case "selesai":
            $q = $q->whereNotNull('steam_finished_at')
              ->whereNull('steam_taken_at');
          break;
          case "ambil":
            $q = $q->whereNotNull('steam_taken_at');
          break;
          default:
        }
      }

      $q = $q->select(
        'steams.id as id',
        'steam_invoice',
        'cs.customer_name',
        'total_item',
        DB::raw('case when steam_paidoff is true then \'Lunas\' else \'Belum Lunas\' end as status_bayar'),
        'steam_paid',
        DB::raw('total_trx - steam_paid as selisih'),
          'steam_created_at',
          'u.user_name as sales',
        DB::raw('case when steam_executed_at is null then \'Draft\' 
          when steam_executed_at is not null and steam_finished_at is null then \'Diproses\'
          when steam_finished_at is not null and steam_taken_at is null then \'Selesai\'
          when steam_taken_at is not null then \'Diambil\'
          else \' \' end as status')
      );
  }
}
