<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    protected $fillable = ['employee_name', 'employee_user_id' ,'employee_address', 'employee_sallary', 'employee_type', 'employee_contact', 'employee_active', 'employee_created_at', 'employee_created_by', 'employee_modified_at', 'employee_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->employee_user_id = null;
        $model->employee_name = null;
        $model->employee_address = null;
        $model->employee_contact = null;
        $model->employee_sallary = null;
        $model->employee_type = null;
        $model->employee_created_at = null;
        $model->employee_created_by = null;
        $model->employee_modified_at = null;
        $model->employee_modified_by = null;

        return $model;
    }

    public function scopeLaundryEmployee($query)
    {
        return $query->where('employee_active', '1')
            ->where('employee_type', 'Laundry');
    }
}
