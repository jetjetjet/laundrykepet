<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Model\SCategory;
use Validator;

class SCategoryController extends Controller
{
    public function index()
  {
    return view('SCategory.index');
  }

  public function getDropDownList(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = SCategory::
        whereRaw('UPPER(scategory_name) LIKE UPPER(\'%'.$cari.'%\')')
        ->where('scategory_active', '1')
        ->select('id', 'scategory_name', 'scategory_price')
        ->get();
      return response()->json($data);
    }
  }

  public function getGrid(Request $request)
  {
      $data=SCategory::join('users as cr','scategory_created_by','cr.id')
        ->leftJoin('users as mod','scategory_modified_by','mod.id')
        ->where('scategory_active','1')
         ->select('scategories.id as id', 
             'scategory_detail',
             'scategory_name',
             'scategory_type',
             'scategory_price',
             'scategory_created_at',
             'cr.user_name as scategory_created_by',
             'scategory_modified_at',
             'mod.user_name as scategory_modified_by' );

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
    $data = SCategory::getFields($data);

    if($id) 
    {
        $data=SCategory::join('users as cr','scategory_created_by','cr.id')
        ->leftJoin('users as mod','scategory_modified_by','mod.id')
        ->where('scategory_active','1')
        ->where('scategories.id', $id)
        ->select('scategories.id as id'
        ,'scategory_name'
        ,'scategory_detail'
        ,'scategory_type'
        ,'scategory_price'
        ,'scategory_created_at'
        ,'cr.user_name as scategory_created_by'
        ,'scategory_modified_at'
        ,'mod.user_name as scategory_modified_by')->first();
        if( $data == null)
        {
            return view('SCategory.index')->with(['errorMessages'=>'Data Tidak Ditemukan']);
        }
    }
    return view('SCategory.edit')->with('data',$data);
  }

  public function postEdit(Request $request, $id = null)
  {
    $rules = array(
        'scategory_name' => 'required',
        'scategory_type' => 'required'
    );

    $inputs = $request->all();
    $validator = validator::make($inputs, $rules);

    if ($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
     try{
        $category = null;
        if (!$request->id){
            $category = SCategory::create([
                'scategory_name' => $request->scategory_name,
                'scategory_detail' => $request->scategory_detail,
                'scategory_type' => $request->scategory_type,
                'scategory_price' => $request->scategory_price,
                'scategory_active' => '1',
                'scategory_created_by' => Auth::user()->getAuthIdentifier(),
                'scategory_created_at' => now()->toDateTimeString()
            ]);

            $request->session()->flash('successMessages', 'Data ' . $category->scategory_name . ' berhasil ditambah.');


        }
        else{
            $category = SCategory::where('scategory_active', '1')->where('id', $request->id)->firstOrFail();

            $category->update([
                'scategory_name' => $request->scategory_name,
                'scategory_detail' => $request->scategory_detail,
                'scategory_type' => $request->scategory_type,
                'scategory_price' => $request->scategory_price,
                'scategory_modified_by' => Auth::user()->getAuthIdentifier(),
                'scategory_modified_at' => now()->toDateTimeString()
              ]);
              $request->session()->flash('successMessages', 'Data ' . $category->scategory_name . ' berhasil diubah.');
            }
            return redirect(action('SCategoryController@getEdit', array('id' => $category->id)));
     } catch(\Excaption $e){
        return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
     }
  }

  public function postDelete(Request $request, $id = null)
  {
    $rules = array('success'=> false,'errorMessages' =>array(), 'debugMassages' =>array());
    $category = SCategory::where('scategory_active', '1')->where('id', $id)->first();

    if($category == null){
      $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
      return redirect(action('SCategoryController@index'));
    }

    try{
      $category->update([
        'scategory_active' => '0',
        'scategory_modified_by' => Auth::user()->getAuthIdentifier(),
        'scategory_modified_at' => now()->toDateTimeString()
      ]);
      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $category->Scategory_name . ' berhasil dihapus.';
      return response()->json($result);
    } catch (\Exception $e){
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }
}
