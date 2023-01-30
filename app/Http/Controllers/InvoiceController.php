<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceTemplates;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    function invoice_settings()
    {
        return view("invoice.invoice_settings");
    }

    function add_invoice()
    {
        return view("invoice.add_invoice");
    }

    function invoice_templates()
    {
        $data['invoice_templates'] = DB::table("invoice_templates")
            ->select("*")
            ->where("status", "!=", "2")
            ->orderBy("id", "DESC")
            ->get();
        return view("invoice.invoice_templates", $data);
    }

    function add_invoice_submitted(Request $request)
    {
        try {
            $data = $request->all();
            $request->validate([
                'key' => 'required',
                'mesg_subject' => 'required',
                'mesg_body' => 'required'
            ]);
            //---------------------------------------------------------------------------------------------
            $invoiceTemplates = new InvoiceTemplates();
            $invoiceTemplates->company_id = 1;
            $invoiceTemplates->key = $data['key'];
            $invoiceTemplates->mesg_subject = $data['mesg_subject'];
            $invoiceTemplates->mesg_body = $data['mesg_body'];
            $invoiceTemplates->status = '1';
            $invoiceTemplates->save();
            return redirect(route('invoice_templates'))->with('success', "Invoice Templates added successfully..!");
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    function edit_invoice()
    {
        $tid = $_GET['tid'];
        $invoiceTemplates = InvoiceTemplates::select('*')
            ->where("id", "=", $tid)
            ->first();

        $data['invoiceTemplates'] = $invoiceTemplates;
        return view('invoice/edit_invoice', $data);
    }

    function edit_invoice_submitted(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'key' => 'required',
            'mesg_subject' => 'required',
            'mesg_body' => 'required'
        ]);

        $tid = $data['tid'];

        $invoiceTemplates = InvoiceTemplates::select('*')
            ->where("id", "=", $tid)
            ->first();

        $invoiceTemplates->company_id = 1;
        $invoiceTemplates->key = $data['key'];
        $invoiceTemplates->mesg_subject = $data['mesg_subject'];
        $invoiceTemplates->mesg_body = $data['mesg_body'];
        $invoiceTemplates->status = '1';
        $invoiceTemplates->save();
        return redirect(route('invoice_templates'))->with('success', "Invoice templates updated successfully..!");
    }

    function change_invoice_status(Request $request)
    {
        $id = $request->tid;
        $status = $request->status;
        $invoiceTemplates = InvoiceTemplates::select("*")
            ->where("id", "=", $id)
            ->first();
        //print_r($course);exit;
        if ($status != $invoiceTemplates->status && ($status == '0' || $status == '1' || $status == '2')) {
            $invoiceTemplates->status = $status;
            $invoiceTemplates->save();
            if ($status == 2) {
                return back()->with('success', 'Templates Deleted successfully');
            } else if ($status == 1) {
                return back()->with('success', 'Templates Activated successfully');
            } else if ($status == 0) {
                return back()->with('success', 'Templates  Deactivated Successfully');
            }
        }
        return back()->with('error', 'oops..! something went wrong');
    }

    //------------------------------------------------------------------------------

    function all_invoices()
    {
        $data['invoices'] = DB::table("invoices as inv")
            ->selectRaw("inv.*,d.first_name,d.last_name")
            ->join('user_details as d', 'd.user_id', '=', 'inv.user_id')
            ->orderBy("inv.id", "DESC")
            ->get();
        return view("invoice.client_invoice", $data);
    }

    function send_invoice()
    {
        return view("invoice.send_invoice");
    }

    function send_invoice_submitted(Request $request)
    {

        $data = $request->all();
        $request->validate([
            'user_id' => 'required',
            'type' => 'required',
            'payment_method' => 'required',
            'due_date' => 'required'
        ]);

        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < 6; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }

        $invoice = new Invoice();
        $invoice->company_id = 1;
        $invoice->invoice_number = $token;
        $invoice->user_id = $data['user_id'];
        $invoice->type = $data['type'];
        $invoice->subject = $data['subject'];
        $invoice->description = $data['description'];
        $invoice->currency = $data['currency'];
        $invoice->due_date = $data['due_date'];
        $invoice->save();

        $invoice_template = InvoiceTemplates::select('*')
            ->where("key", "=", 'invs_notification')
            ->where("company_id", "=", '1')
            ->first();
        // $mesg_body = $invoice_template->mesg_body;
        //$message = str_replace('[username]', $username, $message_body);
        // $content = str_replace('[useractivationlink]', $activation_link, $message);
        return redirect(route('all_invoices'))->with('success', "Invoice send successfully..!");
    }

    //-------------------------------------------------------------------------------------------------------

    function parent_invoices()
    {
        $info = Session::get("isLogin");
        $data['invoices'] = DB::table("invoices as inv")
            ->selectRaw("inv.*")
            ->where("inv.user_id", "=", $info->user_id)
            ->orderBy("inv.id", "DESC")
            ->get();
        return view("parent.invoices", $data);
    }

    function complaints()
    {
        return view("parent.complaints");
    }
}
