<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_role_user_id'
        ,'user_role_role_id'
        ,'user_role_active'
        ,'user_role_created_at'
        ,'user_role_created_by'
        ,'user_role_modified_at'
        ,'user_role_modified_by'
    ];
}
