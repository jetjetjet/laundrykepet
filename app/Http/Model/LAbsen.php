<?php

namespace App\Http\Model;
use DB;
use App\Http\Model\DAbsen;
use Illuminate\Database\Eloquent\Model;

class LAbsen extends Model
{
  protected $table = 'labsen';
  public $timestamps = false;
  protected $fillable = [
    'labsen_detail'
    ,'labsen_active'
    ,'labsen_created_at'
    ,'labsen_created_by'
    ,'labsen_modified_at'
    ,'labsen_modified_by'];

  public function scopeGetList($query)
  {
    return $query->join('users as cr', 'cr.id', 'labsen_created_by')
      ->leftJoin('users as md', 'md.id', 'labsen_modified_by')
      ->where('labsen_active', '1')
      //->groupBy(DB::raw('labsen_created_at::date')) 
      ->orderBy('labsen_created_at', 'DESC')
      ->select('labsen.id'
        ,'labsen_detail'
        ,'labsen_created_at'
        ,'cr.user_name as labsen_created_by'
        ,'labsen_modified_at'
        ,'md.user_name as labsen_modified_by'
      );
  }
}
