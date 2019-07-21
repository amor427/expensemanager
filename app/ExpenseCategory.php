<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'expense_category';

    protected $fillable = [
        'name', 'description'
    ];

    

    public function expense()
    {
    	return $this->hasMany(Expense::class);
    }
}
