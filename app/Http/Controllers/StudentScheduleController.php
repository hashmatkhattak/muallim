<?php

namespace App\Http\Controllers;

use App\Models\StudentClassDays;
use App\Models\TeacherFreeSlots;
use App\Models\TeacherStudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentScheduleController extends Controller
{
    private $thrFreeSlots = "";

    function __construct()
    {
        $this->thrFreeSlots = new TeacherFreeSlots();
    }

    function add_std_schedule(Request $request)
    {
        try {
            $data = $request->all();
            $teacher_id = isset($data['teacher_id']) ? $data['teacher_id'] : '';
            $student_teacher_id = isset($data['student_teacher_id']) ? $data['student_teacher_id'] : '';
            $request_type = isset($data['request_type']) ? $data['request_type'] : '';
            $this->teacher_timings = $this->thrFreeSlots->getTeacherWorkTimings($teacher_id);
            if ($this->teacher_timings->count() <= 0) {
                $this->status = 0;
                $this->message = "Teacher work timing is not configured";
                return $this->sendResponse();
            }
            $assigned_slots = $this->thrFreeSlots->getThrWeeklyAssignedSlots($teacher_id, $student_teacher_id);
            $assigned_slots = $this->thrFreeSlots->adjustedAssignedTimings($assigned_slots);
            $all_slots = $this->thrFreeSlots->getTimesSlots();
            $all_available_slots = $this->thrFreeSlots->teacher_free_slots($this->teacher_timings, $assigned_slots, $all_slots);
            $data['week_days'] = $all_available_slots;
            $data['assigned_slots'] = $assigned_slots;
            $returnHTML = view('teacher/ajax_update_thr_std_slots', $data)->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        } catch (\Exception $exception) {
            $this->message = $exception->getTraceAsString();
            return $this->sendResponse();
        }
    }

    function std_schedule_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $student_teacher_id = $data['student_teacher_id'];
            //Delete pervious student class days schedule
            DB::table("student_class_days")
                ->where("student_teacher_id", "=", $student_teacher_id)
                ->delete();

            DB::table("classes")
                ->where("student_teacher_id", "=", $student_teacher_id)
                ->where("status", "=", "0")
                ->where("class_date", ">=", date('Y-m-d', time()))
                ->delete();

            $schedule = $data['schedule'];
            if (!empty($schedule)) {
                foreach ($schedule as $key => $sch) {
                    $day_id = $key;
                    $slot_id = $sch['slot_id'];
                    $required_slots = $sch['required_slots'];
                    if ($slot_id != '') {
                        $myschedule = new StudentClassDays();
                        $myschedule->student_teacher_id = $student_teacher_id;
                        $myschedule->slot_id = $slot_id;
                        $myschedule->day_id = $day_id;
                        $myschedule->required_slots = $required_slots;
                        $myschedule->save();
                    }
                }
            }
            $this->status = 1;
            $this->message = "Student schedule is successfully updated.";
            return $this->sendResponse();
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function delete_thr_std_schedule(Request $request)
    {
        try {
            $data = $request->all();
            $student_teacher_id = $data['student_teacher_id'];

            DB::table("student_class_days")
                ->where("student_teacher_id", "=", $student_teacher_id)
                ->delete();

            DB::table("classes")
                ->where("student_teacher_id", "=", $student_teacher_id)
                ->where("status", "=", "0")
                ->where("class_date", ">=", date('Y-m-d', time()))
                ->delete();

            $this->status = 1;
            $this->message = "Student schedule is deleted";
            return $this->sendResponse();
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }
}

