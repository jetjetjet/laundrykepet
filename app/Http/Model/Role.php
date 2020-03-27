<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'role_name'
        ,'role_detail'
        ,'role_active'
        ,'role_permissions'
        ,'role_created_at'
        ,'role_created_by'
        ,'role_modified_at'
        ,'role_modified_by'
    ];

    public static function getFields($model){

        $model->id = null;
        $model->role_name = null;
        $model->role_detail = null;
        $model->role_permissions = null;
        $model->user_id = [];
        $model->role_created_at = null;
        $model->role_created_by = null;
        $model->role_modified_at = null;
        $model->role_modified_by = null;

        return $model;
    }
}
