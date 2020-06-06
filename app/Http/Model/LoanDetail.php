<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
  public $timestamps = false;

  protected $fillable = ['loan_detail_loan_id'
  ,'loan_detail_payment'
  ,'loan_detail_installment'
  ,'loan_detail_active'
  ,'loan_detail_created_at'
  ,'loan_detail_created_by'
  ,'loan_detail_modified_at'
  ,'loan_detail_modified_by'];

  public static function getFields($model){
    $model->loan_detail_loan_id = null;
    $model->loan_detail_payment = null;
    $model->loan_detail_installment = null;
    $model->loan_detail_active = null;
    $model->loan_detail_created_at = null;
    $model->loan_detail_created_by = null;
    $model->loan_detail_modified_at = null;
    $model->loan_detail_modified_by = null;
  }
}
