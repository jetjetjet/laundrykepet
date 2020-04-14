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
        ->where('laundry_active', '1')
        ->select('laundries.id as id', 
          'laundry_invoice', 
          'laundry_paid',
          'laundry_paidoff',
          'laundry_delivery',
          'cust.customer_name as laundry_customer_name',
          'cust.customer_phone as laundry_customer_phone',
          'cust.customer_address as laundry_customer_address',
          DB::raw('case when laundry_executed_at is null then \'Draft\' 
            when laundry_executed_at is not null and laundry_finished_at is null then \'Diproses\'
            when laundry_finished_at is not null and (laundry_delivered_at is null and laundry_taken_at is null) then \'Selesai\'
            when laundry_delivered_at is not null then \'Diantar\'
            when laundry_taken_at is not null then \'Diambil\'
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
              case "draft":
                $data = $data->whereNull('laundry_executed_at');
              break;
              case "diproses":
                $data = $data->whereNotNull('laundry_executed_at')
                  ->whereNull('laundry_finished_at');
              break;
              case "selesai":
                $data = $data->whereNotNull('laundry_finished_at')
                  ->whereNull('laundry_delivered_at')
                  ->whereNull('laundry_taken_at');
              break;
              case "diantar":
                $data = $data->whereNotNull('laundry_delivered_at');
              break;
              case "diambil":
                $data = $data->whereNotNull('laundry_taken_at');
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
