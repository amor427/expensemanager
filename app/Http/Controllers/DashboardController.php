<?php

namespace App\Http\Controllers;
use App\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
            $user = auth('api')->user();
         if ($user['type'] == 'admin'){
             $condition = "";
         }
         else{
            $condition = "where expense.user_id = ". $user->id;
            
         }

      		$data=[];

          $expense = DB::select("SELECT expense_category.name,SUM(expense.amount) AS total_amount
            FROM expense
            INNER JOIN expense_category 
            ON expense.expense_category_id = expense_category.id" .' ' .$condition .' '.
            "GROUP BY(expense.expense_category_id)"
          );
          $data[] = $expense;
    	

             
            foreach ($expense as $e) {
            	$data['label'][] = $e->name;
            	$data['datasets'][] = (int)$e->total_amount;
            	
            }
    
       
       return $data;

    }

    
    
}
