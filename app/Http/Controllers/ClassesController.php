<?php

namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Common;
use App\Models\Teacher;
use App\Models\TeacherFreeSlots;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClassesController extends Controller
{
    function load_classes(Request $request)
    {
        try {
            $data = $request->all();
            $type = isset($data['type']) ? $data['type'] : "1";
            $data['label'] = "Select slot:";
            if ($type == '1') {
                $data['time_slots'] = DB::table("time_slots as ts")
                    ->select("ts.id", "ts.t_slot")
                    ->get();
            } else if ($type == '2') {
                $data['label'] = "Select Teacher:";
                $data['teachers'] = User::query()
                    ->select("id")
                    ->where("role_id", "=", "4")
                    ->where("status", "=", 1)
                    ->get();
            } else if ($type == '3') {
                $data['label'] = "Select Student:";
                $data['teachers'] = User::query()
                    ->select("id")
                    ->where("role_id", "=", "4")
                    ->where("status", "=", 1)
                    ->get();
            }
            return view('classes.load_classes', $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function ajax_load_classes(Request $request)
    {
        try {
            $data = $request->all();
            $classOBJ = new Classes();
            $start_date = $data['start_date'];
            $time_zone = $data['time_zone'];
            $slot_id = isset($data['slot_id']) ? $data['slot_id'] : '';
            $teacher_id = isset($data['teacher_id']) ? $data['teacher_id'] : '';
            $std_id = isset($data['std_id']) ? $data['std_id'] : '';
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
            if ($std_id != '') {
                $sql->where("student_id", "=", $std_id);
            }
            $sql->orderBy("slot_id", "ASC");
            
            $classes = $sql->get();
            $classOBJ->adjust_class_timings($classes);
            $classes = $classOBJ->setBackgroundColours($classes, $start_date, $time_zone);
            $data['classes'] = $classes;
            return view('classes.ajax.ajax_load_classes', $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getTraceAsString();
            return $this->sendResponse();
        }
    }

    function configure_classes()
    {
        try {
            return view('classes.configure_classes');
        } catch (\Exception $exception) {
            return redirect(route('configure_classes'))->with('error', $exception->getMessage());
        }
    }

    function configure_classes_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];
            $classOBJ = new Classes();
            // 1- we have base settings of classes, we will extract all those classes that have not been
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
            while ($start_date <= $end_date) {
                $start_date = date('Y-m-d', $start_date);
                // scheduled yet
                list($year, $month, $day) = explode("-", $start_date);
                $day = date("l", mktime(0, 0, 0, $month, $day, $year));
                // all classes that are not yet scheduled on specific date
                $base_settings = $classOBJ->getClassesBaseSettings($day);
                if (empty($base_settings)) {
                    return redirect(route('configure_classes'))->with('error', "No base settings is available");
                }
                // Configuration of classes based on student classes days
                $classOBJ->configureSchedule($base_settings, $start_date);
                $start_date = strtotime(date("Y-m-d", strtotime($start_date)) . " +1 day");
            }
            $message = "Successfully Configured successfully..";
            return redirect(route('configure_classes'))->with('success', $message);
        } catch (\Exception $exception) {
            return redirect(route('configure_classes'))->with('error', $exception->getMessage());
        }
    }

    function available_teacher(Request $request)
    {
        try {
            $data = $request->all();
            $reschedule_date = isset($data['reschedule_date']) ? $data['reschedule_date'] : "";
            $reschedule_class_id = isset($data['reschedule_class_id']) ? $data['reschedule_class_id'] : "";
            $slot_id = isset($data['slot_id']) ? $data['slot_id'] : "";
            $thrFreeSlots = new TeacherFreeSlots();
            if ($reschedule_date != '' && $reschedule_class_id != '' && $slot_id != '') {
                list($year, $month, $day) = explode("-", $reschedule_date);
                $date_time = strtotime($reschedule_date);
                $day = strtolower(date("l", $date_time));
                $clsObj = new Classes();
                $class_details = $clsObj->getClassTimeDetails($reschedule_class_id);
                $all_teachers = $clsObj->getAllTeachers();
                $free_teacher = array();
                foreach ($all_teachers as $key => $value) {
                    $teacher_timings = $thrFreeSlots->getTeacherWorkTimings($value->id);
                    $assigned_slots = $thrFreeSlots->getThrWeeklyAssignedSlots($value->id);
                    $assigned_slots = $thrFreeSlots->adjustedAssignedTimings($assigned_slots);
                    $all_slots = $thrFreeSlots->getTimesSlots();
                    $all_available_slots = $thrFreeSlots->teacher_free_slots($teacher_timings, $assigned_slots, $all_slots);
                    echo "<pre>";print_r($all_available_slots);exit;
                    $rs = $thrFreeSlots->checkTeacherForAvailableSlot($all_available_slots, ucwords($day), $slot_id, $class_details);
                    if ($rs == true) {
                        array_push($free_teacher, $value);
                    }
                }
                print_r($free_teacher);exit;
                $all_free = array();
                $classObj = new classDAO();
                // now need to check in classes if the teacher has not been assign any class
                foreach ($free_teacher as $key => $value) {
                    list($year, $month, $day) = explode("-", $_POST['date']);
                    list($year, $month, $pday) = explode("-", date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year)));
                    $selected_date = $_POST['date'];
                    $previous_date = $year . "-" . $month . "-" . $pday;
                    $time_slot_array = $classObj->todays_classes($value['id'], $selected_date);
                    $consumes_slots = $this->assinged_slots_in_classes($time_slot_array);
                    $time_slot_array = $classObj->todays_classes($value['id'], $previous_date);
                    $consumes_slots = $this->assinged_slots_of_today_in_yesterday_classes($time_slot_array, $consumes_slots);
                    $ret = $this->check_teacher_in_classes($consumes_slots, $class_time_slot_detail, $time_slot_id);
                    if ($ret == true) {
                        array_push($all_free, $value);
                    }
                };
            }
            $this->status = 0;
            $this->message = "Required data is missing";
            return $this->sendResponse();
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function reschedule_class(Request $request)
    {
        try {
            $data = $request->all();
            $data['slots'] = DB::table("time_slots as ts")
                ->get();
            return view("classes.reschedule_class", $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function view_cls_report(Request $request)
    {
        try {
            $data = $request->all();
            $class_id = $data['class_id'];
            $report = Classes::query()
                ->select("id", "teacher_id", "student_id", "course_id", "slot_id", "start_time", "end_time")
                ->where("id", "=", $class_id)
                ->first();
            $data['report'] = $report;
            return view("classes.view_cls_report", $data);
        } catch (\Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

}
