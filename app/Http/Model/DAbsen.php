<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class DAbsen extends Model
{
  protected $table = 'dabsen';
  public $timestamps = false;
  protected $fillable = [
    'dabsen_labsen_id'
    ,'dabsen_employee_id'
    ,'dabsen_note'
    ,'dabsen_active'
    ,'dabsen_created_at'
    ,'dabsen_created_by'
    ,'dabsen_modified_at'
    ,'dabsen_modified_by'];
}
