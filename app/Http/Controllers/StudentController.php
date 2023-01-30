<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    function all_student(Request $request)
    {
        $data = $request->all();
        $student_qry = Student::query()
            ->where("parent_id", "=", $data['user_id']);
        $student_qry->orderBy("id", "DESC");
        $data['students'] = $student_qry->get();
        $data['user_id'] = isset($data['user_id']) ? $data['user_id'] : '';
        return view("student.all_student", $data);
    }

    function add_student()
    {
        $data['title'] = 'Add Student';
        return view('student.add-student', $data);
    }

    function add_student_submitted(Request $request)
    {
     //  echo "heree";exit;
        $data = $request->all();
        try {

            $request->validate([
                'student_name' => 'required',
                'gender' => 'required',
                'fees_amount' => 'required'
            ]);
            //---------------------------------------------------------------------------------------------
            $student = new Student();
            $student->parent_id = $data['user_id'];
            $student->student_name = $data['student_name'];
            $student->gender = $data['gender'];
            $student->fees_amount = $data['fees_amount'];
            $student->status = '1';
            $student->save();

            return redirect(route('all-student',['user_id'=> $data['user_id']]))->with('success', "Student added successfully..!");

        } catch (\Exception $exception) {
            return redirect(route('add-student',['user_id'=> $data['user_id']]))->with('error', $exception->getMessage());
        }
    }

    function edit_student()
    {
        $sid = $_GET['sid'];
        $student = Student::select('*')
            ->where("id", "=", $sid)
            ->first();

        $data['student'] = $student;
        return view('student.edit-student',$data);
    }

    function edit_student_submitted (Request $request)
    {
        $data = $request->all();
        $request->validate([
            'student_name' => 'required',
            'gender' => 'required',
            'fees_amount' => 'required'
        ]);

        $sid = $data['sid'];
        $student = Student::select('*')
            ->where("id", "=", $sid)
            ->first();
        $student->student_name = $data['student_name'];
        $student->gender = $data['gender'];
        $student->fees_amount = $data['fees_amount'];
        $student->status = '1';
        $student->save();
        return redirect(route('all-student',['user_id'=>  $student->parent_id]))->with('success', "Student updated successfully..!");
    }

    function change_student_status(Request $request){
        $sid = $request->sid;
        $status = $request->status;

        $student = Student::select("*")
            ->where("id", "=", $sid)
            ->first();
        // print_r($role);exit;
        if ($status != $student->status && ($status =='2')) {
            $student->status = $status;
            $student->save();
            return back()->with('success', 'Student is Deleted successfully');
        }

        if ($status != $student->status && ($status =='0')) {
            $student->status = $status;
            $student->save();
            return back()->with('success', 'Student is  DeActivated  successfully');
        }

        if ($status != $student->status && ($status =='1')) {
            $student->status = $status;
            $student->save();
            return back()->with('success', 'Student is Activated successfully');
        }
        return back()->with('error','oops..! something went wrong');
    }

    function my_student()
    {

        $info = Session::get("isLogin");
        $data['my_students'] = DB::table("student_teacher as t")
            ->select("t.student_id","t.id as stid", "t.course_id", "s.student_name", "c.course_title", "u.email", "u.phone_number")
            ->join("students as s", "t.student_id", "s.id")
            ->where("t.teacher_id", "=", $info->user_id)
            ->join("courses as c", "c.id", "t.course_id")
            ->join("users as u", "s.parent_id", "u.id")
            ->orderBy("t.id", "DESC")
            ->get();
        return view("student.my_students", $data);
    }

    function student_lesson_history()
    {
        try {
            $info = Session::get("isLogin");
            // Get all the students against of parent
            $std_arr = DB::table('students')->where("students.parent_id", "=",  $info->user_id)->pluck('id')->toArray();
            $data['lesson_histories'] = [];
            if(count($std_arr) > 0) {
                $sql = Classes::query()
                    ->select("*")
                    ->whereIn('student_id', $std_arr)
                    ->whereNotNull('lesson_id');
                $data['lesson_histories'] = $sql->get();
                return view("parent.lesson_history",$data);
            }
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

}
