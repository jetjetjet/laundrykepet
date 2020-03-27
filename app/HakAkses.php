<?php
namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

use DB;

class HakAkses
{
    public static $all = array(
        'karyawan_daftar',
        'karyawan_simpan',
        'karyawan_ubah',
        'karyawan_hapus',
        'peran_daftar',
        'peran_simpan',
        'peran_ubah',
        'peran_hapus',
        'LaundryKategori_daftar',
        'LaundryKategori_simpan',
        'LaundryKategori_ubah',
        'LaundryKategori_hapus',
        'pelanggan_daftar',
        'pelanggan_simpan',
        'pelanggan_ubah',
        'pelanggan_hapus',
        'user_daftar',
        'user_simpan',
        'user_ubah',
        'user_hapus',

    );

    public static function all(){
        $result = array();
        foreach (self::$all as $value){
            $values = explode('_', $value);
            if (!isset($result[$values[0]])){
                $result[$values[0]] = new \stdClass();
                $result[$values[0]]->module = $values[0];
                $result[$values[0]]->actions = array();
            }
            
            $action = new \stdClass();
            $action->raw = $value;
            $action->value = $values[1];
            array_push($result[$values[0]]->actions, $action);
        }

        ksort($result);
        return $result;
    }

    public static function full($permissions){
        $maps = array_map(function ($value) use ($permissions){
            return in_array($value, $permissions);
        }, self::$all);
        $full = count(array_keys($maps, true)) === count(self::$all);
        return $full;
    }

    public static function admin()
    {
        if (Auth::user()->getAuthIdentifier() === 1) 
        return true;
    }

    public static function can($permissions){
        
        if (Self::admin()) return true;
        return Auth::check() && Auth::user()->can($permissions,[]);
    }
}