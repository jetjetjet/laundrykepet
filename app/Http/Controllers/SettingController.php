<?php
 
namespace App\Http\Controllers;
 
use App\Http\Model\Setting;
use Auth;
use Illuminate\Http\Request;
use Validator;
 
class SettingController extends Controller
{
  public function index()
  {
    return view('setting.index');
  }
 
  public function getGrid(Request $request)
  {
    $data=Setting::join('users as cr','setting_created_by','cr.id')
    ->leftJoin('users as mod','setting_modified_by','mod.id')
    ->where('setting_active','1')
      ->select('settings.id as id', 
        'setting_category',
        'setting_key',
        'setting_value',
        'setting_active',
        'setting_created_at',
        'cr.user_name as setting_created_by',
        'setting_modified_at',
        'mod.user_name as setting_modified_by' );
 
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
      $data = Setting::getFields($data);
 
      if($id)
      {
        $data=Setting::join('users as cr','setting_created_by','cr.id')
          ->leftJoin('users as mod','setting_modified_by','mod.id')
          ->where('setting_active','1')
          ->where('settings.id', $id)
          ->select('settings.id as id'
          ,'setting_category'
          ,'setting_key'
          ,'setting_value'
          ,'setting_active'
          ,'setting_created_at'
          ,'cr.user_name as setting_created_by'
          ,'setting_modified_at'
          ,'mod.user_name as setting_modified_by')->first();
        if( $data == null)
        {
          return view('Setting.index')->with(['errorMessages'=>'Data Tidak Ditemukan']);
        }
      }
    return view('Setting.edit')->with('data',$data);
  }
 
  public function postEdit(Request $request, $id = null)
  {
    $rules = array(
      'setting_key' => 'required',
      'setting_category' => 'required'
    );
 
    $inputs = $request->all();
    $validator = validator::make($inputs, $rules);
 
    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    try {
      $setting = null;
      if (!isset($request->id)){
        $setting = Setting::create([
          'setting_category' => $request->setting_category,
          'setting_key' => $request->setting_key,
          'setting_value' => $request->setting_value,
          'setting_active' => '1',
          'setting_created_by' => Auth::user()->getAuthIdentifier(),
          'setting_created_at' => now()->toDateTimeString()
        ]);
        $request->session()->flash('successMessages', 'Data ' . $setting->setting_key . ' berhasil ditambah.');
      } else {
        $setting = Setting::where('setting_active', '1')->where('id', $request->id)->firstOrFail();
        $setting->update([
          'setting_category' => $request->setting_category,
          'setting_key' => $request->setting_key,
          'setting_value' => $request->setting_value,
          'setting_modified_by' => Auth::user()->getAuthIdentifier(),
          'setting_modified_at' => now()->toDateTimeString()
        ]);
        $request->session()->flash('successMessages', 'Data ' . $setting->setting_key . ' berhasil diubah.');

      }
      return redirect(action('SettingController@getEdit', array('id' => $setting->id)));
    } catch(\Excaption $e){
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }
 
  public function postDelete(Request $request, $id = null)
  {
    $rules = array('success'=> false,'errorMessages' =>array(), 'debugMassages' =>array());
    $setting = Setting::where('setting_active', '1')->where('id', $id)->first();
 
    if($setting == null){
      $request->session()->flash('errorMessages', 'Data tidak ditemukan.');
      return redirect(action('SettingController@index'));
    }
 
    try{
        $setting->update([
          'setting_active' => '0',
          'setting_modified_by' => Auth::user()->getAuthIdentifier(),
          'setting_modified_at' => now()->toDateTimeString()
        ]);
        $result['success'] = true;
        $result['successMessages'] = 'Data ' . $setting->Setting_key . ' berhasil dihapus.';
        return response()->json($result);
      } catch (\Exception $e) {
        array_push($result['errorMessages'], $e->getMessage());
        return response()->json($result);
      }
  }
}