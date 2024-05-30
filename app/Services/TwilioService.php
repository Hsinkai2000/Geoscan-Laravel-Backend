<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendMessage($to, $message)
    {
        return $this->twilio->messages->create($to, [
            'from' => config('services.twilio.from'),
            'body' => $message,
            'riskCheck' => 'disable',
            'statusCallback' => config('services.twilio.callback_url'),
        ]);
    }

}