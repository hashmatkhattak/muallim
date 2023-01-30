<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentClassesController extends Controller
{
    function my_student_classes(Request $request)
    {
        $data = array();
        try {
            $data = $request->all();
            $data['type'] = isset($data['type']) ? $data['type'] : "1";
            $data['time_slots'] = DB::table("time_slots as ts")
                ->select("ts.id", "ts.t_slot")
                ->get();
            return view("my_classes.my-classes", $data);
        } catch (Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }


}
