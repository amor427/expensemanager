<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
   protected $table = 'expense';

    protected $fillable = [
        'expense_category_id','amount', 'entry_date','user_id'
    ];

    /*public function category()
    {
    	
    	return $this->belongsTo(ExpenseCategory::class);
    }*/



}
