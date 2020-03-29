<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
  public function index()
  {
    return view('User.index');
  }

  public function getUserLists(Request $request){
    $data = user::join('users as cr', 'users.user_created_by', 'cr.id')
      ->leftJoin('users as mod', 'users.user_modified_by', 'mod.id')
      ->where('users.user_active', '1')
      ->select('users.id as id', 'users.user_name', 'users.user_phone', 'users.user_address', 'cr.user_name as user_cr',
        'users.user_created_at', 'mod.user_name as user_mod', 'users.user_modified_at');
    
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
    $data = User::getFields($data);

    if($id){
      $data = User::join('users as cr', 'users.user_created_by', 'cr.id')
      ->leftJoin('users as mod', 'users.user_modified_by', 'mod.id')
      ->where('users.user_active', '1')
      ->where('users.id', $id)
      ->select('users.id as id', 'users.user_name', 'users.user_full_name', 'users.user_phone', 'users.user_address', 'cr.user_name as user_created_by',
          'users.user_created_at', 'mod.user_name as user_modified_by', 'users.user_modified_at')
      ->first();
      if($data == null){
        return view('User.index')->with(['errorMessages' => 'Data tidak Ditemukan']);
      }
    }
    
    return view('User.edit')->with('data', $data);
  }

  public function postEdit(Request $request){
    $rules = array(
      'user_name' => 'required',
    );

    $inputs = $request->all();
    $validator = Validator::make($inputs, $rules);

    if ($validator->fails()){
      return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    
    try{
      $user = null;
      if(!$request->id) {
        $user = User::create([
          'user_name' => $request->user_name,
          'user_address' => $request->user_address,
          'user_phone' => $request->user_phone,
          'user_full_name' => $request->user_full_name,
          'user_password' => Hash::make($request->user_password),
          'user_active' => '1',
          'user_created_by' => Auth::user()->getAuthIdentifier(),
          'user_created_at' => now()->toDateTimeString()
        ]);

        $request->session()->flash('successMessages', 'Data ' . $user->user_name . ' berhasil ditambah.');
      } else {
        $user = User::where('user_active', '1')->where('id', $request->id)->firstOrFail();

        $user->update([
          'user_name' => $request->user_name,
          'user_address' => $request->user_address,
          'user_phone' => $request->user_phone,
          'user_full_name' => $request->user_full_name,
          'user_modified_by' => Auth::user()->getAuthIdentifier(),
          'user_modified_at' => now()->toDateTimeString()
        ]);
        
        $request->session()->flash('successMessages', 'Data ' . $user->user_name . ' berhasil diubah.');
      }
      return redirect(action('UserController@getEdit', array('id' => $user->id)));
    } catch(\Exception $e) {
      return redirect()->back()->with(['errorMessages' => $e->getMessage()]);
    }
  }

  public function postDelete (Request $request, $id = null){
    $user = User::where('user_active', '1')->where('id', $id)->firstOrFail();

    if($user == null){
      array_push($result['errorMessages'], 'Data tidak ditemukan.');
      return response()->json($result);
    }

    try{
      $user->update([
        'user_active' => '0',
        'user_modified_by' => Auth::user()->getAuthIdentifier(),
        'user_modified_at' => now()->toDateTimeString()
      ]);

      $result['success'] = true;
      $result['successMessages'] = 'Data ' . $user->user_name . ' berhasil dihapus.';
      return response()->json($result);
    } catch (\Exception $e){
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

  public function postChangePassword (Request $request, $id = null){
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array());
    $user = User::where('user_active', '1')->where('id', $id)->firstOrFail();

    if($user == null){
      array_push($result['errorMessages'], 'Data tidak ditemukan.');
      return response()->json($result);
    }
    
    try{
      $user->update([
        'user_password' => Hash::make($request->user_password),
        'user_modified_by' => Auth::user()->getAuthIdentifier(),
        'user_modified_at' => now()->toDateTimeString()
      ]);

      $result['success'] = true;
      $result['successMessages'] = 'Password ' . $user->user_name . ' berhasil diubah.';
      return response()->json($result);
    } catch (\Exception $e){
      array_push($result['errorMessages'], $e->getMessage());
      return response()->json($result);
    }
  }

}
