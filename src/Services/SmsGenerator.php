<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsGenerator
{
 


public function SendSms(string $number, string $name, string $text)
{

    $accountSid = $_ENV['twilio_account_sid'];
    $authToken = $_ENV['twilio_auth_token'];
    $fromNumber = $_ENV['twilio_from_number'];

    $toNumber = $number;
    $message = ''. $name. 'vous a envoyé le message suivant : ' . ' ' .$text. '';

    $client = new Client($accountSid, $authToken);
    
    $client->messages->create(
       $toNumber,
       [
        
        'from'=> $fromNumber,
        'body'=> $message,
        
       ]

        
       );








    
}



    
}