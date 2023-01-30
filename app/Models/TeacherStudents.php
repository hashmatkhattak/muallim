<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherStudents extends Model
{
    private $thr_free_slots = "";
    protected $table = "student_teacher";

    public function __construct(array $attributes = [])
    {
        $this->thr_free_slots = new TeacherFreeSlots();
    }

    function getTeacherStudents($teacher_id, $course_id, $type)
    {

        $sql = DB::table("student_teacher as st")
            ->select(
                "st.id as teacher_student_id", "d.user_id", "d.first_name", "st.teacher_id", "st.student_id",
                "st.slot_id", "st.course_id", "c.course_title", "std.student_name", "d.first_name", "d.last_name"
            )
            ->join("students as std", "std.id", "st.student_id")
            ->join("user_details as d", "d.user_id", "std.parent_id")
            ->leftJoin("courses as c", "c.id", "st.course_id");
        if ($teacher_id != '') {
            $sql->where("st.teacher_id", "=", $teacher_id);
        }
        if ($course_id != '') {
            $sql->where("st.course_id", "=", $course_id);
        }
        $sql->where("st.end_date", "=", null);
        $teacher_students = $sql->get();
        foreach ($teacher_students as $key => $value) {
            $week_days = DB::table("week_days as wd")
                ->select("id", "day")
                ->get();
            $value->slot_id = '';
            $value->required_slots = '';
            foreach ($week_days as $day) {
                $schedule = $this->getStudentClassDays($value->course_id, $value->teacher_student_id, $day->id);
                $day->t_slot = '';
                if (!empty($schedule)) {
                    $value->slot_id = $schedule->slot_id;
                    $value->required_slots = $schedule->required_slots;
                    $this->$type($value->student_id, $value->slot_id, $day, $day->day, $value->required_slots);
                }
            }
            $value->week_days = $week_days;
        }
        return $teacher_students;
    }

    function getWeekDaysClasses($student_id, $start_slot_id, $index_day, $day, $required_slots)
    {
        if ($start_slot_id == 0) {
            return 0;
        }
        $final_slot = $start_slot_id + $required_slots - 1;
        $index_day->class_start_day = $day;
        // 48 are total slots. And 48 is the last slot .. slot mean half an hr time (12:00 AM - 12:30 AM)
        if ($final_slot > 48) {
            $endingSlot = $final_slot % 48;
            $current_index = $this->week_days_reverse_index[$day];
            $new_index = ($current_index + 1) % 7;
            $day = $this->numbered_week_days[$new_index];
            $index_day->class_end_day = $day;
            $star_arr = $this->thr_free_slots->get_slot_time($start_slot_id);
            // print_r($star_arr);exit;
            list($starting_time, $ending_time) = explode(" - ", $star_arr->t_slot);
            // get time of final time slot
            $time_slot_row = $this->thr_free_slots->get_slot_time($endingSlot);
            list($stime, $end_time) = explode(" - ", $time_slot_row->t_slot);
            $index_day->t_slot = $starting_time . " - " . $end_time;
        } else {
            $index_day->class_end_day = $day;
            $index_day->required_slots = $required_slots;
            $star_arr = $this->thr_free_slots->get_slot_time($start_slot_id);
            if ($final_slot != $start_slot_id) {
                list($starting_time, $ending_time) = explode(" - ", $star_arr->t_slot);
                // get time of final time slot
                $time_slot_row = $this->thr_free_slots->get_slot_time($final_slot);
                list($stime, $end_time) = explode(" - ", $time_slot_row->t_slot);
                $index_day->t_slot = $starting_time . " - " . $end_time;
            } else {
                $index_day->t_slot = $star_arr->t_slot;
            }
        }
        return true;
    }

    function getStudentClassDays($course_id, $teacher_student_id, $day_id)
    {
        return DB::table("student_class_days as scd")
            ->select("scd.day_id", "scd.slot_id", "scd.required_slots")
            ->where("scd.student_teacher_id", "=", $teacher_student_id)
            ->where("scd.day_id", "=", $day_id)
            ->first();
    }
}
