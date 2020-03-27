<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //untuk custom created/modified
    public $timestamps = false;

    //Kolom yang bisa diinsert
    protected $fillable = ['customer_name', 'customer_address', 'customer_phone', 'customer_active', 'customer_created_at', 'customer_created_by', 'customer_modified_at', 'customer_modified_by'];

    //Field default
    public static function getFields($model){

        $model->id = null;
        $model->customer_name = null;
        $model->customer_address = null;
        $model->customer_phone = null;
        $model->customer_created_at = null;
        $model->customer_created_by = null;
        $model->customer_modified_at = null;
        $model->customer_modified_by = null;

        return $model;
    }
}
