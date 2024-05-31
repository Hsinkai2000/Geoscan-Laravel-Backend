<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function callback(Request $request)
    {
        $postData = $request->all();
        if (isset($postData['SmsSid']) && isset($postData['SmsStatus'])) {
            $TwilioSMSsid = $postData['SmsSid'];
            $TwilioSMSstatus = 'Twilio ' . $postData['SmsStatus'];

            DB::table('alert_logs')->where('sms_messageId', $TwilioSMSsid)->update([
                'sms_status_updated' => date("Y-m-d H:i:s"),
                'sms_status' => $TwilioSMSstatus,
            ]);

            return response('{"Success"}', 200);
        } else {
            return response('{"error":"Missing parameters in the request"}', 400);
        }
    }
}