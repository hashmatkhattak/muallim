<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    function getCourses()
    {
        return Course::query()
            ->select("id", "course_title", "status", "course_type")
            ->where("status", "=", "1")
            ->get();
    }

    function getCourseType($course_id)
    {
        $type = "getWeekDaysLectures";
        $type = "getWeekDaysClasses";
        if ($course_id != "") {
            $course = new Course();
            $course_detail = $course->getCourse($course_id);
            if (!empty($course_detail) && isset($course_detail->course_type) && $course_detail->course_type == '1') {
                $type = "getWeekDaysClasses";
            }
        }
        return $type;
    }

    function getCourse($course_id)
    {
        return Course::query()
            ->select("id", "course_title", "status", "course_type")
            ->where("id", "=", $course_id)
            ->where("status", "=", "1")
            ->first();
    }
}
