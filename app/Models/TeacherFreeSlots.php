<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherFreeSlots extends Model
{
    private $free_slots = "";
    private $common = "";

    function __construct(array $attributes = [])
    {
        $this->common = new Common();
        $this->free_slots = [
            'Monday' => array(),
            'Tuesday' => array(),
            "Wednesday" => array(),
            'Thursday' => array(),
            'Friday' => array(),
            'Saturday' => array(),
            'Sunday' => array()
        ];
    }

    function getThrWeeklyAssignedSlots($teacher_id, $student_teacher_id = "")
    {
        try {
            $week_days = DB::table("week_days as wd")
                ->select("id", "day")
                ->get();
            foreach ($week_days as $day) {
                //Assigned slots for week days
                $qry = DB::table("student_class_days as scd")
                    ->select("scd.day_id", "scd.slot_id", "scd.slot_id", "scd.required_slots", "st.teacher_id", "st.student_id", "st.course_id", "ts.t_slot", "scd.student_teacher_id")
                    ->join("time_slots as ts", "ts.id", "scd.slot_id")
                    ->join("student_teacher as st", "st.id", "scd.student_teacher_id")
                    ->where("scd.day_id", $day->id);
                if ($teacher_id!=''){
                    $qry->where("st.teacher_id", $teacher_id);
                }

                if (isset($student_teacher_id) and $student_teacher_id > 0) {
                    $qry->where("scd.student_teacher_id", $student_teacher_id);
                }
                $day->assigned_slots = $qry->get();
            }
            return $week_days;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    function adjustedAssignedTimings($assigned_slots)
    {
        //  echo "<pre>";print_r($assigned_slots);exit;
        foreach ($assigned_slots as $key => $day) {
            if (empty($day)) {
                continue;
            } else {
                foreach ($day->assigned_slots as $index => $value) {
                    //print_r($value);exit;
                    $time_slot_id = $value->slot_id;
                    $required_slots = $value->required_slots;
                    $value->class_start_day = $day->day;
                    $final_slot = $time_slot_id + $required_slots - 1;
                    // echo "<pre>";print_r($value);exit;
                    // 48 are total slots. And 48 is the last slot .. slot mean half an hr time (12:00 AM - 12:30 AM)
                    if ($final_slot > 48) {
                        // echo "<pre>";print_r($value);exit;
                        $endingSlot = $final_slot % 48;
                        $current_index = $value->day_id;

                        $new_index = ($current_index + 1) % 7;
                        $day = DB::table("week_days as wd")->select("wd.id", "wd.day")
                            ->where('id', $new_index)
                            ->first();
                        //print_r($new_index);
                        //$next_day = $this->numbered_week_days[$new_index];

                        $value->class_end_day = $day->day;
                        // echo "hereee";exit;
                        $arr = array('id' => $value->day_id,
                            'student_id' => $value->student_id,
                            'teacher_id' => $value->teacher_id,
                            'course' => $value->course_id,
                        );
                        // echo "<pre>";print_r($assigned_slots);exit;
                        for ($i = 1; $i <= $endingSlot; $i++) {
                            $arr['slot_id'] = $i;
                            //array_push($assigned_slots[$new_index] $arr);
                            $assigned_slots[$new_index]->student_id = $value->day_id;
                            $assigned_slots[$new_index]->teacher_id = $value->teacher_id;
                            $assigned_slots[$new_index]->course = $value->course_id;
                        }

                    } else {
                        $value->class_end_day = $day->day;

                        if ($final_slot != $time_slot_id) {
                            list($starting_time, $ending_time) = explode("-", $value->t_slot);
                            // get time of final time slot
                            $time_slot_row = $this->get_slot_time($final_slot);
                            list($stime, $end_time) = explode(" - ", $time_slot_row->t_slot);
                            $value->t_slot = $starting_time . " - " . $end_time;
                        }
                    }
                }
            }
        }
        return $assigned_slots;
    }

    function getTimesSlots()
    {
        $slots = DB::table("time_slots")
            ->get();
        return $slots;
    }

    function getTeacherWorkTimings($teacher_id)
    {
        return DB::table("teacher_work_timings as twt")
            ->where("twt.user_id", "=", $teacher_id)
            ->get();
    }

    function teacher_free_slots($teacher_timings, $assigned_slots, $all_slots)
    {
        foreach ($teacher_timings as $timing) {
            $office_start_slot_id = $timing->start_slot_id;
            $office_end_slot_id = $timing->end_slot_id;
            $blank = $office_start_slot_id . "" . $office_end_slot_id;
            if ($blank != "" and $office_start_slot_id != $office_end_slot_id) {
                $this->find__free_slots($office_start_slot_id, $office_end_slot_id, $assigned_slots, $all_slots);
            }
        }
        return $this->free_slots;
    }

    function find__free_slots($start_slot, $end_slot, $already_assigned_slots, $all_slots)
    {
        if ($start_slot < $end_slot) {
            // iterate on all slots
            foreach ($all_slots as $key => $value) {
                if ($value->id >= $start_slot and $value->id <= $end_slot) {
                    // check if this slot is already assigned
                    $this->check_in_array($value, $already_assigned_slots);
                }
            }
        } else {
            // This is from start to end of his slot
            foreach ($all_slots as $key => $value) {
                if ($value->id <= $end_slot) {
                    // check if this slot is already assigned
                    $this->check_in_array($value, $already_assigned_slots);
                }
            }
            // this will work for finding from start to till end of the array
            foreach ($all_slots as $key => $value) {
                if ($value->id >= $start_slot) {
                    // check if this slot is already assigned
                    $this->check_in_array($value, $already_assigned_slots);
                }
            }
        }
    }

    function get_slot_time($final_slot)
    {
        return DB::table("time_slots as ts")
            ->where("ts.id", "=", $final_slot)
            ->first();
    }

    private function check_in_array($value, $already_assigned_slots)
    {
        $week_days = $this->common->getWeekDays();
        foreach ($week_days as $key => $day) {
            if ($this->check_if_already_assigned($value->id, $already_assigned_slots[$key]->assigned_slots) == false) {
                array_push($this->free_slots[$day->day], $value);
            }
        }
    }

    function check_if_already_assigned($time_slot_id, $already_assigned_slots)
    {
        foreach ($already_assigned_slots as $key => $value) {
            if (isset($value->class_start_day)) {
                if ($value->class_start_day == $value->class_end_day and $value->required_slots > 1) {
                    $start_limit = $value->slot_id;
                    $end_limit = $value->slot_id + $value->required_slots - 1;
                    if ($time_slot_id >= $start_limit and $time_slot_id <= $end_limit)
                        return true;
                } else {
                    if ($value->class_start_day != $value->class_end_day and $value->required_slots > 1) {
                        $start_limit = $value->slot_id;
                        $end_limit = 48;
                        if ($time_slot_id >= $start_limit and $time_slot_id <= $end_limit)
                            return true;
                    }
                }
            }
            if ($value->slot_id == $time_slot_id)
                return true;
        }
        return false;
    }

    function checkTeacherForAvailableSlot($timings, $days, $slot_id, $class_detail)
    {
        echo $slot_id;
        // this reschedule class needs slots
        $required_slots = ($class_detail->required_slots);
        if ($required_slots == 1) {
            foreach ($timings[$days] as $key => $value) {
                if ($value->id == $slot_id) {
                    echo "I am here";
                    exit;
                    return false;
                }
            }
        } else {
            $required_set = array();
            $end_limit = ($slot_id + $required_slots - 1);
            if ($end_limit > 48) {
                for ($i = $slot_id; $i <= 48; $i++) {
                    array_push($required_set, $i);
                }
                $end_limit = $end_limit % 48;
                for ($i = 1; $i <= $end_limit; $i++) {
                    array_push($required_set, $i);
                }
            } else {
                for ($i = $slot_id; $i <= $end_limit; $i++) {
                    array_push($required_set, $i);
                }
            }
            $consumes_set = array();
            foreach ($timings[$days] as $key => $value) {
                if (isset($value[$week_day . '_required_slots'])) {
                    $consumes_slots = $value[$week_day . '_required_slots'];
                    $time_slot_id = $value['fk_time_slot_id'];
                    $endlimit = $time_slot_id + $consumes_slots - 1;
                    if ($endlimit > 48) {
                        for ($i = $slot_id; $i <= 48; $i++) {
                            array_push($consumes_set, $i);
                        }
                        $endlimit = $endlimit % 48;
                        for ($i = 1; $i <= $endlimit; $i++) {
                            array_push($consumes_set, $i);
                        }
                    } else {
                        for ($i = $slot_id; $i <= $endlimit; $i++) {
                            array_push($consumes_set, $i);
                        }
                    }
                } else {
                    if ($value->slot_id == $slot_id)
                        return false;
                }
            }
            foreach ($required_set as $key => $value) {
                foreach ($consumes_set as $ckey => $cvalue) {
                    if ($cvalue == $value)
                        return false;
                }
            }
        }
        return true;
    }
}
