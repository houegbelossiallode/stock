<?php

namespace App\Controller;

use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BuilderInterface $qrBuilder): Response
    {
        $rand = chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). sprintf('%04d', mt_rand(0,999));
        $qrResult = $qrBuilder
                ->size(150)
                ->margin(20)
                ->data($rand)
                ->labelText('QR CODE')
                ->build();
                $base64 = $qrResult->getDataUri();
                
                return $this->render('home/index.html.twig', [
                  'base64'=> $base64
                
                ]);

    }

                  #[Route('/accueil', name: 'aleatoire')]
                  public function generate(): Response
                  {
                    $rand = chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). sprintf('%04d', mt_rand(0,999));
                    
                    return $this->render('home/new.html.twig',[
                      'value'=> $rand
                    ]);
                  }


                 
                 





    
}