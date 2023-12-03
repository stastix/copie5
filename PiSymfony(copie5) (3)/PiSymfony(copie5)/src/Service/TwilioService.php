<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = 'AC5db532fc50d8e3c859ac48f7a83aaa38';
    private $authToken = '758ec5ec485362f1d5e473ee1c667e2e';
    private $twilioPhoneNumber = '+12674934901';
   
    public function sendSMS($to, $body)
    {
        // Créer le client Twilio
        $client = new Client($this->accountSid, $this->authToken);

        // Envoyer le message
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $body,
            ]
        );
    }
}
