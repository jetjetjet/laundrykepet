<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public $timestamps = false;

    protected $fillable = ['loan_employee_id'
    ,'loan_detail'
    ,'loan_amount'
    ,'loan_tenor'
    ,'loan_paidoff'
    ,'loan_active'
    ,'loan_created_at'
    ,'loan_created_by'
    ,'loan_modified_at'
    ,'loan_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->loan_employee_id = null;
        $model->loan_detail = null;
        $model->loan_amount = null;
        $model->loan_tenor = null;
        $model->loan_paidoff = null;
        $model->loan_active = null;
        $model->employee_id = [];
        $model->loan_created_at = null;
        $model->loan_created_by = null;
        $model->loan_modified_at = null;
        $model->loan_modified_by = null;

        return $model;
    }
}
