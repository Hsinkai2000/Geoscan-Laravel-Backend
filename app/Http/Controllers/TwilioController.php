<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use Illuminate\Http\Request;

class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function callback(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        $this->twilio->sendMessage($to, $message);

        return response()->json(['message' => 'SMS sent successfully']);
    }
}