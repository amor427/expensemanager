<?php

namespace App\Http\Controllers;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $category = ExpenseCategory::select('*')->get();

        return  $category;


    }
    
    public function store(Request $request)
    {
         $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:191'
        ]);

        return ExpenseCategory::create([
            'name' => $request['name'],
            'description' => $request['description']
        ]);
    }

  
    public function update(Request $request, $id)
    {
        
        $role = ExpenseCategory::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:191'
        ]);

        $role->update($request->all());
        return ['message' => 'Updated Category info'];
    }

    public function destroy($id)
    {
       $exp = ExpenseCategory::findOrFail($id);
       

        $exp->delete();

        return ['message' => 'category Deleted'];

      
    }
}
