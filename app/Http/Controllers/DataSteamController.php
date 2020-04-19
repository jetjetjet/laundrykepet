<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Steam;
use App\Http\Model\SteamDetail;
use App\Http\Libs\Helper;
use Auth;
use DB;

class DataSteamController extends Controller
{
    public function index()
    {
      return view('DataSteam.index');
    }

    public function getLists(Request $request)
    {
    $filter = Helper::getFilter($request);
      //dd($filter);
        $data = Steam::join('users as cr', 'steam_created_by', 'cr.id')
        ->Join('customers as cus', 'steam_customer_id', 'cus.id')
        ->where('steam_active', '1')
        ->select(
        'steams.id as id',
        'steam_invoice',
        'cus.customer_name as steam_customer_name',
        'cus.customer_phone as steam_customer_phone',
        'steam_date',
        'steam_total_price',
        'steam_finished_date',
        'cr.user_name as steam_finished_by',
        'steam_created_at',
        'cr.user_name as steam_created_by',
        'steam_modified_at',
        'cr.user_name as steam_modified_by',
        'steam_created_at',
        DB::raw('case when steam_executed_at is null then \'Draft\' 
        when steam_executed_at is not null and steam_finished_date is null then \'Diproses\'
        when steam_finished_at is not null then \'Selesai\'
        else \' \' end as steam_status'));

    if (!empty($filter->filterColumns)){
      foreach ($filter->filterColumns as $value){
        if (!isset($value->value)) continue;
        $trimmedText = trim($value->value);
        $text = strtolower(implode('%', explode(' ', $trimmedText)));

        $field = self::mapView2DatabaseName($value->field);
        if ($field == "steam_status"){
          switch($text){
            case "draft":
              $data = $data->whereNull('steam_executed_at');
            break;
            case "diproses":
              $data = $data->whereNotNull('steam_executed_at')
                ->whereNull('steam_finished_date');
            break;
            case "selesai":
              $data = $data->whereNotNull('steam_finished_date')
                ->whereNull('steam_delivered_at')
                ->whereNull('steam_taken_at');
            break;
            default:
          }
        } else if ($field == "steam_paidoff"){
          if($text == "lunas"){
            $data->where('steam_paidoff', '1');
          } else if ($text == "belum") {
            $data->where('steam_paidoff', '0');
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
        $data->orderBy('steam_created_at', 'desc');
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
        'steam_customer_name' => 'customer_name',
        'steam_invoice' => 'steam_invoice',
        'lunas' => 'lunas',
        'status' => 'status'
    );
    $mapperKeys = array_keys($mappers);
    return in_array($viewName, $mapperKeys) ? $mappers[$viewName] : $viewName;
  }
}
