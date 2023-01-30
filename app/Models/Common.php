<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Common extends Model
{
    function getWeekDays()
    {
        return DB::table("week_days")
            ->select("id", "day")
            ->get();
    }

    function calculateTimeDifference($current_time, $old_time)
    {
        $last_activity_time = $old_time;
        $diff = $current_time - $last_activity_time;
        if ($days = intval((floor($diff / 86400))))
            $diff = $diff % 86400;
        if ($hours = intval((floor($diff / 3600))))
            $diff = $diff % 3600;
        if ($minutes = intval((floor($diff / 60))))
            $diff = $diff % 60;
        $diff = intval($diff);
        $detail = array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $diff);
        return $detail;
    }

    function getSlotTime($slot_id)
    {
        return DB::table("time_slots")
            ->where("id", "=", $slot_id)
            ->first();
    }

    function getStatusColors()
    {
        return array(
            'is_class_in_progress' => array(
                'color' => '#FFCCCC',
                'icon' => 'progress.gif'
            ),
            'class_started' => array(
                'color' => '#00FF00',
                'icon' => 'class_time_started.png'
            ),
            'near_to_start' => array(
                'color' => '#FFFF00',
                'icon' => 'about-to.png'
            ),
            'class_is_missed' => array(
                'color' => '#CCCCCC',
                'icon' => 'class_missed.png'),
            'still_have_time' => array(
                'color' => '#FFFFFF',
                'icon' => 'still_have_time.png'),
            'class_is_taken' => array(
                'color' => '#FF9900',
                'icon' => 'class_taken.png'),
            'class_close_status' => array(
                'color' => '#FF0000',
                'icon' => 'angry_face.png')
        );
    }

}
