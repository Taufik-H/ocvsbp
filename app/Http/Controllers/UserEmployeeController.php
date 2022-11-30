<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class UserEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::get();
        return response()->view('dashboard.master.employee.index',compact('employees'));
    }
    public function create()
    {
        $sessions = Session::get();
        return response()->view('dashboard.master.employee.create',compact('sessions'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'session_id' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nip' => 'required|numeric',
            'division' => 'required'
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->messages() as $errors => $messages) {
                foreach($messages as $message){
                    toastr()->warning($message);
                }
            }
            return back()->withInput();
        }else{
            $userId = Str::uuid();
            $name = $request->input('name');
            try {
                $isCreatedUserData = User::create([
                    'id' => $userId,
                    'name' => $name,
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'is_voted' => 'false'
                ]);
                $isCreatedEmployeeData = Employee::create([
                    'id' => Str::uuid(),
                    'user_id' => $userId,
                    'session_id' => $request->input('session_id'),
                    'name' => $name,
                    'nip' => $request->input('nip'),
                    'division' => $request->input('division')
                ]);
                if($isCreatedUserData && $isCreatedEmployeeData){
                    toastr()->success('employee data successfully created');
                    return back();
                }else{
                    toastr()->error('employee data failed to create');
                    return back()->withInput();;
                }
            } catch (Throwable $throw) {
                toastr()->error($throw);
                return back()->withInput();;
            }

        }
    }
    public function edit($id)
    {
        $employee = Employee::whereId($id)->first();
        $sessions = Session::get();
        $isExist = isset($employee);
        if($isExist){
            return response()->view('dashboard.master.employee.edit',compact('employee','sessions'));
        }else{
            toastr()->error('employee data not found');
            return back();
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'session_id' => 'required|numeric',
            'name' => 'required|string',
            'nip' => 'required|numeric',
            'division' => 'required'
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->messages() as $errors => $messages) {
                foreach($messages as $message){
                    toastr()->warning($message);
                }
            }
            return back()->withInput();
        }else{
            $employee = Employee::whereId($id)->first();
            $isExist = isset($employee);
            if($isExist){
                $isUpdated = $employee->update([
                    'session_id' => $request->input('session_id'),
                    'name' => $request->input('name'),
                    'nip' => $request->input('nip'),
                    'division' => $request->input('division')
                ]);
                if($isUpdated){
                    toastr()->success('employee data successfully updated');
                    return back();
                }else{
                    toastr()->error('employee data failed to update');
                    return back()->withInput();
                }
            }else{
                toastr()->error('employee data not found');
                return redirect(route('indexmasteremployee'));
            }
        }
    }
    public function destroy($id)
    {
        $employee = Employee::whereId($id)->first();
        $isExist = isset($employee);
        if($isExist){
            $isDeleted = $employee->delete();
            if($isDeleted){
                toastr()->success('employee data successfully deleted');
                return back();
            }else{
                toastr()->error('employee data failed to delete');
                return back()->withInput();
            }
        }else{
            toastr()->error('employee data not found');
            return redirect(route('indexmasteremployee'));
        }
    }
}
