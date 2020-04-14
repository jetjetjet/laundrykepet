<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Laundry;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $data = new \StdClass();
    $data->thn = Array();
    $thn =Carbon::now()->format('Y');
    array_push($data->thn,$thn-0);
    for($i=0; $i<2; $i++){
      $thn = $thn - 1;
      array_push($data->thn,$thn);
    }
    
    $blnNow = Carbon::now()->format('m');
    $data->bln = Array();
    $bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    foreach($bln as $key  => $val){
      $anka = $key + 1;
      $temp = new \StdClass();
      $temp->bln = $val;
      $temp->val = strlen($anka) == 1 ? 0 . $anka:$key;;
      $temp->skrg = $blnNow == $key + 1 ? true:false;
      
      array_push($data->bln, $temp);
    }
    return view('Home.Dashboard')->with('data', $data);
  }

  public function getDataDash(Request $request)
  {
    $inputs = $request->all();
    $bln = isset($request->bulan) ? $request->bulan : Carbon::now()->format('m');
    $thn = isset($request->tahun) ? $request->tahun : Carbon::now()->format('Y');
    $filter = $thn . '-' . $bln;
    $parse = Carbon::parse($filter);
    $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
    $transaction = Laundry::select(DB::raw('date(laundry_created_at) as date,sum(laundry_paid) as total'))
      ->where('laundry_created_at', 'LIKE', '%' . $filter . '%')
      ->groupBy(DB::raw('date(laundry_created_at)'))->get();

    $data = new \StdClass();
    $nom = [];
    foreach ($array_date as $row) {
      $f_date = strlen($row) == 1 ? 0 . $row:$row;
      $date = $filter . '-' . $f_date;
      $total = $transaction->firstWhere('date', $date);
      
      array_push($nom,$total ? $total->total:0);
    }
    
    $data->chartTotal = implode(',', $nom);
    $data->chartTgl = implode(',', $array_date);

    $data->total = Laundry::Laundry()
      ->where('laundry_created_at', 'LIKE', '%' . $filter . '%')
      ->count();
    $data->draft = Laundry::Laundry()
      ->where('laundry_created_at', 'LIKE', '%' . $filter . '%')
      ->whereNull('laundry_executed_at')->count();
    $data->executed = Laundry::Laundry()
      ->where('laundry_executed_at', 'LIKE', '%' . $filter . '%')
      ->whereNotNull('laundry_executed_at')
      ->whereNull('laundry_finished_at')->count();
    $data->finished = Laundry::Laundry()
      ->where('laundry_finished_at', 'LIKE', '%' . $filter . '%')
      ->whereNotNull('laundry_executed_at')
      ->whereNotNull('laundry_finished_at')
      ->whereNull('laundry_delivered_at')
      ->whereNull('laundry_taken_at')
      ->count();
    $data->delivery = Laundry::Laundry()
      ->where('laundry_delivered_at', 'LIKE', '%' . $filter . '%')
      ->where('laundry_delivery', '1')
      ->whereNotNull('laundry_finished_at')
      ->whereNotNull('laundry_delivered_at')
      ->count();
    $data->taken = Laundry::Laundry()
      ->where('laundry_taken_at', 'LIKE', '%' . $filter . '%')
      ->where('laundry_delivery', '0')
      ->whereNotNull('laundry_finished_at')
      ->whereNotNull('laundry_taken_at')
      ->count();

    return response()->json($data);
  }
}
