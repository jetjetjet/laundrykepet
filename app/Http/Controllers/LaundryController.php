<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Model\Laundry;
use App\Http\Model\LaundryDetail;
use Validator;

use PDF;

use DB;
use Exception;

class LaundryController extends Controller
{
  public function Input(Request $request, $id = null)
  {
    $data = new \stdClass();
    $data = Laundry::getFields($data);
    if($id){
      $data = Laundry::laundryById($id)
        ->select(
          'laundries.id as id'
          ,'laundry_invoice'
          ,'laundry_customer_id'
          ,'cus.customer_name as laundry_customer_name'
          ,'laundry_paid'
          ,'laundry_paidoff'
          ,'laundry_delivery'
          ,'laundry_taken_at'
          ,'laundry_taken_by'
          ,'laundry_finished_at'
          ,'exe.user_name as laundry_executed_by'
          ,'laundry_executed_at'
          ,'fin.user_name as laundry_finished_by'
          ,'laundry_delivered_at'
          ,'dlv.employee_name as laundry_delivered_by'
          ,'cr.user_name as laundry_created_by'
          ,'laundry_created_at'
          ,'mod.user_name as laundry_modified_by'
          ,'laundry_modified_at')
        ->first();
      //jika data tidak ada
      if($data == null){
        return view('DataLaundry.index')->with(['error' => 'Data laundry tidak Ditemukan']);
      }
        $sub = LaundryDetail::detailLaundry($data['id']);
        $data->sub = $sub->select('ldetails.id',
          'ldetail_lcategory_id',
          'lc.lcategory_name as ldetail_lcategory_name',
          'lc.lcategory_price as price',
          'ldetail_start_date',
          DB::raw('TO_CHAR(ldetail_end_date, \'DD-MM-YYYY\') as ldetail_end_date'),
          'ldetail_qty',
          'ldetail_total')->get();
        $diff = $sub->select(DB::raw('sum(ldetail_total) as total'))->first();
        $data->diff = $diff['total'] - $data['laundry_paid'];
    } else {
      $date = date('01-m-Y');
      $count = Laundry::where('laundry_created_at', '>=', $date)->count();
      $data->laundry_invoice = "INV/LD/" . ($count + 1) . "/" . date('m/Y');
    }
    
    return view('Laundry.input')->with('data', $data);
  }

  public function generateReceipt($id)
  {
    $data = new \stdClass();
    $data = Laundry::laundryById($id)
      ->select(
        'laundries.id as id'
        ,'laundry_invoice'
        ,'cus.customer_name as customer_name'
        ,'cus.customer_address'
        ,'cus.customer_phone'
        ,'laundry_paid'
        ,'laundry_paidoff'
        ,'laundry_delivery'
        ,'cr.user_name as laundry_created_by'
        ,'laundry_created_at')
      ->first();

    if($data == null){
      return view('DataLaundry.index')->with(['error' => 'Data laundry tidak Ditemukan']);
    }

    $sub = LaundryDetail::detailLaundry($data['id']);
    $data->sub = $sub->select('ldetails.id',
      'ldetail_lcategory_id',
      'lc.lcategory_name as ldetail_lcategory_name',
      'lc.lcategory_price as price',
      'ldetail_start_date',
      DB::raw('TO_CHAR(ldetail_end_date, \'DD-MM-YYYY\') as ldetail_end_date'),
      'ldetail_qty',
      'ldetail_total')->get();
    
    $diff = $sub->select(DB::raw('sum(ldetail_total) as total'))->first();
    $data->total = $diff['total'];
    $data->diff = $diff['total'] - $data['laundry_paid'];

    $pdf = PDF::loadView('laundry.receipt', compact('data'));
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
      $laundry = Laundry::where('laundry_active', '1')->where('id', $id)->firstOrFail();
      switch($mode){
        case "execute":
          self::execute($laundry);
          $msg = "diproses.";
        break;
        case "finish":
          self::finish($laundry, $inputs);
          $msg = "diselesaikan.";
        break;
        case "delivery":
          self::delivery($laundry);
          $msg = "diantar.";
        break;
        default:
          $result['errorMessages'] = Array("Transaksi tidak diketahui");
          return response()->json($result);
      }
      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $laundry->laundry_invoice . ' berhasil '. $msg;
      return response()->json($result);
    } catch (\Exception $e) {
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

  private function execute($model)
  {
    return $model->update([
      'laundry_executed_at' => now()->toDateTimeString(),
      'laundry_executed_by' => Auth::user()->getAuthIdentifier(),
      'laundry_modified_at' => now()->toDateTimeString(),
      'laundry_modified_by' => Auth::user()->getAuthIdentifier()
    ]);
  }

  private function finish($model, $inputs)
  {
    $paid_off = $inputs['dp'] + $inputs['leftover'];
    return $model->update([
      'laundry_paid' => $paid_off,
      'laundry_paidoff' => '1',
      'laundry_finished_at' => now()->toDateTimeString(),
      'laundry_finished_by' => Auth::user()->getAuthIdentifier(),
      'laundry_modified_at' => now()->toDateTimeString(),
      'laundry_modified_by' => Auth::user()->getAuthIdentifier()
    ]);
  }

  private function delivery($model)
  {
    return $model->update([
      'laundry_delivered_at' => now()->toDateTimeString(),
      'laundry_delivered_by' => Auth::user()->getAuthIdentifier(),
      'laundry_modified_at' => now()->toDateTimeString(),
      'laundry_modified_by' => Auth::user()->getAuthIdentifier()
    ]);
  }

  public function postEdit(Request $request, $id = null)
  {
    $rules = array(
      'laundry_customer_id' => 'required'
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
        $valid = self::saveLaundry($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        if($id != null){
          $valid = self::removeMissingDetails($result, $result['laundry_id'], $details, $loginId);
        }

        $valid = self::saveDetails($result, $result['laundry_id'], $details, $loginId);
        if (!$valid) return $result;

        $result['success'] = true;
      });
    } catch (\Exception $e) {
      $request->session()->flash('errorMessages', 'Data gagal ditambah.');
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    $msg = $id == null ? ' ditambah.' : ' diubah.';
    $request->session()->flash('successMessages', 'Data Laundry berhasil '. $msg);
    return redirect(action('LaundryController@input', array('id' => $result['laundry_id'])));
  }

  public function saveLaundry(&$result, $id, $input, $loginId)
  {
    $total = isset($input['laundry_total']) ? $input['laundry_total'] : 0;
    $bayar = isset($input['laundry_paid']) ? $input['laundry_paid'] : 1;
    $laundry = null;
    $laundry_delivery =  isset($input['laundry_delivery']) ? $input['laundry_delivery'] : false;
    $kurir = isset($input['laundry_delivered_id']) ? $input['laundry_delivered_id'] : false ;
    $paidoff = $total - $bayar == 0 ? true : false;
    try{
      if ($id == null){
        $laundry = Laundry::create([
          'laundry_invoice' => $input['laundry_invoice'],
          'laundry_customer_id' => $input['laundry_customer_id'],
          'laundry_paid' => $input['laundry_paid'],
          'laundry_paidoff' => $paidoff,
          'laundry_delivery' => $laundry_delivery,
          
          'laundry_active' => '1',
          'laundry_created_at' => now()->toDateTimeString(),
          'laundry_created_by' => $loginId
        ]);
      } else {
        $laundry = Laundry::where('laundry_active', '1')->where('id', $id)->firstOrFail();

        if($kurir){
          $laundry->update([
            'laundry_delivered_by' => $kurir
          ]);
        } else {
          $laundry->update([
            'laundry_customer_id' => $input['laundry_customer_id'],
            'laundry_paid' => $input['laundry_paid'],
            'laundry_paidoff' => $paidoff,
            'laundry_delivery' => $laundry_delivery,
            'laundry_modified_at' => now()->toDateTimeString(),
            'laundry_modified_by' => $loginId
          ]);
        }
      }
      
      $result['laundry_id'] = $laundry->id ?: $id;
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
      $data = LaundryDetail::where('ldetail_active', '1')
        ->where('ldetail_laundry_id', $id)
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
          $det = LaundryDetail::create([
            'ldetail_laundry_id' => $id,
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
          $det = LaundryDetail::where('ldetail_active', '1')->where('id', $dtl->id)->firstOrFail();
          $det->update([
            'ldetail_laundry_id' => $id,
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
      $laundry = Laundry::where('laundry_active', '1')->where('id', $id)->firstOrFail();
      $details = LaundryDetail::where('ldetail_active', '1')->where('ldetail_laundry_id', $id);

      $laundry->update([
      'laundry_active' => '0',
      'laundry_modified_at' => now()->toDateTimeString(),
      'laundry_modified_by' => $loginId
      ]);

      $details->update([
        'ldetail_active' => '0',
        'ldetail_modified_by' => $loginId,
        'ldetail_modified_at' => now()->toDateTimeString()
      ]);

      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $laundry->laundry_invoice . ' berhasil dihapus.';
      return response()->json($result);
    } catch (\Exception $e) {
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

  public function postPickup(Request $request, $id = null)
  {
    $loginId = Auth::user()->getAuthIdentifier();
    $cust = isset($request['laundry_taken_by']) ? $request['laundry_taken_by'] : '' ;
    try{
      $ld = Laundry::where('laundry_active', '1')->where('id', $id)->firstOrFail();
      $ld->update([
        'laundry_taken_by' => $cust,
        'laundry_taken_at' => now()->toDateTimeString(),
        'laundry_modified_at' => now()->toDateTimeString(),
        'laundry_modified_by' => $loginId
      ]);
      $request->session()->flash('successMessages', 'Data ' . $ld->laundry_invoice . ' berhasil diubah.');
      return redirect(action('LaundryController@input', array('id' => $id)));
    } catch(\Exception $e){
      $request->session()->flash('ErrorMessages', 'Data gagal diubah.');
      return redirect(action('LaundryController@input', array('id' => $id)));
    }
  }

  public function postDelivery(Request $request, $id = null)
  {
    $loginId = Auth::user()->getAuthIdentifier();
    $petugas = isset($request['laundry_delivered_id']) ? $request['laundry_delivered_id'] : '' ;
    try{
      $ld = Laundry::where('laundry_active', '1')->where('id', $id)->firstOrFail();
      $ld->update([
        'laundry_delivered_by' => $petugas,
        'laundry_delivered_at' => now()->toDateTimeString(),
        'laundry_modified_at' => now()->toDateTimeString(),
        'laundry_modified_by' => $loginId
      ]);
      $request->session()->flash('successMessages', 'Data ' . $ld->laundry_invoice . ' berhasil diubah.');
      return redirect(action('LaundryController@input', array('id' => $id)));
    } catch(\Exception $e){
      $request->session()->flash('ErrorMessages', 'Data gagal diubah.');
      return redirect(action('LaundryController@input', array('id' => $id)));
    }
  }
}
