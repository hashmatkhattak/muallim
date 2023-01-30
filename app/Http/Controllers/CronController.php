<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Exception;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    function send_invoices()
    {
        try {
            $companies = DB::table("companies as c")
                ->select("c.id", "c.company_name", "c.email")
                ->where("status", "=", "1")
                ->get();
            if (!empty($companies)) {
                foreach ($companies as $company) {
                    $this->send_monthly_invoices($company->id)
                }
            }
        } catch (Exception $exception) {
            $this->status = 0;
            $this->message = $exception->getMessage();
            return $this->sendResponse();
        }
    }

    function send_monthly_invoices($company_id)
    {
        // here we need to load the setting values first so that we can check whether invoices should be sent or not.
        $account = new Accounts();
        $settings = $account->get_invoices_settings('invoice_sending_day', $company_id);
        $today = date('d');
        if ($today == $settings->value) {
            // select all customers who are active and having template invoices in system
            $customers = $account->get_all_customers($company_id);
            foreach ($customers as $customer) {
                $sending_details = $account->get_invoice_sending_details($customer->account_id);
                // it means the invoice has not yet been sent
                if (!empty($sending_details)) {
                    // load the invoice for the customer
                    // 1=> Monthly 2=> Instant
                    $invoice_id = $this->load_customer_template_invoice($customer->account_id, 1);
                    if (strlen($invoice_id) != 0)
                        // create a copy and send them to the customer
                        create_invoice_and_send_email($invoice_id, 'monthly', NULL, $company_id);
                }
            }
        }
    }

    function load_customer_template_invoice()
    {

    }
}
