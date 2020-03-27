<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Role;
use App\User;
use App\Http\Model\UserRole;
use Validator;
use Auth;
use DB;
use Exception;

class RoleController extends Controller
{
  public function index()
  {
    return view('Role.index');
  }

  public function getRoleLists(Request $request)
  {
    $data = Role::join('users as cr', 'role_created_by', 'cr.id')
      ->leftJoin('users as mod', 'role_modified_by', 'mod.id')
      ->where('role_active', '1')
      ->select('roles.id as id', 'role_name', 'role_detail', 'cr.user_name as role_created_by',
        'role_created_at', 'mod.user_name as role_modified_by', 'role_modified_at');
    
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
    $data = Role::getFields($data);
    $user = User::where('user_active', '1')->get();

    if($id != null){
      $data = Role::join('users as cr', 'role_created_by', 'cr.id')
        ->leftJoin('users as mod', 'role_modified_by', 'mod.id')
        ->where('role_active', '1')
        ->where('roles.id', $id)
        ->select('roles.id as id', 'role_name', 'role_detail', 'role_permissions', 'cr.user_name as role_created_by',
        'role_created_at', 'mod.user_name as role_modified_by', 'role_modified_at')
        ->first();
      
      //User Role
      $subs = UserRole::where('user_role_active', '1')
        ->where('user_role_role_id', $id)->select('user_role_user_id')->get();
      //push userid -> role->userid
      $is = Array();
      foreach($subs as $sub){
        array_push($is, $sub['user_role_user_id']);
      }
      
      $data->role_permissions = explode(",",$data->role_permissions);
      $data->user_id = $is;
    }

    return view('Role.edit')->with('user', $user)->with('data', $data);
  }

  public function postEdit(Request $request, $id = null){
    $inputs = $request->all();
    $id = $inputs['id'] ?: $id ;
    $inputs['permissions'] = !empty($inputs['permissions']) ? $inputs['permissions'] : array();
    $inputs['perm'] = implode(",", $inputs['permissions']);
    $loginId = Auth::user()->getAuthIdentifier();
    
    $result = array('success' => false, 'errorMessages' => array(), 'debugMessages' => array(), 'successMessages' => array());
    try{
      DB::transaction(function () use (&$result, $id, $inputs, $loginId){
        $valid = self::saveRole($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        if($id != null){
          $valid = self::removeMissingUserRole($result, $id, $inputs, $loginId);
        }

        $valid = self::saveUserRole($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        $result['success'] = true;
      });
    } catch (Exception $e){
      dd('Err', $e);
    }
    
    return redirect(action('RoleController@getEdit', array('id' => $result['role_id'])));
  }

  public function removeMissingUserRole(&$result, $id, $inputs, $loginId)
  {
    try{
      $data = UserRole::where('user_role_active', '1')
        ->where('user_role_role_id', $id)
        ->whereNotIn('user_role_user_id', $inputs['user_id'])
        ->update([
          'user_role_active' => '0',
          'user_role_modified_by' => $loginId,
          'user_role_modified_at' => now()->toDateTimeString()
          ]);
    } catch(Exception $e){
      dd('Err', $e);
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
    }
    return true;
  }

  public function saveUserRole(&$result, $id, $inputs, $loginId)
  {
    $id_users = $inputs['user_id'];
    $nId = $id ?: $result['role_id'];
    foreach($id_users as $id_user){
      $userRole = UserRole::where('user_role_role_id', $nId)
        ->where('user_role_user_id', $id_user)
        ->where('user_role_active', '1')
        ->first();

      if($userRole == null){
        UserRole::create([
          'user_role_role_id' => $nId,
          'user_role_user_id' => $id_user,
          'user_role_active' => '1',
          'user_role_created_by' => $loginId,
          'user_role_created_at' =>now()->toDateTimeString()
        ]);
      }
    }
    return true;
  }

  public function saveRole(&$result, $id, $inputs, $loginId){
    $role = null;
    try{
      if($id == null){
        $role = Role::Create([
          'role_name' => $inputs['role_name'],
          'role_detail' => isset($inputs['role_detail']) ? $inputs['role_detail'] :null,
          'role_permissions' => $inputs['perm'],
          'role_active' => '1',
          'role_created_by' => $loginId,
          'role_created_at' =>now()->toDateTimeString()
        ]);
      } else {
        $role = Role::where('role_active', '1')->where('id', $id)->firstOrFail();
        $role->update([
          'role_name' => $inputs['role_name'],
          'role_detail' => isset($inputs['role_detail']) ? $inputs['role_detail'] :null,
          'role_permissions' => $inputs['perm'],
          'role_modified_by' => $loginId,
          'role_modified_at' => now()->toDateTimeString()
        ]);
      }
    } catch (Exception $e) {
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
    }
    $result['role_id'] = $role->id ?: $id;
    return true;
  }

}
