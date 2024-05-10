<?php

namespace App\Controller;

use App\Services\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    #[Route('/sms', name: 'app_sms')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig', [
            'smsSent' => 'false',
        ]);
    }


    #[Route('/sendSms', name: 'send_sms')]
    public function sendSms(Request $request , SmsGenerator $smsGenerator): Response
    {

      $number=$request->request->get('number');
      $name=$request->request->get('name');
      $text=$request->request->get('text');
      $number_test=$_ENV['twilio_to_number'];

      $smsGenerator->sendSms($number_test, $name , $text);

        
        return $this->render('sms/index.html.twig', [
            'smsSent' => 'true'
        ]);
    }


    
}