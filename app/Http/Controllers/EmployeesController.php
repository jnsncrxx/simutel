<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeesController extends Controller
{
    public function view_employees(){
        $employees = Employees::all();
        
        return view('admin.employees.employees',compact('employees'));
    }

    public function add_employee(Request $request)
    {
        $user = new User;

        $request->validate([
            'email' => 'unique:users',
            'username' => 'unique:users',
        ]);

        $user->usertype = "employee";
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make(Str::random(10));

        $user->save();

        $employee = new Employees;
        $employee->user_id = $user->id;
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->contact = $request->contact;
        $employee->birthday = $request->birthday;
        $employee->role = $request->role;
        
        $employee->save();

        return redirect()->back()->with('message', 'Employee Added Successfully');
    }

    public function delete_employee($id)
    {
        $employee = Employees::find($id)->user;

        if(Auth::user()->id == $employee->id) {
            Auth::logout();
            
            return redirect('/')->with('message', 'Employee Deleted Successfully');
        }

        $employee->delete();

        return redirect('employees')->with('message', 'Employee Deleted Successfully');
    }

    public function update_employee(Request $request, $id)
    {
        $employee = Employees::find($id);

        $request->validate([
            'edit_email' => 'unique:users,email,'.$employee->user->id,
            'edit_username' => 'unique:users,username,'.$employee->user->id,
        ],
        [
            'edit_email.unique' => $employee->id,
            'edit_username.unique' => $employee->id
        ]);

        $employee->user->email = $request->edit_email;
        $employee->user->username = $request->edit_username;
        $employee->user->save();
        
        $employee->contact = $request->edit_contact;
        $employee->role = $request->edit_role;
        $employee->save();

        return redirect()->back()->with('message', 'Employee Updated Successfully');
    }
}
