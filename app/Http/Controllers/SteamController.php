<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Steam;
use App\Http\Model\SteamDetail;
use App\Http\Libs\Helper;
use Auth;
use DB;
use PDF;
use Validator;
use Exception;

class SteamController extends Controller
{
  public function Input(Request $request, $id = null)
  {
    $data = new \stdClass();
    $data = Steam::getFields($data);
    if($id){
      $data = Steam::steamById($id)
        ->select(
          'steams.id as id'
          ,'steam_customer_id'
          ,'steam_invoice'
          ,'cus.customer_name as steam_customer_name'
          ,'steam_paid'
          ,'steam_paidoff'
          ,'steam_taken_by'
          ,'steam_taken_at'          
          ,'exe.user_name as steam_executed_by'
          ,'steam_executed_at'
          ,'fin.user_name as steam_finished_by'
          ,'steam_finished_at'
          ,'cr.user_name as steam_created_by'
          ,'steam_created_at'
          ,'mod.user_name as steam_modified_by'
          ,'steam_modified_at')
        ->first();
      //jika data tidak ada
      if($data == null){
        return view('DataSteam.index')->with(['error' => 'Data Steam tidak Ditemukan']);
      }
        $sub = SteamDetail::detailSteam($data['id']);
        $data->sub = $sub->select('sdetails.id',
          'sdetail_scategory_id',
          'sc.scategory_name as sdetail_scategory_name',
          'sc.scategory_price as price',
          'sdetail_start_date',
          DB::raw('TO_CHAR(sdetail_end_date, \'DD-MM-YYYY\') as sdetail_end_date'),
          'sdetail_qty',
          'sdetail_price')->get();
        $diff = $sub->select(DB::raw('sum(sdetail_price) as total'))->first();
        $data->diff = $diff['total'] - $data['steam_paid'];
    } else {
      $date = date('01-m-Y');
      $count = Steam::where('steam_created_at', '>=', $date)->count();
      $data->steam_invoice = "INV/ST/" . ($count + 1) . "/" . date('m/Y');
    }    
    return view('Steam.input')->with('data', $data);
  }

  public function generateReceipt($id)
  {
    $data = new \stdClass();
    $data = Steam::steamById($id)
      ->select(
        'steams.id as id'
        ,'steam_invoice'
        ,'cus.customer_name as customer_name'
        ,'cus.customer_address'
        ,'cus.customer_phone'
        ,'steam_paid'
        ,'steam_paidoff'
        ,'cr.user_name as steam_created_by'
        ,'steam_created_at')
      ->first();

    if($data == null){
      return view('DataSteam.index')->with(['error' => 'Data Steam tidak Ditemukan']);
    }

    $sub = SteamDetail::detailSteam($data['id']);
    $data->sub = $sub->select('sdetails.id',
      'sdetail_scategory_id',
      'sc.lcategory_name as sdetail_scategory_name',
      'sc.lcategory_price as price',
      'sdetail_start_date',
      DB::raw('TO_CHAR(sdetail_end_date, \'DD-MM-YYYY\') as sdetail_end_date'),
      'sdetail_qty',
      'sdetail_price')->get();
    
    $diff = $sub->select(DB::raw('sum(sdetail_price) as total'))->first();
    $data->total = $diff['total'];
    $data->diff = $diff['total'] - $data['steam_paid'];

    $pdf = PDF::loadView('steam.receipt', compact('data'));
    return $pdf->stream("asd.pdf");
    //return view('Laundry.receipt')->with('data', $data);
  }

  public function postUbahStatus(Request $request, $id, $mode)
  {
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array());
    
    $inputs = $request->all();
    $loginId = Auth::user()->getAuthIdentifier();
    $msg = "";
    try{
      $steam = Steam::where('steam_active', '1')->where('id', $id)->firstOrFail();
      switch($mode){
        case "execute":
          self::execute($steam);
          $msg = "diproses.";
        break;
        case "finish":
          self::finish($steam, $inputs);
          $msg = "diselesaikan.";
        break;
        default:
          $result['errorMessages'] = Array("Transaksi tidak diketahui");
          return response()->json($result);
      }
      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $steam->steam_invoice . ' berhasil '. $msg;
      return response()->json($result);
    } catch (\Exception $e) {
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

  private function execute($model)
  {
    return $model->update([
      'steam_executed_at' => now()->toDateTimeString(),
      'steam_executed_by' => Auth::user()->getAuthIdentifier(),
      'steam_modified_at' => now()->toDateTimeString(),
      'steam_modified_by' => Auth::user()->getAuthIdentifier()
    ]);
  }

  private function finish($model, $inputs)
  {
    $paid_off = $inputs['dp'] + $inputs['leftover'];
    return $model->update([
      'steam_paid' => $paid_off,
      'steam_paidoff' => '1',
      'steam_finished_at' => now()->toDateTimeString(),
      'steam_finished_by' => Auth::user()->getAuthIdentifier(),
      'steam_modified_at' => now()->toDateTimeString(),
      'steam_modified_by' => Auth::user()->getAuthIdentifier()
    ]);
  }

  public function postEdit(Request $request, $id = null)
  {
    $rules = array(
      'steam_customer_id' => 'required'
    );

    $inputs = $request->all();
    $details = $this->mapRowsX(isset($inputs['dtl']) ? $inputs['dtl'] : null);
    $validator = Validator::make($inputs, $rules);

    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }

    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array(), 'successMessages' => array());
    $loginId = Auth::user()->getAuthIdentifier();
    $id = $inputs['id'] ?: $id ;
    try{
      DB::transaction(function () use (&$result, $id, $details,  $inputs, $loginId)
      {
        $valid = self::saveSteam($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        if($id != null){
          $valid = self::removeMissingDetails($result, $result['steam_id'], $details, $loginId);
        }

        $valid = self::saveDetails($result, $result['steam_id'], $details, $loginId);
        if (!$valid) return $result;

        $result['success'] = true;
      });
    } catch (\Exception $e) {
      $request->session()->flash('errorMessages', 'Data gagal ditambah.');
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    $msg = $id == null ? ' ditambah.' : ' diubah.';
    $request->session()->flash('successMessages', 'Data Steam berhasil '. $msg);
    return redirect(action('SteamController@input', array('id' => $result['steam_id'])));
  }

  public function saveSteam(&$result, $id, $input, $loginId)
  {
    $total = isset($input['steam_total']) ? $input['steam_total'] : 0;
    $bayar = isset($input['steam_paid']) ? $input['steam_paid'] : 1;
    $steam = null;
    $steam_delivery =  isset($input['steam_delivery']) ? $input['steam_delivery'] : false;
    $kurir = isset($input['steam_delivered_id']) ? $input['steam_delivered_id'] : false ;
    $paidoff = $total - $bayar == 0 ? true : false;
    try{
      if ($id == null){
        $steam = Steam::create([
          'steam_invoice' => $input['steam_invoice'],
          'steam_customer_id' => $input['steam_customer_id'],
          'steam_paid' => $input['steam_paid'],
          'steam_paidoff' => $paidoff,
          'steam_delivery' => $steam_delivery,
          
          'steam_active' => '1',
          'steam_created_at' => now()->toDateTimeString(),
          'steam_created_by' => $loginId
        ]);
      } else {
        $steam = Steam::where('steam_active', '1')->where('id', $id)->firstOrFail();

        if($kurir){
          $steam->update([
            'steam_delivered_by' => $kurir
          ]);
        } else {
          $steam->update([
            'steam_customer_id' => $input['steam_customer_id'],
            'steam_paid' => $input['steam_paid'],
            'steam_paidoff' => $paidoff,
            'steam_delivery' => $steam_delivery,
            'steam_modified_at' => now()->toDateTimeString(),
            'steam_modified_by' => $loginId
          ]);
        }
      }
      
      $result['steam_id'] = $steam->id ?: $id;
      return true;
    } catch (\Exception $e) {
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
      return false;
    }
  }

  public function removeMissingDetails(&$result, $id, $details, $loginId)
  {
    $ids = Array();
    foreach($details as $dt){
      array_push($ids,$dt->id);
    }
    try{
      $data = SteamDetail::where('ldetail_active', '1')
        ->where('ldetail_steam_id', $id)
        ->whereNotIn('id', $ids)
        ->update([
          'ldetail_active' => '0',
          'ldetail_modified_by' => $loginId,
          'ldetail_modified_at' => now()->toDateTimeString()
          ]);
      return true;
    } catch(Exception $e){
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
      return false;
    }
  }

  public function saveDetails(&$result, $id, $details, $loginId)
  {
    try{ 
      $det = null;
      foreach ($details as $dtl){
        if (!isset($dtl->id)){
          $det = SteamDetail::create([
            'ldetail_steam_id' => $id,
            'ldetail_lcategory_id' => $dtl->ldetail_lcategory_id,
            'ldetail_qty' => $dtl->ldetail_qty,
            'ldetail_start_date' => now()->toDateTimeString(),
            'ldetail_end_date' => $dtl->ldetail_end_date,
            'ldetail_total' => $dtl->ldetail_total,
            'ldetail_active' => '1',
            'ldetail_created_at' => now()->toDateTimeString(),
            'ldetail_created_by' =>$loginId
          ]);
        } else {
          $det = SteamDetail::where('ldetail_active', '1')->where('id', $dtl->id)->firstOrFail();
          $det->update([
            'ldetail_steam_id' => $id,
            'ldetail_lcategory_id' => $dtl->ldetail_lcategory_id,
            'ldetail_qty' => $dtl->ldetail_qty,
            'ldetail_end_date' => $dtl->ldetail_end_date,
            'ldetail_total' => $dtl->ldetail_total,
            'ldetail_modified_at' => now()->toDateTimeString(),
            'ldetail_modified_by' =>$loginId
          ]);
        }
      }
      return true;
    } catch(\Exception $e) {
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
      return false;
    }
  }

  public function postDelete(Request $request, $id = null)
  {
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array());
    $loginId = Auth::user()->getAuthIdentifier();
    try {
      $steam = Steam::where('steam_active', '1')->where('id', $id)->firstOrFail();
      $details = SteamDetail::where('ldetail_active', '1')->where('ldetail_steam_id', $id);

      $steam->update([
      'steam_active' => '0',
      'steam_modified_at' => now()->toDateTimeString(),
      'steam_modified_by' => $loginId
      ]);

      $details->update([
        'ldetail_active' => '0',
        'ldetail_modified_by' => $loginId,
        'ldetail_modified_at' => now()->toDateTimeString()
      ]);

      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $steam->steam_invoice . ' berhasil dihapus.';
      return response()->json($result);
    } catch (\Exception $e) {
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

  public function postPickup(Request $request, $id = null)
  {
    $loginId = Auth::user()->getAuthIdentifier();
    $cust = isset($request['steam_taken_by']) ? $request['steam_taken_by'] : '' ;
    try{
      $ld = Steam::where('steam_active', '1')->where('id', $id)->firstOrFail();
      $ld->update([
        'steam_taken_by' => $cust,
        'steam_taken_at' => now()->toDateTimeString(),
        'steam_modified_at' => now()->toDateTimeString(),
        'steam_modified_by' => $loginId
      ]);
      $request->session()->flash('successMessages', 'Data ' . $ld->steam_invoice . ' berhasil diubah.');
      return redirect(action('steamController@input', array('id' => $id)));
    } catch(\Exception $e){
      $request->session()->flash('ErrorMessages', 'Data gagal diubah.');
      return redirect(action('steamController@input', array('id' => $id)));
    }
  }

  public function postDelivery(Request $request, $id = null)
  {
    $loginId = Auth::user()->getAuthIdentifier();
    $petugas = isset($request['steam_delivered_id']) ? $request['steam_delivered_id'] : '' ;
    try{
      $ld = Steam::where('steam_active', '1')->where('id', $id)->firstOrFail();
      $ld->update([
        'steam_delivered_by' => $petugas,
        'steam_delivered_at' => now()->toDateTimeString(),
        'steam_modified_at' => now()->toDateTimeString(),
        'steam_modified_by' => $loginId
      ]);
      $request->session()->flash('successMessages', 'Data ' . $ld->steam_invoice . ' berhasil diubah.');
      return redirect(action('SteamController@input', array('id' => $id)));
    } catch(\Exception $e){
      $request->session()->flash('ErrorMessages', 'Data gagal diubah.');
      return redirect(action('SteamController@input', array('id' => $id)));
    }
  }
}
