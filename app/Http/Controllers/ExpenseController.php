<?php

namespace App\Http\Controllers;
use App\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
class ExpenseController extends Controller
{
  
    
    public function index()
    {
         $user = auth('api')->user();
         if ($user['type'] == 'admin'){
             $condition = "";
         }
         else{
            $condition = "where ex.user_id = ". $user->id;
            
         }
            
        $expense = DB::select('select ex.id,ex.user_id,ex.amount,ex.entry_date,ex.created_at,ex.expense_category_id,cat.name from expense as ex
            INNER JOIN expense_category as cat
            ON cat.id = ex.expense_category_id' .' ' .$condition
             );
                            
            return   $expense;
    }


    public function store(Request $request)
    {
         $user = auth('api')->user();
         $dateinit = \Carbon\Carbon::parse($request['entry_date']);

         $this->validate($request,[
            'expense_category_id' => 'required|max:191',
            'entry_date' => 'required',
            'amount' => 'required|max:191'
        ]);
       

        return Expense::create([
            'expense_category_id' => $request['expense_category_id'],    
            'entry_date' => $dateinit->format('y-m-d H:i:s'),
            'amount' => $request['amount'],
            'user_id' =>$user->id
        ]);
    }

    public function update(Request $request, $id)
    {
        $dateinit = \Carbon\Carbon::parse($request['entry_date']); 
        $role = Expense::findOrFail($id);

        $this->validate($request,[
            'expense_category_id' => 'required|max:191',
            'entry_date' => 'required|max:191',
            'amount' => 'required|max:191'
        ]);

        $role->update($request->all());
        return ['message' => 'Updated the user role info'];
    }

   
    public function destroy($id)
    {
         
        $exp = Expense::findOrFail($id);
      

        $exp->delete();

        return ['message' => 'Expense Deleted'];
    }
}
