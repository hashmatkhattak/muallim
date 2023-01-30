<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesLessons;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    function add_course()
    {
        return view("courses.add_course");
    }

    function add_course_submitted(Request $request)
    {

        try {

            //---------------------------------------------------------------------------------------------
            $course = new Course();
            $course->company_id = 1;
            $course->course_title = $data['course_title'];
            $course->course_type = $data['course_type'];
            $course->cdescription = isset($data['cdescription']) ? $data['cdescription'] : '';
            $course->status = '1';
            $course->save();

            return redirect(route('all_courses'))->with('success', "Course added successfully..!");
        } catch (Exception $exception) {
            return redirect(route('add_course'))->with('error', $exception->getMessage());
        }
    }

    function all_courses()
    {
        $info = Session::get("isLogin");
        $data['courses'] = DB::table("courses as c")
            ->select("*")
            ->where("status", "!=", "2")
            ->where("c.company_id", "=", $info->company_id)
            ->orderBy("c.id", "DESC")
            ->get();
        return view("courses.all_courses", $data);
    }

    function edit_course()
    {
        $data['title'] = 'Edit country';
        $data['breadcrums'] = 'Edit country';

        $id = $_GET['cid'];
        $course = Course::select('*')
            ->where("id", "=", $id)
            ->first();

        $data['course'] = $course;
        return view('courses/edit_course', $data);
    }

    function edit_course_submitted(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'course_title' => 'required',
            'course_type' => 'required'
        ]);

        $c_id = $data['cid'];

        $course = Course::select('*')
            ->where("id", "=", $c_id)
            ->first();

        $course->company_id = 1;
        $course->course_title = $data['course_title'];
        $course->course_type = $data['course_type'];
        $course->cdescription = isset($data['cdescription']) ? $data['cdescription'] : '';
        $course->status = '1';
        $course->save();
        return redirect(route('all_courses'))->with('info', "Updated");
    }

    function change_course_status(Request $request)
    {
        $id = $request->course_id;
        $status = $request->status;
        $course = Course::select("*")
            ->where("id", "=", $id)
            ->first();
        //print_r($course);exit;
        if ($status != $course->status && ($status == '0' || $status == '1' || $status == '2')) {
            $course->status = $status;
            $course->save();
            if ($status == 2) {
                return redirect(route('all_courses'))->with('success', 'Course Deleted successfully');
            } else if ($status == 1) {
                return redirect(route('all_courses'))->with('success', 'Course Activated successfully');
            } else if ($status == 0) {
                return redirect(route('all_courses'))->with('success', 'Course  Deactivated Successfully');
            }
        }
        return redirect(route('all_courses'))->with('error', 'oops..! something went wrong');
    }

    //-----------------------------------------------------------------------------------------------------------------

    function all_course_lessons(Request $request)
    {
        $info = Session::get("isLogin");
        $course_id = $request->course_id;
        $data['course_id'] = isset($course_id) ? $course_id : '';
        $data['courses_lessons'] = DB::table("courses_lessons as c")
            ->select("*")
            ->where("c.course_id", "=", $course_id)
            ->orderBy("c.id", "ASC")
            ->get();
        return view("course_lessons.all_courses_lessons", $data);
    }

    function add_lesson()
    {
        return view("course_lessons.add_lessons");
    }

    function add_lesson_submitted(Request $request)
    {
        $request->validate([
            'lesson_label' => 'required',
            'lesson_tablet' => 'required'
        ]);
        try {
            $data = $request->all();
            //---------------------------------------------------------------------------------------------
            $coursesLessons = new CoursesLessons();
            $coursesLessons->course_id = $data['cid'];
            $coursesLessons->lesson_label = $data['lesson_label'];
            $coursesLessons->lesson_detail = $data['lesson_detail'];
            $coursesLessons->lesson_tablet = $data['lesson_tablet'];
            $coursesLessons->status = '1';
            $coursesLessons->save();
            return redirect(route('all_course_lessons', ['course_id' => $data['cid']]))->with('success', "Lesson added successfully..!");
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    function edit_lesson()
    {
        $lid = $_GET['lid'];
        $lesson = CoursesLessons::select('*')
            ->where("id", "=", $lid)
            ->first();

        $data['lesson'] = $lesson;
        return view('course_lessons/edit_lessons', $data);
    }

    function edit_lessons_submitted(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'lesson_label' => 'required',
            'lesson_tablet' => 'required'
        ]);

        $lid = $data['lid'];

        $coursesLessons = CoursesLessons::select('*')
            ->where("id", "=", $lid)
            ->first();

        //$coursesLessons->couse_id = 1;
        $coursesLessons->lesson_label = $data['lesson_label'];
        $coursesLessons->lesson_detail = $data['lesson_detail'];
        $coursesLessons->lesson_tablet = $data['lesson_tablet'];
        $coursesLessons->status = '1';
        $coursesLessons->save();
        return redirect(route('all_course_lessons', ['course_id' => $coursesLessons->course_id]))->with('success', "Lesson updated successfully..!");
    }

    function change_lesson_status(Request $request)
    {
        $id = $request->lid;
        $status = $request->status;
        $coursesLessons = CoursesLessons::select("*")
            ->where("id", "=", $id)
            ->first();
        //print_r($course);exit;
        if ($status != $coursesLessons->status && ($status == '0' || $status == '1' || $status == '2')) {
            $coursesLessons->status = $status;
            $coursesLessons->save();
            if ($status == 2) {
                return back()->with('success', 'Lesson Deleted successfully');
            } else if ($status == 1) {
                return back()->with('success', 'Lesson Activated successfully');
            } else if ($status == 0) {
                return back()->with('success', 'Lesson  Deactivated Successfully');
            }
        }
        return redirect(route('all_courses'))->with('error', 'oops..! something went wrong');
    }

    function lesson_detail(Request $request)
    {
        $id = $request->lid;
        $data['lesson_detail'] = DB::table("courses_lessons")
            ->select("*")
            ->where("id", "=", $id)
            ->first();
        return view("course_lessons.lesson_detail", $data);
    }

    //----------------------------------------------------Show teacher logged in based data-----------------------------
    function my_courses()
    {
        $info = Session::get("isLogin");
        $data['courses'] = DB::table("courses as c")
            ->select("*")
            ->where("status", "!=", "2")
            ->where("c.company_id", "=", $info->company_id)
            ->orderBy("c.id", "DESC")
            ->get();
        return view("courses.my_courses", $data);
    }

    function my_course_lessons(Request $request)
    {
        $info = Session::get("isLogin");
        $course_id = $request->course_id;
        $data['course_id'] = isset($course_id) ? $course_id : '';
        $data['courses_lessons'] = DB::table("courses_lessons as c")
            ->select("*")
            ->where("c.course_id", "=", $course_id)
            ->orderBy("c.id", "ASC")
            ->get();
        return view("course_lessons.my_courses_lessons", $data);
    }

    function lesson_content(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $file = $request->file("upload");
            $msg = 'Image uploaded successfully';
            $file->storeAs("lesson", $fileName, 'uploads');
            $url = asset('uploads/lesson/' . $fileName);
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
