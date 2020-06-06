<?php

namespace App\Http\Controllers;

use App\Http\Model\LAbsen;
use App\Http\Model\DAbsen;
use App\Http\Model\Employee;
use Illuminate\Http\Request;
use Auth;
use DB;

class LAbsenController extends Controller
{
  public function index()
  {
    return view('Absen.laundry');
  }

  public function getList(Request $request)
  {
    $data = LAbsen::getList();
    $count = $data->count();
    $countFiltered = $data->count();
  
    $grid = new \stdClass();
    $grid->recordsTotal = $count;
    $grid->recordsFiltered = $countFiltered;
    $grid->data = $data->get();
    
    return response()->json($grid);
  }

  public function getEdit(Request $request, $id = null)
  {
    $data = new \StdClass();
    $data->id = null;
    $data->labsen_detail = null;
    $data->hadir = [];
    if($id){
      $data = LAbsen::where('labsen_active', '1')
        ->join('users as cr', 'cr.id', 'labsen_created_by')
        ->where('labsen.id', $id)
        ->select('labsen.id', 'labsen_detail', 'labsen_created_at', 'cr.user_name as labsen_created_by')
        ->first();
      $hadir = DAbsen::where('dabsen_active', '1')
        ->where('dabsen_labsen_id', $id)
        ->select('dabsen_employee_id')
        ->get();
      
      $temp = [];
      foreach($hadir as $hdr){
        array_push( $temp, $hdr->dabsen_employee_id);
      }
      $data->hadir = $temp;
    }
    $data->employeeList = Employee::laundryEmployee()
      ->select('id as employee_id', 'employee_name')->get();
    return view('Absen.edit')->with('data', $data);
  }

  public function postEdit(Request $request, $id = null)
  {
    $inputs = $request->all();
    $id = $inputs['id'] ?: $id ;
    $inputs['employees'] = !empty($inputs['employee']) ? $inputs['employee'] : array();
    $loginId = Auth::user()->getAuthIdentifier();
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array(), 'successMessages' => array());
  
    $cekTgl = LAbsen::where('labsen_active', '1')->whereRaw('CAST(labsen_created_at as Date) = CAST(now() as Date)')->first();
    if($cekTgl != null){
      $request->session()->flash('errorMessages', 'Absen hari ini sudah dilakukan');
      return redirect(action('LAbsenController@index'));
    }

    try{
      DB::transaction(function () use (&$result, $id, $inputs, $loginId){
        $valid = self::saveAbsen($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        if($id != null){
          $valid = self::removeMissingEmployee($result, $id, $inputs, $loginId);
        }

        $valid = self::saveDetailAbsen($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        $result['success'] = true;
      });
    } catch (\Exception $e){
      $request->session()->flash('errorMessages', $e);
      return redirect()->back()->withInput($inputs);
    }
    $request->session()->flash('successMessages', 'Data absensi berhasil ditambah.');
    return redirect(action('LAbsenController@index'));
  }

  public function saveAbsen(&$result, $id, $inputs, $loginId){
    $absen = null;
    try{
      if($id == null){
        $absen = LAbsen::Create([
          'labsen_detail' => isset($inputs['labsen_detail']) ? $inputs['labsen_detail'] :null,
          'labsen_active' => '1',
          'labsen_created_by' => $loginId,
          'labsen_created_at' => now()->todatetimestring()
        ]);
      } else {
        $absen = LAbsen::where('labsen_active', '1')->where('id', $id)->firstOrFail();
        $absen->update([
          'labsen_detail' => isset($inputs['labsen_detail']) ? $inputs['labsen_detail'] :null,
          'labsen_modified_by' => $loginId,
          'labsen_modified_at' => now()->todatetimestring()
        ]);
      }
    } catch (Exception $e) {
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
    }
    $result['labsen_id'] = $absen->id ?: $id;
    return true;
  }

  public function saveDetailAbsen(&$result, $id, $inputs, $loginId)
  {
    $id_absen = $result['labsen_id'];
    $employees = $inputs['employees'] ?: Array();
    foreach($employees as $emp){
      $dAbsen = DAbsen::where('dabsen_labsen_id', $id_absen)
        ->where('dabsen_employee_id', $emp)
        ->where('dabsen_active', '1')
        ->where(DB::raw('CAST(dabsen_created_at as DATE) = CAST(now() as Date)'))
        ->first();
        
      if($dAbsen == null){
        DAbsen::create([
          'dabsen_labsen_id' => $id_absen,
          'dabsen_employee_id' => $emp,
          'dabsen_active' => '1',
          'dabsen_created_by' => $loginId,
          'dabsen_created_at' => now()->toDateTimeString()
        ]);
      }
    }
    return true;
  }

  public function removeMissingEmployee(&$result, $id, $inputs, $loginId)
  {
    try{
      $data = DAbsen::where('dabsen_active', '1')
        ->where('dabsen_employee_id', $id)
        ->whereNotIn('dabsen_user_id', $inputs['user_id'])
        ->update([
          'dabsen_active' => '0',
          'dabsen_modified_by' => $loginId,
          'dabsen_modified_at' => now()->todatetimestring()
          ]);
    } catch(Exception $e){
      dd('Err', $e);
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
    }
    return true;
  }

  public function postDelete(Request $request)
  {
    //
  }
}
