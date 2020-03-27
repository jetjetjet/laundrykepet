<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Model\LCategory;
use Validator;


class LCategoryController extends Controller
{
  public function index()
  {
    return view('LCategory.index');
  }

  public function getDropDownList(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = LCategory::
        where('lcategory_name', 'LIKE', '%'.$cari.'%')
        ->where('lcategory_active', '1')
        ->select('id', 'lcategory_name', 'lcategory_price')
        ->get();
      return response()->json($data);
    }
  }

  public function getGrid(Request $request)
  {
    $data = LCategory::join('users as cr', 'lcategory_created_by', 'cr.id')
      ->leftJoin('users as mod', 'lcategory_modified_by', 'mod.id')
      ->where('lcategory_active', '1')
      ->select('lcategories.id as id',
        'lcategory_name',
        'lcategory_detail',
        'lcategory_price', 
        'cr.user_name as lcategory_created_by',
        'lcategory_created_at', 
        'mod.user_name as lcategory_modified_by',
        'lcategory_modified_at');
      
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
    $data = LCategory::getFields($data);

    if ($id){
      $data = LCategory::join('users as cr', 'lcategory_created_by', 'cr.id')
      ->leftJoin('users as mod', 'lcategory_modified_by', 'mod.id')
      ->where('lcategory_active', '1')
      ->where('lcategories.id', $id)
      ->select('lcategories.id as id', 
        'lcategory_name', 
        'lcategory_detail',
        'lcategory_days',
        'lcategory_price',
        'cr.user_name as lcategory_created_by',
        'lcategory_created_at', 
        'mod.user_name as lcategory_modified_by', 
        'lcategory_modified_at')
      ->first();
      if($data == null){
        return view('LCategory.index')->with(['errorMessages' => 'Data tidak Ditemukan']);
      }
    }
    return view('LCategory.edit')->with('data', $data);
  }

  public function postDelete(Request $request, $id = null)
  {
    $id = $request->input('id');
    $category = LCategory::where('lcategory_active', '1')->where('id', $id)->first();

    if($category == null){
      $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
      return redirect(action('LCategoryController@index'));
    }

    $category->update([
      'lcategory_active' => '0',
      'lcategory_modified_by' => Auth::user()->getAuthIdentifier(),
      'lcategory_modified_at' => now()->toDateTimeString()
    ]);

    $request->session()->flash('successMessages', 'Data ' . $category->lcategory_name . ' berhasil dihapus.');
    return redirect(action('LCategoryController@index'));
  }

  public function postEdit(Request $request, $id = null){
    $rules = array(
      'lcategory_name' => 'required',
      'lcategory_price' => 'required'
    );

    $inputs = $request->all();
    $validator = Validator::make($inputs, $rules);

    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    
    try{
      $category = null;
      if(!$request->id) {
        $category = LCategory::create([
          'lcategory_name' => $request->lcategory_name,
          'lcategory_detail' => $request->lcategory_detail,
          'lcategory_days' => $request->lcategory_days,
          'lcategory_price' => $request->lcategory_price,
          'lcategory_active' => '1',
          'lcategory_created_by' => Auth::user()->getAuthIdentifier(),
          'lcategory_created_at' => now()->toDateTimeString()
        ]);

        $request->session()->flash('successMessages', 'Data ' . $category->lcategory_name . ' berhasil ditambah.');
      } else {
        $category = LCategory::where('lcategory_active', '1')->where('id', $request->id)->firstOrFail();

        $category->update([
          'lcategory_name' => $request->lcategory_name,
          'lcategory_detail' => $request->lcategory_detail,
          'lcategory_days' => $request->lcategory_days,
          'lcategory_price' => $request->lcategory_price,
          'lcategory_modified_by' => Auth::user()->getAuthIdentifier(),
          'lcategory_modified_at' => now()->toDateTimeString()
        ]);
        
        $request->session()->flash('successMessages', 'Data ' . $category->lcategory_name . ' berhasil diubah.');
      }
      return redirect(action('LCategoryController@getEdit', array('id' => $category->id)));
    } catch(\Exception $e) {
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }
}
