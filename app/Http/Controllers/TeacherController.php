<?php

namespace App\Http\Controllers;

use App\Models\Common;
use App\Models\Course;
use App\Models\StudentClassDays;
use App\Models\Teacher;
use App\Models\TeacherFreeSlots;
use App\Models\TeacherStudents;
use App\Models\TeacherWorkTimings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TeacherController extends Controller
{
    private $course = "";
    private $thrStds = "";
    private $teacher = "";
    private $thrFreeSlots = "";
    private $teacher_timings = "";
    private $common = "";

    function __construct()
    {
        $this->thrStds = new TeacherStudents();
        $this->teacher = new Teacher();
        $this->course = new Course();
        $this->thrFreeSlots = new TeacherFreeSlots();
        $this->common = new Common();
    }

    function thr_search_slots()
    {
        try {
            $teachers = User::query()
                ->select("id", "email", "phone_number")
                ->where("role_id", "=", "4")
                ->get();
            $data['teachers'] = $teachers;
            return view('teacher.thr_search_slots', $data);
        } catch (\Exception $exception) {
            return redirect(route('thr_office_timings'));
        }
    }

    function thr_free_slots(Request $request)
    {
        try {
            $data = $request->all();
            $teacher_id = isset($data['teacher_id']) ? $data['teacher_id'] : '';
            $request_type = isset($data['request_type']) ? $data['request_type'] : '';
            $this->teacher_timings = $this->thrFreeSlots->getTeacherWorkTimings($teacher_id);
            if ($this->teacher_timings->count() <= 0) {
                $this->status = 0;
                $this->message = "Teacher work timing is not configured";
                return $this->sendResponse();
            }

            $assigned_slots = $this->thrFreeSlots->getThrWeeklyAssignedSlots($teacher_id);
            $assigned_slots = $this->thrFreeSlots->adjustedAssignedTimings($assigned_slots);
            $all_slots = $this->thrFreeSlots->getTimesSlots();
            $all_available_slots = $this->thrFreeSlots->teacher_free_slots($this->teacher_timings, $assigned_slots, $all_slots);
            $data['week_days'] = $all_available_slots;
            if ($request_type == 'assign_std') {
                $data['student_teacher_id'] = "";
                $data['assigned_slots'] = array();
                return view("teacher.ajax_assign_std_thr_free_slots", $data);
            }
            return view("teacher.ajax_thr_free_slots", $data);
        } catch (\Exception $exception) {
            $this->message = $exception->getLine();
            return $this->sendResponse();
        }
    }

    function thr_office_timings()
    {
        try {
            $teachers = User::query()
                ->select("id", "email", "phone_number")
                ->where("role_id", "=", "4")
                ->get();
            $data['start_slots'] = DB::table("time_slots as ts")
                ->select("ts.id", "ts.t_slot")
                ->orderBy("ts.id", "ASC")
                ->get();
            $data['end_slots'] = DB::table("time_slots as ts")
                ->select("ts.id", "ts.t_slot")
                ->orderBy("ts.id", "DESC")
                ->get();
            $data['teachers'] = $teachers;
            return view('teacher.thr_office_timings', $data);
        } catch (\Exception $exception) {
            return redirect(route('thr_office_timings'));
        }
    }

    function thr_office_timings_submitted(Request $request)
    {

        $data = $request->all();
        parse_str($_POST['data'], $params);

        DB::table('teacher_work_timings')->where('user_id', $params['teacher_id'])->delete();

        if (isset($params['start_time_1']) and isset($params['end_time_1'])) {
            $teacherWorkTimings = new TeacherWorkTimings();
            $teacherWorkTimings->user_id = $params['teacher_id'];
            $teacherWorkTimings->start_slot_id = $params['start_time_1'];
            $teacherWorkTimings->end_slot_id = $params['end_time_1'];
            $teacherWorkTimings->save();
        }

        if ((isset($params['start_time_2']) and !empty($params['start_time_2'])) and (isset($params['end_time_2']) and !empty($params['end_time_2']))) {
            $teacherWorkTimings = new TeacherWorkTimings();
            $teacherWorkTimings->user_id = $params['teacher_id'];
            $teacherWorkTimings->start_slot_id = $params['start_time_2'];
            $teacherWorkTimings->end_slot_id = $params['end_time_2'];
            $teacherWorkTimings->save();
        }
        echo 'done';
    }

    function get_thr_office_timing(Request $request)
    {
        $data = $request->all();
        $data['start_slots'] = DB::table("time_slots as ts")
            ->select("ts.id", "ts.t_slot")
            ->orderBy("ts.id", "ASC")
            ->get();
        $data['end_slots'] = DB::table("time_slots as ts")
            ->select("ts.id", "ts.t_slot")
            ->orderBy("ts.id", "DESC")
            ->get();

        $data['tw'] = DB::table("teacher_work_timings as tw")
            ->select("tw.start_slot_id", "tw.end_slot_id")
            ->where('user_id', $data['teacher_id'])
            ->orderBy("tw.id", "ASC")
            ->get()->all();
        //echo "<pre>";print_r($data['tw']);exit;

        $returnHTML = view('teacher.ajax_thr_office_timing')->with($data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function thr_std_schedule()
    {
        try {
            $teachers = User::query()
                ->select("id", "email", "phone_number")
                ->where("role_id", "=", "4")
                ->get();

            $courses = Course::query()
                ->select("id", "course_title")
                ->where("status", "=", "1")
                ->get();

            $data['teachers'] = $teachers;
            $data['courses'] = $courses;
            return view('teacher_student.thr_std_schedule', $data);
        } catch (\Exception $exception) {
            //return redirect(route('thr_std_schedule'))->with("error", $exception->getMessage());
        }
    }

    function load_thr_sdt(Request $request)
    {
        try {
            $data = $request->all();
            $teacher_id = isset($data['teacher_id']) ? $data['teacher_id'] : '';
            $course_id = isset($data['course_id']) ? $data['course_id'] : '';

            $type = $this->course->getCourseType($course_id);
            if ($teacher_id == "") {
                $this->message = "Please select teacher";
                return $this->sendResponse();
            }
            $teacher_timings = $this->thrFreeSlots->getTeacherWorkTimings($teacher_id);
            if ($teacher_timings->count() <= 0) {
                $this->status = 0;
                $this->message = "Teacher work timing is not configured";
                return $this->sendResponse();
            }
            $teacher_students = $this->thrStds->getTeacherStudents($teacher_id, $course_id, $type);

            $assigned_slots = $this->thrFreeSlots->getThrWeeklyAssignedSlots($teacher_id);
            $assigned_slots = $this->thrFreeSlots->adjustedAssignedTimings($assigned_slots);
            // echo "<pre>";print_r($assigned_slots);
            // exit;
            $all_slots = $this->thrFreeSlots->getTimesSlots();
            $all_available_slots = $this->thrFreeSlots->teacher_free_slots($teacher_timings, $assigned_slots, $all_slots);
            $courses = $this->course->getCourses();

            $data['week_days'] = $this->common->getWeekDays();
            $data['courses'] = $courses;
            $data['all_available_slots'] = $all_available_slots;
            $data['all_slots'] = $all_available_slots;
            $data['teacher_students'] = $teacher_students;
            return view('teacher.ajax_load_thr_std', $data);
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function student_schedule_detail(Request $request)
    {
        $info = Session::get("isLogin");
        $data = $request->all();
        $stid = isset($data['stid']) ? $data['stid'] : '';
        //  echo $stid;exit;
        $schedule_detail = DB::table("student_class_days as scd")
            ->select("scd.day_id", "scd.slot_id", "ts.t_slot", "wd.day")
            ->join("time_slots as ts", "ts.id", "scd.slot_id")
            ->join("week_days as wd", "wd.id", "scd.day_id")
            ->where("scd.student_teacher_id", "=", $stid)
            ->orderBy("scd.id", "DESC")
            ->get();
        $returnHTML = view('users.ajax-student-schedule-detail')->with('schedule_detail', $schedule_detail)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function assign_students()
    {
        try {
            $data['teachers'] = User::query()
                ->select("id", "role_id")
                ->where("role_id", "=", "4")
                ->get();
            $data['courses'] = DB::table("courses as c")
                ->where("c.status", "=", "1")
                ->get();
            return view('teacher.assign_students', $data);
        } catch (\Exception $exception) {
            return redirect(route('assign_students'))->with('error', $exception->getMessage());
        }

    }

    function search_student(Request $request)
    {
        try {
            $html = "";
            $data = $request->all();
            $student_name = $data['student_name'];
            $students = DB::table("students as s")
                ->where("student_name", "like", "%" . $student_name . "%")
                ->get();
            $output = '<ul class="studnet_ul">';

            if ($students) {
                foreach ($students as $student) {
                    $output .= '<li data-userid="' . $student->id . '">' . ucwords($student->student_name) . '</li>';
                }
            } else {
                $output .= '<li> Student not Found</li>';
            }
            $output .= '</ul>';
            echo $output;
            exit;

        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function assign_students_submitted(Request $request)
    {
        try {
            $params = $request->all();
            parse_str($_POST['data'], $params);
            $teacher_id = $params['teacher_id'];
            $student_id = $params['student_id'];
            $course_id = $params["course_id"];
            $fees = $params["fees"];
            $remarks = $params["remarks"];
            $student_teacher_id = "";
            if ($student_id == '') {
                $this->message = "Please select student";
                return $this->sendResponse();
            } elseif ($teacher_id == '') {
                $this->message = "Please select teacher";
                return $this->sendResponse();
            } elseif ($course_id == '') {
                $this->message = "Please select course";
                return $this->sendResponse();
            } elseif ($fees == '') {
                $this->message = "Please select fees";
                return $this->sendResponse();
            }
            $tstd = TeacherStudents::query()
                ->where("teacher_id", "=", $teacher_id)
                ->where("student_id", "=", $student_id)
                ->where("course_id", "=", $course_id)
                ->first();
            if (empty($tstd) && !isset($tstd->id)) {
                DB::table("student_teacher as st")
                    ->where("st.student_id", "=", $student_id)
                    ->where("status", "=", "1")
                    ->where("end_date", "=", null)
                    ->update(array("end_date" => date("Y-m-d")));
                $tstd = new TeacherStudents();
            }
            $tstd->teacher_id = $teacher_id;
            $tstd->student_id = $student_id;
            $tstd->course_id = $course_id;
            $tstd->start_date = date('Y-m-d');
            $tstd->slot_id = 2;
            $tstd->fees = $fees;
            $tstd->remarks = $remarks;
            $tstd->status = '1';
            if ($tstd->save()) {
                $student_teacher_id = $tstd->id;
                $schedule = $params['schedule'];
                if (!empty($schedule)) {
                    foreach ($schedule as $key => $sch) {
                        $day_id = $key;
                        $slot_id = $sch['slot_id'];
                        $required_slots = $sch['required_slots'];
                        if ($slot_id != '') {
                            $myschedule = StudentClassDays::query()
                                ->where("student_teacher_id", $student_teacher_id)
                                ->where("day_id", "=", $day_id)
                                ->first();
                            if (!empty($myschedule)) {
                                $myschedule->slot_id = $slot_id;
                                $myschedule->required_slots = $required_slots;
                                $myschedule->save();
                            } else {
                                $myschedule = new StudentClassDays();
                                $myschedule->student_teacher_id = $student_teacher_id;
                                $myschedule->slot_id = $slot_id;
                                $myschedule->day_id = $day_id;
                                $myschedule->required_slots = $required_slots;
                                $myschedule->save();
                            }
                        } else {
                            StudentClassDays::query()
                                ->where("student_teacher_id", $student_teacher_id)
                                ->where("day_id", "=", $day_id)
                                ->delete();
                        }
                    }
                }
                DB::table("classes")
                    ->where("student_teacher_id", "=", $tstd->id)
                    ->where("status", "=", 0)
                    ->where("class_date", ">=", date("Y-m-d"))
                    ->delete();
                $this->status = 1;
                $this->message = "Teacher is assigned successfully..";
                return $this->sendResponse();
            }
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

}
