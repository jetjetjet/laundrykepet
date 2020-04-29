<?php

namespace App\Http\Controllers;

use App\Http\Model\Lexpenses;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LexpensesController extends Controller
{
    
    public function index()
    {
        return view('Lexpenses.index');
    }


    public function getGrid(Request $request)
    {
        $data=Lexpenses::join('users as cr','lexpenses_created_by','cr.id')
    ->leftJoin('users as mod','lexpenses_modified_by','mod.id')
    ->where('lexpenses_active','1')
      ->select('lexpenses.id as id', 
        'lexpenses_name',
        'lexpenses_detail',
        'lexpenses_price',
        'lexpenses_active',
        'lexpenses_created_at',
        'cr.user_name as lexpenses_created_by',
        'lexpenses_modified_at',
        'mod.user_name as lexpenses_modified_by' );
 
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
        $data = lexpenses::getFields($data);
   
        if($id)
        {
          $data=Lexpenses::join('users as cr','lexpenses_created_by','cr.id')
            ->leftJoin('users as mod','lexpenses_modified_by','mod.id')
            ->where('lexpenses_active','1')
            ->where('lexpenses.id', $id)
            ->select('lexpenses.id as id'
            ,'lexpenses_name'
            ,'lexpenses_detail'
            ,'lexpenses_price'
            ,'lexpenses_active'
            ,'lexpenses_created_at'
            ,'cr.user_name as lexpenses_created_by'
            ,'lexpenses_modified_at'
            ,'mod.user_name as lexpenses_modified_by')->first();
          if( $data == null)
          {
            return view('lexpenses.index')->with(['errorMessages'=>'Data Tidak Ditemukan']);
          }
        }
      return view('lexpenses.edit')->with('data',$data);
    }

    
    public function postEdit(Request $request, $id = null)
    {
        $rules = array(
            'lexpenses_name' => 'required'
          );
       
          $inputs = $request->all();
          $validator = validator::make($inputs, $rules);
       
          if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($inputs);
          }
          try {
            $lexpenses = null;
            if (!isset($request->id)){
              $lexpenses = Lexpenses::create([
                'lexpenses_name' => $request->lexpenses_name,
                'lexpenses_detail' => $request->lexpenses_detail,
                'lexpenses_price' => $request->lexpenses_price,
                'lexpenses_active' => '1',
                'lexpenses_created_by' => Auth::user()->getAuthIdentifier(),
                'lexpenses_created_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $lexpenses->lexpenses_name . ' berhasil ditambah.');
            } else {
              $lexpenses = lexpenses::where('lexpenses_active', '1')->where('id', $request->id)->firstOrFail();
              $lexpenses->update([
                'lexpenses_name' => $request->lexpenses_name,
                'lexpenses_detail' => $request->lexpenses_detail,
                'lexpenses_price' => $request->lexpenses_price,
                'lexpenses_modified_by' => Auth::user()->getAuthIdentifier(),
                'lexpenses_modified_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $lexpenses->lexpenses_name . ' berhasil diubah.');
      
            }
            return redirect(action('LexpensesController@getEdit', array('id' => $lexpenses->id)));
          } catch(\Excaption $e){
            return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
          }
    }

    
    public function postDelete(Request $request, $id = null)
    {
        $rules = array('success'=> false,'errorMessages' =>array(), 'debugMassages' =>array());
        $lexpenses = Lexpenses::where('lexpenses_active', '1')->where('id', $id)->first();
 
        if($lexpenses == null){
        $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
        return redirect(action('LexpensesController@index'));
        }
 
      try{
        $lexpenses->update([
          'lexpenses_active' => '0',
          'lexpenses_modified_by' => Auth::user()->getAuthIdentifier(),
          'lexpenses_modified_at' => now()->toDateTimeString()
        ]);
          $result['success'] = true;
          $result['successMessages'] = 'Data ' . $lexpenses->Lexpenses_name . ' berhasil dihapus.';
          return response()->json($result);
      } catch (\Exception $e) {
        array_push($result['errorMessages'], $e->getMessage());
        return response()->json($result);
        }
    }


}
