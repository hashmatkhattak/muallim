<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classes extends Model
{
    function teacher()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'teacher_id')
            ->select("user_id", "first_name", "last_name");
    }

    function student()
    {
        return $this->hasOne(Student::class, 'id', 'student_id')
            ->select("id", "student_name");
    }

    function classTime()
    {
        return $this->hasOne(TimeSlots::class, 'id', 'slot_id')
            ->select("id", "t_slot");
    }

    function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')
            ->select("id", "course_title");
    }

    function coursesLessons()
    {
        return $this->hasOne(CoursesLessons::class, 'id', 'lesson_id')
            ->select("id", "lesson_label");
    }

    function getClassesBaseSettings($day)
    {

        $day_id = $this->getDayId($day);
        $settings = DB::table("student_class_days as scd")
            ->select("scd.day_id", "st.course_id", "st.teacher_id", "st.student_id", "scd.student_teacher_id", "scd.slot_id", "scd.slot_id", "scd.required_slots", "ts.t_slot")
            ->join("student_teacher as st", "st.id", "scd.student_teacher_id")
            ->join("time_slots as ts", "ts.id", "scd.slot_id")
            ->where("scd.day_id", $day_id)
            ->where("st.end_date", null)
            ->get();
        return $settings;
    }

    function getDayId($day)
    {
        $day_id = "";
        switch ($day) {
            case 'Monday':
                $day_id = 1;
                break;
            case 'Tuesday':
                $day_id = 2;
                break;
            case 'Wednesday':
                $day_id = 3;
                break;
            case 'Thursday':
                $day_id = 4;
                break;
            case 'Friday':
                $day_id = 5;
                break;
            case 'Saturday':
                $day_id = 6;
                break;
            case 'Sunday':
                $day_id = 7;
                break;
            default:
        }
        return $day_id;
    }

    // Configuration of classes based on student classes days

    function configureSchedule($base_settings, $class_date)
    {
        foreach ($base_settings as $key => $value) {
            $student_id = $value->student_id;
            $teacher_id = $value->teacher_id;
            $slot_id = $value->slot_id;
            $required_slots = $value->required_slots;
            $course_id = $value->course_id;
            $student_teacher_id = $value->student_teacher_id;

            // 1 = Regular 2 = Rescheduled 3 = Make-up 4 = Additional
            $rescheduled = DB::table("classes as c")
                ->select("*")
                ->where("c.student_teacher_id", "=", $student_teacher_id)
                ->where("c.class_date", "=", $class_date)
                ->where("c.class_type", "!=", '3')
                ->where("c.class_type", "!=", '4')
                ->count();

            $original = DB::table("classes as c")
                ->select("*")
                ->where("c.student_teacher_id", "=", $student_teacher_id)
                ->where("c.original_date", "=", $class_date)
                ->where("c.class_type", "=", '2')
                ->where("c.class_type", "=", '3')
                ->count();

            if ($rescheduled <= 1 && $original <= 1) {
                $is_exists = DB::table("classes as c")
                    ->select("*")
                    ->where("c.teacher_id", "=", $teacher_id)
                    ->where("c.student_id", "=", $student_id)
                    ->where("c.slot_id", "=", $slot_id)
                    ->where("c.class_date", "=", $class_date)
                    ->count();
                if ($is_exists < 1) {
                    $scheduled = new Classes();
                    $scheduled->teacher_id = $teacher_id;
                    $scheduled->student_id = $student_id;
                    $scheduled->slot_id = $slot_id;
                    $scheduled->required_slots = $required_slots;
                    $scheduled->course_id = $course_id;
                    $scheduled->class_date = $class_date;
                    $scheduled->student_teacher_id = $student_teacher_id;
                    $scheduled->save();
                }
            }
        }

    }

    function adjust_class_timings($all_classes)
    {
        $common = new Common();
        $i = 0;
        foreach ($all_classes as $key => $value) {
            $slot_id = $value->slot_id;
            $required_slots = $value->required_slots;
            $final_slot = $slot_id + $required_slots - 1;
            if ($final_slot > 48)
                $final_slot = $final_slot % 48;
            $time_slot_array = $common->getSlotTime($final_slot);
            list($start_time, $crap) = explode(" - ", $value->classTime->t_slot);
            list($crap, $end_time) = explode(" - ", $time_slot_array->t_slot);
            $all_classes[$i]->classTime->t_slot = $start_time . " - " . $end_time;
            $i++;
        }
        return $all_classes;
    }

    function setBackgroundColours($all_classes, $date, $time_zone)
    {
        echo $time_zone;
        $common = new Common();
        $colors = $common->getStatusColors();
        foreach ($all_classes as $key => $cls) {
            //Current Time in UTC
            $current = date('Y-m-d h:i:s A');
            $dt = new DateTime($current, new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone($time_zone));
            //Conversion of UTC time to local time;
            $current_date_time = $dt->format('Y-m-d h:i:s A');
            //echo $current_date_time;
            $local_current_time = strtotime($current_date_time);
            list($cls_start, $cls_end) = explode("-", $cls->classTime->t_slot);
            //Class start & end time
            $cls_start_time = $date . " " . $cls_start;
            $cls_end_time = $date . " " . $cls_end;
            //Class start & end time in milliseconds
            $cls_start_range = strtotime(trim($cls_start_time));
            $cls_end_range = strtotime(trim($cls_end_time));

            //Code for 30 Mints Classes
            switch ($cls->required_slots) {
                case 1:
                    // class in progress
                    if ($cls->status == 1) {
                        if ($local_current_time >= $cls_start_range and $current < $cls_end_range) {
                            $cls->background = $colors['is_class_in_progress']['color'];
                            $cls->icon = $colors['is_class_in_progress']['icon'];
                            $cls->cls_status = "InProgress";
                            $cls->edit_link = 1;
                            $cls->start_link = 0;
                        } else {
                            $cls->background = $colors['class_close_status']['color'];
                            $cls->icon = $colors['class_close_status']['icon'];
                            $cls->cls_status = "Close";
                            $cls->edit_link = 1;
                            $cls->start_link = 0;
                        }
                        // class taken
                    } else if ($cls->status == 2) {
                        $cls->background = $colors['class_is_taken']['color'];
                        $cls->icon = $colors['class_is_taken']['icon'];
                        $cls->cls_status = "Class is Taken";
                        $arr = $common->calculateTimeDifference(strtotime($cls->start_time), strtotime($cls->end_time));
                        $cls->class_time = $arr['hours'] . ':' . $arr['minutes'] . ":" . $arr['seconds'];
                        $cls->show_start_link = 0;
                        $cls->show_edit_link = 0;
                        // it mean still have time in class or class has been missed etc
                    } else {
                        $demand_date = strtotime(trim($date));
                        $today_date = strtotime((date('Y-m-d')));
                        if ($local_current_time >= $cls_start_range and $local_current_time < $cls_end_range and $demand_date == $today_date) {
                            $cls->background = $colors['class_started']['color'];
                            $cls->icon = $colors['class_started']['icon'];
                            $cls->cls_status = "Ready to start";
                            $cls->edit_link = 0;
                            $cls->start_link = 1;
                        } else {
                            if ($local_current_time >= $cls_end_range and ($demand_date <= $today_date)) {
                                $cls->background = $colors['class_is_missed']['color'];
                                $cls->icon = $colors['class_is_missed']['icon'];
                                $cls->cls_status = "Class is Missed";
                                $cls->edit_link = 0;
                                $cls->start_link = 0;
                            } else {
//                                echo "cls_start_time = ".$cls_start_time."<br>";
//                                echo "cls_end_time = ".$cls_end_time."<br>";
//                                echo "Local_time = ".$current_date_time."<br>";
                                $arr = $common->calculateTimeDifference($cls_start_range, $local_current_time);
                                // print_r($arr);exit;
                                if ($arr['days'] > 0 or $arr['hours'] > 0 or $arr['minutes'] > 5) {
                                    $cls->background = $colors['still_have_time']['color'];
                                    $cls->icon = $colors['still_have_time']['icon'];
                                    $cls->cls_status = "Still have time";
                                    $cls->edit_link = 0;
                                    $cls->start_link = 0;
                                    // class is about to start
                                } else if ($arr['hours'] <= 0 and $arr['minutes'] <= 5) {
                                    $cls->background = $colors['class_started']['color'];
                                    $cls->icon = $colors['class_started']['icon'];
                                    $cls->cls_status = "Ready to start";
                                    $cls->edit_link = 0;
                                    $cls->start_link = 1;
                                }
                            }
                        }
                    }
                    break;
                case 2:

                    break;
                default:
            }
        }
        return $all_classes;
    }

    function getClassTimeDetails($class_id)
    {
        $details = DB::table("classes as c")
            ->select("c.id", "slot_id", "required_slots")
            ->where("c.id", "=", $class_id)
            ->first();
        return $details;
    }

    function getAllTeachers()
    {
        $teachers = User::query()
            ->select("id", "email")
            ->where("role_id", "=", 4)
            ->get();
        return $teachers;
    }


    function studentCounts($teacher_id)
    {

    }
}
