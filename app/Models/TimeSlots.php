<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TimeSlots extends Model
{
    protected $table = "time_slots";

    function getTimeSlots()
    {
        return DB::table($this->table)
            ->get();
    }
}
