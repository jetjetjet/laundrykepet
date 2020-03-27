<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

use Illuminate\Support\Facades\Hash;
use App\User;
use Session;
use App\Can;

use DB;

class CustomUserProvider implements UserProvider{
    public function retrieveById($identifier)
    {
        $user = User:: //select('id', 'user_password as password', 'user_name as username', 'user_full_name as fullname')->
            where('id', $identifier)
            ->where('user_active', '1')
            ->orderBy('user_created_at')->first();

        $user->permissions = self::cek($user->id);
        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
    }
    
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        $username =  $credentials['username'];
        $password =  $credentials['password'];
        $user = User:: //select('id', 'user_password as password', 'user_name as username', 'user_full_name as fullname')->
            where('user_name', $username)
            ->where('user_active', '1')
            ->orderBy('user_created_at')->first();
        if ($user === null || !Hash::check($password, $user['user_password'])) return null;
        
        $user->permissions = self::cek($user->id);
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user === null) return false;
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public static function cek($id)
    {
        $permissions = DB::table('users')
        ->join('user_roles', 'users.id', 'user_role_user_id')
        ->join('roles', 'roles.id','user_role_role_id')
        ->where('user_active', '1')
        ->where('user_role_active', '1')
        ->where('role_active', '1')
        ->where('users.id', $id) //Auth::user()->getAuthIdentifier())
        ->select(DB::raw('distinct (regexp_split_to_table(string_agg(role_permissions, \',\'), \',\')::varchar) as perm'))
        ->get();
        $perm = array();
        foreach($permissions as $permission){
            array_push($perm, $permission->perm);
        }

        return $perm;
    }
}