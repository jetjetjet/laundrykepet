<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
   // use Notifiable, HasRoles;

    public $timestamps = false;

    protected $fillable = [
        'user_name'
        ,'user_password'
        ,'user_full_name'
        ,'user_password'
        ,'user_phone'
        ,'user_address'
        ,'user_active'
        ,'user_created_at'
        ,'user_created_by'
        ,'user_modified_at'
        ,'user_modified_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function getAuthIdentifier()
    {       
        return $this->attributes['id'];
    }

    public function getUserName()
    {       
        return $this->attributes['user_name'];
    }

    public function getFullName()
    {       
        return $this->attributes['user_full_name'];
    }

    public function permissions()
    {       
        return $this->attributes['permissions'];
    }

    public function getAuthPassword()
    {
        return $this->attributes['user_password'];
    }

    public function getUserAttributes()
    {
        return $this->attributes;
    }
    public static function getFields($model){

        $model->id = null;
        $model->user_name = null;
        $model->user_password = null;
        $model->user_full_name = null;
        $model->user_address = null;
        $model->user_phone = null;
        $model->user_created_at = null;
        $model->user_created_by = null;
        $model->user_modified_at = null;
        $model->user_modified_by = null;

        return $model;
    }
    public function getField($field)
    {
        return !empty($this->attributes[$field]) ? $this->attributes[$field] : null; 
    }

    public function can($actions, $args = array())
    {
        $valids = array_unique(array_map(function ($action){
            return in_array($action, $this->attributes['permissions'], true);
        }, $actions));

        return !in_array(false, $valids, true);
    }
}
