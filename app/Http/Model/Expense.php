<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expense';
  public $timestamps = false;
  protected $fillable = ['expense_name'
    ,'expense_detail'
    ,'expense_price'
    ,'expense_active'
    ,'expense_created_at'
    ,'expense_created_by'
    ,'expense_modified_at'
    ,'expense_modified_by'];

    public static function getFields($model){

        $model->id = null;
        $model->expense_name = null;
        $model->expense_detail = null;
        $model->expense_price = null;
        $model->expense_active = null;
        $model->expense_created_at = null;
        $model->expense_created_by = null;
        $model->expense_modified_at = null;
        $model->expense_modified_by = null;
  
        return $model;
    }
}
