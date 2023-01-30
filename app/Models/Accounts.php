<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Accounts extends Model
{
    protected $table = "accounts";

    function get_invoices_settings($day, $company_id)
    {
        return DB::table("invoice_settings")
            ->where("key", "=", $day)
            ->where("company_id", "=", $company_id)
            ->first();
    }

    function get_all_customers($company_id)
    {
        return DB::table("users")
            ->select("*")
            ->where("company_id", "=", $company_id)
            ->where("u.type", "=", "4")
            ->where("c.status", "=", "1")
            ->get();
    }

    function get_invoice_sending_details($account_id)
    {
        // 1=> Monthly 2=> Instant
        return DB::table("invoice_sending_details as isd")
            ->select("isd.*")
            ->where("account_id", "=", $account_id)
            ->where("invoice_type", "=", "1")
            ->where("sending_date", "=", date('Y-m-d'))
            ->first();
        //MONTH(sending_date) = '".date('m') ."' and
        //YEAR(sending_date) = '".date('Y') ."'" ;
    }
}
