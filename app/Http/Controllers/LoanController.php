<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Loan;
use App\Http\Model\Employee;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class LoanController extends Controller
{
    public function index()
    {
        return view('Loan.index');
    }

    public function getLoanLists(Request $request){
        $data = Loan::join('users as cr', 'loan_created_by', 'cr.id')
        ->leftJoin('users as mod', 'loan_modified_by', 'mod.id')
        ->leftJoin('employees as em', 'loan_employee_id', 'em.id')
        ->where('loan_active', '1')
        ->select('loans.id as id'
        ,'em.employee_name as loan_name'
        ,'loan_detail'
        ,'loan_amount'
        ,'loan_tenor'
        ,'loan_paidoff'
        ,'cr.user_name as loan_created_by'
        ,'loan_created_at'
        ,'mod.user_name as loan_modified_by'
        ,'loan_modified_at');
        
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
        $data = Loan::getFields($data);
        $employee = Employee::where('employee_active', '1')->get();
    
        if($id){
            $data = Loan::join('users as cr', 'loan_created_by', 'cr.id')
            ->leftJoin('users as mod', 'loan_modified_by', 'mod.id')
            ->leftJoin('employees as em', 'loan_employee_id', 'em.id')
            ->where('loan_active', '1')
            ->where('loans.id', $id)
            ->select('loans.id as id'
            ,'em.id as loan_employee_id'
            ,'loan_detail'
            ,'loan_amount'
            ,'loan_tenor'
            ,'loan_paidoff'
            ,'cr.user_name as loan_created_by'
            ,'loan_created_at'
            ,'mod.user_name as loan_modified_by'
            ,'loan_modified_at')
        ->first();
        if($data == null){
            return view('Loan.index')->with(['error' => 'Data tidak Ditemukan']);
            }
        }
        
        return view('Loan.edit')->with('employee', $employee)->with('data', $data);
    }
    
    public function postDelete(Request $request, $id = null){
        $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array());
        try{
            $ln = Loan::where('id',$id)->where('loan_active', '1')->firstOrFail();
            $ln->update([
            'loan_active' => '0',
            'loan_modified_by' => Auth::user()->getAuthIdentifier(),
            'loan_modified_at' => now()->toDateTimeString()
            ]);
    
        $result['success'] = true;
        $result['successMessages'] = 'Data ' . $ln->loan_name . ' berhasil dihapus.';
            return response()->json($result);
        } catch (\Exception $e) {
        $result['errorMessages'] = $e->getMessage();
            return response()->json($result);
        }
    }
    
    public function postEdit(Request $request, $id = null){
        $inputs = $request->all();
        $id_users = $inputs['loan_employee_id'];
        $rules = array(
            'loan_amount' => 'required',
            'loan_tenor' => 'required',
            'loan_paidoff' => 'required'
        );
    
        
        $validator = Validator::make($inputs, $rules);
    
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($inputs);
        }
        try{
            $ln = null;
            if(!$request->id) {
            $ln = Loan::create([
                'loan_employee_id' => $request->loan_employee_id,
                'loan_detail' => $request->loan_detail,
                'loan_amount' => $request->loan_amount,
                'loan_tenor' => $request->loan_tenor,
                'loan_paidoff' => $request->loan_paidoff,
                'loan_active' => '1',
                'loan_created_by' => Auth::user()->getAuthIdentifier(),
                'loan_created_at' => now()->toDateTimeString()
            ]);
            
            if(isset($request->modal)){
                $resp = Array(
                "success" => true,
                "messages" => 'Data ' . $ln->loan_name . ' berhasil ditambah.'
                );
                return response()->json($resp);
            } else {
            $request->session()->flash('successMessages', 'Data ' . $ln->loan_employee_name . ' berhasil ditambah.');
                return redirect(action('LoanController@getEdit', array('id' => $ln->id)));
            }
            } else {
            $ln = Loan::where('id',$request->id)->where('loan_active', '1')->firstOrFail();
    
            $ln->update([
                'loan_employee_id' => $request->loan_employee_id,
                'loan_detail' => $request->loan_detail,
                'loan_amount' => $request->loan_amount,
                'loan_tenor' => $request->loan_tenor,
                'loan_paidoff' => $request->loan_paidoff,
                'loan_modified_by' => Auth::user()->getAuthIdentifier(),
                'loan_modified_at' => now()->toDateTimeString()
            ]);
    
            $request->session()->flash('successMessages', 'Data ' . $ln->loan_employee_name . ' berhasil diubah.');
    
            return redirect(action('LoanController@getEdit', array('id' => $ln->id)));
            }
        }catch(\Exception $e){
            dd($e);
            return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
        }
    }
    
    public function searchLoan(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $data = Loan::
                whereRaw('UPPER(loan_name) LIKE UPPER(\'%'. $cari .'%\')')
                ->where('loan_active', '1')
                ->select('id', 'loan_name')
                ->get();
            return response()->json($data);
        }
    }
}
