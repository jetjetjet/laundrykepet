<?php

namespace App\Http\Controllers;

use App\Http\Model\Lctype;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LctypeController extends Controller
{
    public function index()
    {
        return view('Lctype.index');
    }

    public function getGrid(Request $request)
    {
        $data=Lctype::join('users as cr','lctype_created_by','cr.id')
        ->leftJoin('users as mod','lctype_modified_by','mod.id')
        ->where('lctype_active','1')
        ->select('lctype.id as id', 
        'lctype_name',
        'lctype_active',
        'lctype_created_at',
        'cr.user_name as lctype_created_by',
        'lctype_modified_at',
        'mod.user_name as lctype_modified_by' );

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
        $data = lctype::getFields($data);
   
        if($id)
        {
          $data=Lctype::join('users as cr','lctype_created_by','cr.id')
            ->leftJoin('users as mod','lctype_modified_by','mod.id')
            ->where('lctype_active','1')
            ->where('lctype.id', $id)
            ->select('lctype.id as id'
            ,'lctype_name'
            ,'lctype_active'
            ,'lctype_created_at'
            ,'cr.user_name as lctype_created_by'
            ,'lctype_modified_at'
            ,'mod.user_name as lctype_modified_by')->first();
          if( $data == null)
          {
            return view('lctype.index')->with(['errorMessages'=>'Data Tidak Ditemukan']);
          }
        }
      return view('lctype.edit')->with('data',$data);
    }

    public function postEdit(Request $request, $id = null)
    {
        $rules = array(
            'lctype_name' => 'required'
          );
       
          $inputs = $request->all();
          $validator = validator::make($inputs, $rules);
       
          if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($inputs);
          }
          try {
            $lctype = null;
            if (!isset($request->id)){
              $lctype = Lctype::create([
                'lctype_name' => $request->lctype_name,
                'lctype_active' => '1',
                'lctype_created_by' => Auth::user()->getAuthIdentifier(),
                'lctype_created_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $lctype->lctype_name . ' berhasil ditambah.');
            } else {
              $lctype = lctype::where('lctype_active', '1')->where('id', $request->id)->firstOrFail();
              $lctype->update([
                'lctype_name' => $request->lctype_name,
                'lctype_modified_by' => Auth::user()->getAuthIdentifier(),
                'lctype_modified_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $lctype->lctype_name . ' berhasil diubah.');
      
            }
            return redirect(action('lctypeController@getEdit', array('id' => $lctype->id)));
          } catch(\Excaption $e){
            return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
          }
    }

    public function postDelete(Request $request, $id = null)
    {
        $rules = array('success'=> false,'errorMessages' =>array(), 'debugMassages' =>array());
        $lctype = Lctype::where('lctype_active', '1')->where('id', $id)->first();
 
        if($lctype == null){
        $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
        return redirect(action('lctypeController@index'));
        }
 
      try{
        $lctype->update([
          'lctype_active' => '0',
          'lctype_modified_by' => Auth::user()->getAuthIdentifier(),
          'lctype_modified_at' => now()->toDateTimeString()
        ]);
          $result['success'] = true;
          $result['successMessages'] = 'Data ' . $lctype->lctype_name . ' berhasil dihapus.';
          return response()->json($result);
      } catch (\Exception $e) {
        array_push($result['errorMessages'], $e->getMessage());
        return response()->json($result);
        }
    }
}
