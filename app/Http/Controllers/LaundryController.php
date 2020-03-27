<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Model\Laundry;
use App\Http\Model\LaundryDetail;
use Validator;

class LaundryController extends Controller
{
    public function Input(Request $request, $id = null)
    {
      $data = new \stdClass();
      $data = Laundry::getFields($data);
      if($id){
        $data = Laundry::join('users as cr', 'laundry_created_by', 'cr.id')
          ->leftJoin('customers as cus', 'laundry_customer_id', 'cus.id')
          ->leftJoin('users as mod', 'laundry_modified_by', 'mod.id')
          ->leftJoin('users as fin', 'laundry_finished_by', 'fin.id')
          ->leftJoin('users as delv', 'laundry_delivered_by', 'delv.id')
          ->where('laundry_active', '1')
          ->where('laundries.id', $id)
          ->select(
            'laundries.id as id'
            ,'laundry_customer_id'
            ,'cus.customer_name as laundry_customer_name'
            ,'laundry_est_date'
            ,'laundry_paid'
            ,'laundry_paidoff'
            ,'laundry_delivery'
            ,'laundry_finished_at'
            ,'fin.user_name as laundry_finished_by'
            ,'laundry_delivered_at'
            ,'delv.user_name as laundry_delivered_by'
            ,'cr.user_name as laundry_created_by'
            ,'laundry_created_at'
            ,'mod.user_name as laundry_modified_by'
            ,'laundry_modified_at')
          ->first();
      } else {
        $date = date('01-m-Y');
        $count = Laundry::where('laundry_created_at', '>=', $date)->count();
        $data->laundry_invoice = "INV/LD/" . ($count + 1) . "/" . date('m/Y');
      }
      
      return view('Laundry.input')->with('data', $data);
    }

    public function postEdit(Request $request, $id = null)
    {
      $rules = array(
        'lcategory_name' => 'required',
        'lcategory_price' => 'required'
      );
  
      $inputs = $request->all();
      $subSjrs = $this->mapRowsX(isset($inputs['dtl']) ? $inputs['dtl'] : null);
      dd($inputs, isset($inputs['dtl']));
      $validator = Validator::make($inputs, $rules);
  
      if ($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput($inputs);
      }
      
      
    }
}
