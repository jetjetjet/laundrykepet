<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Customer;
use Carbon\Carbon;
use Validator;
use Auth;

class CustomersController extends Controller
{
  public function index()
  {
      return view('Customers.index');
  }

  public function getCustomerLists(Request $request){
    $data = Customer::join('users as cr', 'customer_created_by', 'cr.id')
      ->leftJoin('users as mod', 'customer_modified_by', 'mod.id')
      ->where('customer_active', '1')
      ->select('customers.id as id', 'customer_name', 'customer_phone', 'customer_address', 'cr.user_name as customer_cr',
        'customer_created_at', 'mod.user_name as customer_mod', 'customer_modified_at');
    
    $count = $data->count();

    //Filter
    $countFiltered = $data->count();

    $grid = new \stdClass();
    $grid->recordsTotal = $count;
    $grid->recordsFiltered = $countFiltered;
    $grid->data = $data->get();

    return response()->json($grid);
  }

  public function getEdit(Request $request, $id = null){
    $data = new \stdClass();
    $data = Customer::getFields($data);

    if($id){
      $data = Customer::join('users as cr', 'customer_created_by', 'cr.id')
      ->leftJoin('users as mod', 'customer_modified_by', 'mod.id')
      ->where('customer_active', '1')
      ->where('customers.id', $id)
      ->select('customers.id as id', 'customer_name', 'customer_phone', 'customer_address', 'cr.user_name as customer_created_by',
          'customer_created_at', 'mod.user_name as customer_modified_by', 'customer_modified_at')
      ->first();
      if($data == null){
        return view('Customers.index')->with(['error' => 'Data tidak Ditemukan']);
      }
    }
    
    return view('Customers.edit')->with('data', $data);
  }

  public function postDelete(Request $request){
    $id = $request->input('id');

    try{
      $cust = Customer::where('id',$id)->where('customer_active', '1')->firstOrFail();
      $cust->update([
        'customer_active' => '0',
        'customer_modified_by' => Auth::user()->getAuthIdentifier(),
        'customer_modified_at' => now()->toDateTimeString()
      ]);
      $request->session()->flash('successMessages', 'Data ' . $cust->customer_name . ' berhasil dihapus.');
      return redirect(action('CustomersController@index'));
    } catch (\Exception $e) {
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }

  public function postEdit(Request $request){
    $rules = array(
      'customer_name' => 'required',
      'customer_phone' => 'required'
    );

    $inputs = $request->all();
    $validator = Validator::make($inputs, $rules);

    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
      
    try{
      $cust = null;
      if(!$request->id) {
        $cust = Customer::create([
          'customer_name' => $request->customer_name,
          'customer_phone' => $request->customer_phone,
          'customer_address' => $request->customer_address,
          'customer_active' => '1',
          'customer_created_by' => Auth::user()->getAuthIdentifier(),
          'customer_created_at' => now()->toDateTimeString()
        ]);
        
        if(isset($request->modal)){
          $resp = Array(
            "success" => true,
            "messages" => 'Data ' . $cust->customer_name . ' berhasil ditambah.'
          );
          return response()->json($resp);
        } else {
          $request->session()->flash('successMessages', 'Data ' . $cust->customer_name . ' berhasil ditambah.');
          return redirect(action('CustomersController@getEdit', array('id' => $cust->id)));
        }
      } else {
        $cust = Customer::where('id',$request->id)->where('customer_active', '1')->firstOrFail();

        $cust->update([
          'customer_name' => $request->customer_name,
          'customer_phone' => $request->customer_phone,
          'customer_address' => $request->customer_address,
          'customer_modified_by' => Auth::user()->getAuthIdentifier(),
          'customer_modified_at' => now()->toDateTimeString()
        ]);

        $request->session()->flash('successMessages', 'Data ' . $cust->customer_name . ' berhasil diubah.');

        return redirect(action('CustomersController@getEdit', array('id' => $cust->id)));
      }
    }catch(\Exception $e){
      dd($e);
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }

  public function searchCustomer(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = Customer::
        where('customer_name', 'LIKE', '%'.$cari.'%')
        ->where('customer_active', '1')
        ->select('id', 'customer_name')
        ->get();
      return response()->json($data);
    }
  }
}