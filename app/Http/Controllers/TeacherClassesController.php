<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Common;
use App\Models\User;
use App\Models\Course;
use App\Models\CoursesLessons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TeacherClassesController extends Controller
{
    function teacher_classes(Request $request)
    {
        $data = array();
        try {
            $data = $request->all();
            $data['type'] = isset($data['type']) ? $data['type'] : "1";
            $data['time_slots'] = DB::table("time_slots as ts")
                ->select("ts.id", "ts.t_slot")
                ->get();
            return view("my_classes.my-classes", $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function ajax_my_student_classes(Request $request)
    {
        try {
            $data = $request->all();
            $classOBJ = new Classes();
            $start_date = $data['start_date'];
            $time_zone = $data['time_zone'];
            $slot_id = isset($data['slot_id']) ? $data['slot_id'] : '';
            $info = Session::get("isLogin");
            $teacher_id = $info->user_id;
            list($year, $month, $day) = explode("-", $start_date);
            $day = date("l", mktime(0, 0, 0, $month, $day, $year));
            // all classes that are not yet scheduled on specific date
            $base_settings = $classOBJ->getClassesBaseSettings($day);
            if (empty($base_settings)) {
                return redirect(route('configure_classes'))->with('error', "No base settings is available");
            }
            // Configuration of classes based on student classes days
            $classOBJ->configureSchedule($base_settings, $start_date);
            $time1 = strtotime($start_date);
            $time2 = strtotime(date('Y-m-d'));
            $common = new Common();
            $diff_arr = $common->calculateTimeDifference($time1, $time2);
            if ($diff_arr['days'] >= 0) {
                $classOBJ->configureSchedule($base_settings, $start_date);
            }
            $sql = Classes::query()
                ->select("*")
                ->where("class_date", "=", $start_date);
            if ($slot_id != '') {
                $sql->where("slot_id", "=", $slot_id);
            }
            if ($teacher_id != '') {
                $sql->where("teacher_id", "=", $teacher_id);
            }
            $sql->orderBy("slot_id","ASC");
            $classes = $sql->get();
            $classOBJ->adjust_class_timings($classes);
            $classes = $classOBJ->setBackgroundColours($classes, $start_date, $time_zone);
            $data['classes'] = $classes;
            return view('my_classes.ajax_load_classes', $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function start_cls(Request $request)
    {
        $data = array();
        try {
            $data = $request->all();
            $info = Session::get("isLogin");
            $data['start_cls'] = $start_cls = Classes::query()
                ->select("*")
                ->where("classes.id", "=", $data['cls_id'])
                ->where("classes.teacher_id", "=", $info->user_id)
                ->first();


            // Class status will change to in prograss when above code run
            $start_cls->start_time = date('H:i:s');
            $start_cls->status = 1;
            $start_cls->save();

            $data['courses_lessons'] = CoursesLessons::query()
                ->select("*")
                ->where("course_id", "=", $start_cls->course_id)
                ->get();

            $data['taught_lessons'] = Classes::query()
                ->select("*")
                ->where("course_id", "=", $start_cls->course_id)
                ->whereNotNull('lesson_id')
                ->get();

            return view("my_classes.start_class", $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function start_cls_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $info = Session::get("isLogin");
            $class_data = Classes::query()
                ->select("*")
                ->where("classes.id", "=", $data['class_id'])
                ->where("classes.teacher_id", "=", $info->user_id)
                ->first();
            // print_r($class_data);exit;
            $_files_arr = array();

            if ($files = $request->file('recordings')) {
                foreach ($files as $key => $file) {
                    $file_name = "recording_" . $key . "_" . $data['class_id'] . "_" . time() . "." . $file->getClientOriginalExtension();
                    $file->storeAs("recording", $file_name, 'uploads');
                    $_files_arr[] = $file_name;
                }
            }

            $class_data->lesson_id = $data['course_lesson'];
            $class_data->status = 2;
            $class_data->lesson_repeat = $data['lesson_repeat'] == 1 ? 1 : 0;
            $class_data->remarks = $data['remarks'];
            $class_data->recordings = implode("|", $_files_arr);
            $class_data->end_time = date('H:i:s');
            $class_data->save();
            return redirect(route('teacher_classes', ['type' => 1]))->with('success', "Class uploaded successfully..!");
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function load_lesson(Request $request)
    {
        try {
            $data = $request->all();
            $course_lesson = $data['course_lesson'];
            $data['courses_lesson'] = CoursesLessons::query()
                ->select("*")
                ->where("id", "=", $data['course_lesson'])
                ->first();
            $returnHTML = view('my_classes.ajax_load_lesson')->with($data)->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function taught_lesson(Request $request)
    {
        $data = $request->all();

        if (isset($data['repeat_status']) and $data['repeat_status'] == 0) {
            $options = ' <option value="">Select Lesson</option>';
            $courses_lessons = CoursesLessons::query()
                ->select("*")
                ->where("course_id", "=", $data['course_id'])
                ->get();
            if (!empty($courses_lessons) && isset($courses_lessons)) {
                foreach ($courses_lessons as $courses_lesson) {
                    $options .= '<option value="' . $courses_lesson->id . '">' . $courses_lesson->lesson_label . '</option>';
                }
            } else {
                $options = '<option>No  lesson</option>';
            }
        }

        if (isset($data['repeat_status']) and $data['repeat_status'] == 1) {
            $options = 'Select taught Lesson';
            $taught_lessons = Classes::query()
                ->select("*")
                ->where("course_id", "=", $data['course_id'])
                ->whereNotNull('lesson_id')
                ->get();
            if (!empty($taught_lessons) && isset($taught_lessons)) {
                foreach ($taught_lessons as $taught_lesson) {
                    $options .= '<option value="' . $taught_lesson->coursesLessons->id . '">' . $taught_lesson->coursesLessons->lesson_label . '</option>';
                }
            } else {
                $options = '<option>No repeat lesson</option>';
            }
        }
        echo $options;
    }
}
