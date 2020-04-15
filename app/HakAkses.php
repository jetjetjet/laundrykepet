<?php
namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

use DB;

class HakAkses
{
  public static $all = array(
    'karyawan_cari',
    'karyawan_lihat',
    'karyawan_list',
    'karyawan_simpan',
    'karyawan_hapus',
    'peran_list',
    'peran_lihat',
    'peran_simpan',
    'peran_hapus',
    'laporan_lihat',
    'laundry_simpan',
    'laundry_lihat',
    'laundry_cetak',
    'laundry_hapus',
    'laundry_ubahStatus',
    'laundry_antar',
    'laundry_list',
    'laundryKategori_cari',
    'laundryKategori_lihat',
    'laundryKategori_list',
    'laundryKategori_simpan',
    'laundryKategori_hapus',
    'steamKategori_list',
    'steamKategori_lihat',
    'steamKategori_simpan',
    'steamKategori_hapus',
    'steamKategori_cari',
    'pelanggan_list',
    'pelanggan_lihat',
    'pelanggan_simpan',
    'pelanggan_hapus',
    'pelanggan_cari',
    'pengeluaran_list',
    'pengeluaran_lihat',
    'pengeluaran_simpan',
    'pengeluaran_hapus',
    'setting_list',
    'setting_lihat',
    'setting_simpan',
    'setting_hapus',
    'user_list',
    'user_lihat',
    'user_simpan',
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

  public static function can($permissions)
  {
    if (Self::admin()) return true;
    return Auth::check() && Auth::user()->can($permissions,[]);
  }
}