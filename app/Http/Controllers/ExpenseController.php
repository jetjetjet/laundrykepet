<?php

namespace App\Http\Controllers;

use App\Http\Model\Expense;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('Expense.index');
    }
    public function getGrid(Request $request)
    {
        $data=Expense::join('users as cr','expense_created_by','cr.id')
    ->leftJoin('users as mod','expense_modified_by','mod.id')
    ->where('expense_active','1')
      ->select('expense.id as id', 
        'expense_name',
        'expense_detail',
        'expense_price',
        'expense_active',
        'expense_created_at',
        'cr.user_name as expense_created_by',
        'expense_modified_at',
        'mod.user_name as expense_modified_by' );
 
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
        $data = new \stdClass();
      $data = expense::getFields($data);
 
      if($id)
      {
        $data=Expense::join('users as cr','expense_created_by','cr.id')
          ->leftJoin('users as mod','expense_modified_by','mod.id')
          ->where('expense_active','1')
          ->where('expense.id', $id)
          ->select('expense.id as id'
          ,'expense_name'
          ,'expense_detail'
          ,'expense_price'
          ,'expense_active'
          ,'expense_created_at'
          ,'cr.user_name as expense_created_by'
          ,'expense_modified_at'
          ,'mod.user_name as expense_modified_by')->first();
        if( $data == null)
        {
          return view('expense.index')->with(['errorMessages'=>'Data Tidak Ditemukan']);
        }
      }
    return view('expense.edit')->with('data',$data);
    }

    public function postEdit(Request $request, $id = null)
    {
        $rules = array(
            'expense_name' => 'required'
          );
       
          $inputs = $request->all();
          $validator = validator::make($inputs, $rules);
       
          if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($inputs);
          }
          try {
            $expense = null;
            if (!isset($request->id)){
              $expense = Expense::create([
                'expense_name' => $request->expense_name,
                'expense_detail' => $request->expense_detail,
                'expense_price' => $request->expense_price,
                'expense_active' => '1',
                'expense_created_by' => Auth::user()->getAuthIdentifier(),
                'expense_created_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $expense->expense_name . ' berhasil ditambah.');
            } else {
              $expense = expense::where('expense_active', '1')->where('id', $request->id)->firstOrFail();
              $expense->update([
                'expense_name' => $request->expense_name,
                'expense_detail' => $request->expense_detail,
                'expense_price' => $request->expense_price,
                'expense_modified_by' => Auth::user()->getAuthIdentifier(),
                'expense_modified_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $expense->expense_name . ' berhasil diubah.');
      
            }
            return redirect(action('ExpenseController@getEdit', array('id' => $expense->id)));
          } catch(\Excaption $e){
            return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
          }
    }

    public function postDelete(Request $request, $id = null)
    {
        $rules = array('success'=> false,'errorMessages' =>array(), 'debugMassages' =>array());
    $expense = Expense::where('expense_active', '1')->where('id', $id)->first();
 
    if($expense == null){
      $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
      return redirect(action('ExpenseController@index'));
    }
 
    try{
        $expense->update([
          'expense_active' => '0',
          'expense_modified_by' => Auth::user()->getAuthIdentifier(),
          'expense_modified_at' => now()->toDateTimeString()
        ]);
        $result['success'] = true;
        $result['successMessages'] = 'Data ' . $expense->Expense_name . ' berhasil dihapus.';
        return response()->json($result);
      } catch (\Exception $e) {
        array_push($result['errorMessages'], $e->getMessage());
        return response()->json($result);
    }
    }

}