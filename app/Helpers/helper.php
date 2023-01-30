<?php
function allStatuses()
{
    return ['DeActivated', 'Activate', 'Delete'];
}

function getStatus($status)
{
    $all = allStatuses();
    return $all[$status];
}

function classTypes()
{
    // 1 = Regular 2 = Rescheduled 3 = Make-up 4 = Make-up
    return array("", "Regular", "Rescheduled", "Make-up", "Make-up");
}

function getClassType($type)
{
    $all = classTypes();
    return $all[$type];
}

function classStatuses()
{
    //  1- class in progress, 0- means class has not been yet started, 2- means class taken
    return array("Not Yet started", "In progress", "Completed");
}

function getClassStatus($status)
{
    $all = classStatuses();
    return $all[$status];
}

function cls_status2($status,$slot)
{
    $return_arr = array();
    $cls_status = 'cls_not_started';
    if($status == 1)
        $cls_status = 'cls_in_progress';
    if($status == 2)
        $cls_status = 'cls_completed';
    if($status == 3)
        $cls_status = 'cls_missed';

    $current_time = date('H:i:s');
    $current_time = date('h:i A', strtotime($current_time));

    $t_slot_arr = explode('-', $slot);
    if(isset($t_slot_arr[0]) AND !empty($t_slot_arr))
        $class_time = strtotime($t_slot_arr[0]);

    $class_time = '07:00 AM';
    $current_time = '07:09 AM';

    $before_cls_seconds  = strtotime($class_time) - strtotime($current_time);
    $before_cls_minutes = floor($before_cls_seconds / 60);

    $after_cls_seconds  = strtotime($current_time) - strtotime($class_time);
    $after_cls_minutes = floor($after_cls_seconds / 60);

    $class_start = false;
    if(($before_cls_minutes > 0 AND $before_cls_minutes <=5) || ($after_cls_minutes > 0 AND $after_cls_minutes <=10)) {
        $class_start = true;
    }
    $return_arr['cls_status'] = $cls_status;
    $return_arr['class_start'] = $class_start;
    return $return_arr;
}
