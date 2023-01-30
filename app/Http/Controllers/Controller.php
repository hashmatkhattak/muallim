<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $message;
    public $status = 0;
    public $data = null;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sendResponse()
    {
        return json_encode(['status' => $this->status, "message" => $this->message]);
    }


    public function sendApiResponse()
    {
        if ($this->status == 1) {
            return json_encode(
                [
                    'status' => $this->status,
                    "message" => $this->message,
                    "data" => $this->data
                ]
            );
        } else {
            return json_encode(
                [
                    'status' => $this->status,
                    "message" => $this->message
                ]
            );
        }
    }
}
