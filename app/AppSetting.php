<?php
namespace App;
use Illuminate\Support\Facades\Auth;

use App\Http\Model\Setting;

class AppSetting
{
  public static function getAppName()
  {
    return Setting::where('setting_key', 'Nama Toko')->select('setting_value')->first();
  }

  public static function getLogo()
  {
    $filename = Setting::where('setting_category', 'Logo')->select('setting_value')->first();
    return $filename['setting_value'];
  }
}