<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class UserStudentController extends Controller
{
    public function index()
    {
        $students = Student::get();
        return response()->view('dashboard.master.student.index',compact('students'));
    }
    public function create()
    {
        $sessions = Session::get();
        return response()->view('dashboard.master.student.create',compact('sessions'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'session_id' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nis' => 'required|numeric',
            'nisn' => 'required|numeric'
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
                $isCreatedStudentData = Student::create([
                    'id' => Str::uuid(),
                    'user_id' => $userId,
                    'session_id' => $request->input('session_id'),
                    'name' => $name,
                    'nis' => $request->input('nis'),
                    'nisn' => $request->input('nisn')
                ]);
                if($isCreatedUserData && $isCreatedStudentData){
                    toastr()->success('student data successfully created');
                    return back();
                }else{
                    toastr()->error('student data failed to create');
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
        $student = Student::whereId($id)->first();
        $sessions = Session::get();
        $isExist = isset($student);
        if($isExist){
            return response()->view('dashboard.master.student.edit',compact('student','sessions'));
        }else{
            toastr()->error('student data not found');
            return back();
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'session_id' => 'required|numeric',
            'name' => 'required|string',
            'nis' => 'required|numeric',
            'nisn' => 'required|numeric'
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->messages() as $errors => $messages) {
                foreach($messages as $message){
                    toastr()->warning($message);
                }
            }
            return back()->withInput();
        }else{
            $student = Student::whereId($id)->first();
            $isExist = isset($student);
            if($isExist){
                $isUpdated = $student->update([
                    'session_id' => $request->input('session_id'),
                    'name' => $request->input('name'),
                    'nis' => $request->input('nis'),
                    'nisn' => $request->input('nisn')
                ]);
                if($isUpdated){
                    toastr()->success('student data successfully updated');
                    return back();
                }else{
                    toastr()->error('student data failed to update');
                    return back()->withInput();
                }
            }else{
                toastr()->error('student data not found');
                return redirect(route('indexmasterstudent'));
            }
        }
    }
    public function destroy($id)
    {
        $student = Student::whereId($id)->first();
        $isExist = isset($student);
        if($isExist){
            $isDeleted = $student->delete();
            if($isDeleted){
                toastr()->success('student data successfully deleted');
                return back();
            }else{
                toastr()->error('student data failed to delete');
                return back()->withInput();
            }
        }else{
            toastr()->error('student data not found');
            return redirect(route('indexmasterstudent'));
        }
    }
}
