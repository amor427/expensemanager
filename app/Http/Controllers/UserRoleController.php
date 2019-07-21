<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRole;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = UserRole::all();

        return $role;
    }

   
    public function store(Request $request)
    {
         $this->validate($request,[
            'type' => 'required|string|max:191',
            'description' => 'required|string|max:191'
        ]);

        return UserRole::create([
            'type' => $request['type'],
            'description' => $request['description']
        ]);
    }

    
    public function update(Request $request, $id)
    {
        
        $role = UserRole::findOrFail($id);

        $this->validate($request,[
            'type' => 'required|string|max:191',
            'description' => 'required|string|max:191'
        ]);

        $role->update($request->all());
        return ['message' => 'Updated the user role info'];
    }

  
     public function destroy($id)
    {

        

        $role = UserRole::findOrFail($id);
       
        $role->delete();

        return ['message' => 'User Deleted'];
    }
}
