<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Laundry;

class ReportController extends Controller
{
  public function getLaundryReport(Request $request)
  {
    $filter = new \stdClass();
    $filter->startDate = $request->input('startDate');
    $filter->endDate = $request->input('endDate');
    $filter->statusBayar = $request->input('statusBayar');
    $filter->status = $request->input('status');
    if($filter->startDate == null && $filter->endDate == null){
      $data = [];
    } else {
      $data = Laundry::laundryReport($filter)->get();
    }
    return View('Laporan.Laundry')->with('data', $data);
  }
}
