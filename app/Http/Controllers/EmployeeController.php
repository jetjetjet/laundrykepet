<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Employee;
use Validator;
use Auth;

class EmployeeController extends Controller
{
  public function index()
  {
    return view('Employee.index');
  }

  public function getEmployeeLists(Request $request)
  {
    $data = Employee::join('users as cr', 'employee_created_by', 'cr.id')
      ->leftJoin('users as mod', 'employee_modified_by', 'mod.id')
      ->where('employee_active', '1')
      ->select('employees.id as id', 'employee_name', 'employee_contact', 'employee_sallary', 'employee_type', 'cr.user_name as employee_cr',
        'employee_created_at', 'mod.user_name as employee_mod', 'employee_modified_at');
    
    $count = $data->count();

    //Filter
    $countFiltered = $data->count();

    $grid = new \stdClass();
    $grid->recordsTotal = $count;
    $grid->recordsFiltered = $countFiltered;
    $grid->data = $data->get();

    return response()->json($grid);
  }

  public function getEdit(Request $request, $id = null)
  {
    $data = new \stdClass();
    $data = Employee::getFields($data);

    if($id){
      $data = Employee::join('users as cr', 'employee_created_by', 'cr.id')
      ->leftJoin('users as mod', 'employee_modified_by', 'mod.id')
      ->where('employee_active', '1')
      ->where('employees.id', $id)
      ->select('employees.id as id', 'employee_name', 'employee_contact', 'employee_address', 'employee_sallary', 'employee_type', 'cr.user_name as employee_created_by',
        'employee_created_at', 'mod.user_name as employee_modified_by', 'employee_modified_at')
      ->first();
      if($data == null){
        return view('Employee.index')->with(['error' => 'Data tidak Ditemukan']);
      }
    }
    
    return view('Employee.edit')->with('data', $data);
  }

  public function postDelete(Request $request, $id = null)
  {
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array());
        
    try{
      $emp = Employee::where('id',$id)->where('employee_active', '1')->firstOrFail();
      $emp->update([
        'employee_active' => '0',
        'employee_modified_by' => Auth::user()->getAuthIdentifier(),
        'employee_modified_at' => now()->toDateTimeString()
      ]);
      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $emp->employee_name . ' berhasil dihapus.';
      return response()->json($result);
    } catch (\Exception $e) {
      $result['errorMessages'] = $e->getMessage();
      return response()->json($result);
    }
  }

  public function postEdit(Request $request)
  {
    $rules = array(
      'employee_name' => 'required',
      'employee_type' => 'required'
    );

    $inputs = $request->all();
    $validator = Validator::make($inputs, $rules);

    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
      
    try{
      $emp = null;
      if(!$request->id) {
        $emp = Employee::create([
          'employee_name' => $request->employee_name,
          'employee_contact' => $request->employee_contact,
          'employee_address' => $request->employee_address,
          'employee_sallary' => $request->employee_sallary,
          'employee_type' => $request->employee_type,
          'employee_active' => '1',
          'employee_created_by' => Auth::user()->getAuthIdentifier(),
          'employee_created_at' => now()->toDateTimeString()
        ]);
        
        $request->session()->flash('successMessages', 'Data ' . $emp->employee_name . ' berhasil ditambah.');
        return redirect(action('EmployeeController@getEdit', array('id' => $emp->id)));
      } else {
        $emp = Employee::where('id',$request->id)->where('employee_active', '1')->firstOrFail();

        $emp->update([
          'employee_name' => $request->employee_name,
          'employee_contact' => $request->employee_contact,
          'employee_address' => $request->employee_address,
          'employee_sallary' => $request->employee_sallary,
          'employee_type' => $request->employee_type,
          'employee_modified_by' => Auth::user()->getAuthIdentifier(),
          'employee_modified_at' => now()->toDateTimeString()
        ]);

        $request->session()->flash('successMessages', 'Data ' . $emp->employee_name . ' berhasil diubah.');

        return redirect(action('EmployeeController@getEdit', array('id' => $emp->id)));
      }
    }catch(\Exception $e){
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }

  public function searchEmployee(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = Employee::
        whereRaw('UPPER(employee_name) LIKE UPPER(\'%'. $cari .'%\')')
        ->where('employee_active', '1')
        ->select('id', 'employee_name')
        ->get();
      return response()->json($data);
    }
  }
}
