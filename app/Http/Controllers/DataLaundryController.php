<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Laundry;
use App\Http\Model\LaundryDetail;
use App\Http\Libs\Helper;
use Auth;
use DB;

class DataLaundryController extends Controller
{
    public function index()
    {
      return view('DataLaundry.index');
    }

    public function getLists(Request $request)
    {
      $filter = Helper::getFilter($request);
      //dd($filter);
      $data = Laundry::join('users as cr', 'laundry_created_by', 'cr.id')
        ->join('customers as cust', 'cust.id', 'laundry_customer_id')
        ->join(DB::raw('(select sum(ldetail_qty) as ldetail_qty, FORMAT(sum(ldetail_total),0) as ldetail_total, ldetail_laundry_id from ldetails l3 where ldetail_active = \'1\' group by ldetail_laundry_id) as ld'), 'ld.ldetail_laundry_id', 'laundries.id')
        ->where('laundry_active', '1')
        ->select('laundries.id as id', 
          'laundry_invoice', 
          'laundry_paid',
          'ldetail_qty',
          'ldetail_total',
          'laundry_paidoff',
          'laundry_delivery',
          'cust.customer_name as laundry_customer_name',
          'cust.customer_phone as laundry_customer_phone',
          'cust.customer_address as laundry_customer_address',
          DB::raw('case when laundry_finished is null then \'Proses\' 
            when laundry_finished is not null and laundry_completed is null then \'Cuci Selesai\'
            when laundry_finished is not null and laundry_completed is not null then \'Transaksi Selesai\'
            else \' \' end as laundry_status'),
          'laundry_created_at');

      if (!empty($filter->filterColumns)){
        foreach ($filter->filterColumns as $value){
          if (!isset($value->value)) continue;
          $trimmedText = trim($value->value);
          $text = strtolower(implode('%', explode(' ', $trimmedText)));
          $field = self::mapView2DatabaseName($value->field);
          if ($field == "laundry_status"){
            switch($text){
              case "proses":
                $data = $data->whereNull('laundry_finished');
              break;
              case "finished":
                $data = $data->whereNotNull('laundry_finished')
                  ->whereNull('laundry_completed');
              break;
              case "completed":
                $data = $data->whereNotNull('laundry_finished')
                  ->whereNotNull('laundry_completed');
              break;
              default:
            }
          } else if ($field == "laundry_paidoff"){
            if($text == "lunas"){
              $data = $data->where('laundry_paidoff', '1');
            } else if ($text == "belum") {
              $data = $data->where('laundry_paidoff', '0');
            }
          } else {
            $data->whereRaw('lower(' . $field . ') like ?', ['%' . $text . '%']);
          }
        }
      }
      
      $count = $data->count();
      $countFiltered = $data->count();

      if (!empty($filter->sortColumns)){
          foreach ($filter->sortColumns as $value){
              $data->orderBy($value->field, $value->dir);
          }
      } else {
          $data->orderBy('laundry_created_at', 'desc');
      }

      //paging
      $data->skip($filter->pageOffset)->take($filter->pageLimit);
  
      $grid = new \stdClass();
      $grid->recordsTotal = $count;
      $grid->recordsFiltered = $countFiltered;
      $grid->data = $data->get();
      return response()->json($grid);
    }
    

    public static function mapView2DatabaseName($viewName)
    {
      $mappers = array(
          'laundry_customer_name' => 'customer_name',
          'laundry_invoice' => 'laundry_invoice',
          'lunas' => 'lunas',
          'status' => 'status'
      );
      $mapperKeys = array_keys($mappers);
      return in_array($viewName, $mapperKeys) ? $mappers[$viewName] : $viewName;
    }
}
