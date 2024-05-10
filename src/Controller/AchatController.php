<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Form\AchatType;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AchatController extends AbstractController
{
    #[Route('/achat', name: 'app_achat')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $achat = new Achat(); 
        //dd($achat1 = new DateTimeImmutable('2013-01-29'));
        //$date = $achat->setCreatedAt('+1 day');
        // dd($achat->setDate(new \DateTimeImmutable('now + 4 day')))->date_format('d-m-Y') ;
        $repository = $doctrine->getRepository(Achat::class);
        $achats = $repository->findAll();
        return $this->render('achat/index.html.twig', [
            'achats'=>$achats
        ]);
    }




    #[Route('/achat/add', name: 'add_achat')]
    public function add(Request $request,ManagerRegistry $doctrine): Response
    {
        $achat = new Achat();   
        $form = $this->createForm(AchatType::class,$achat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            
            $manager = $doctrine->getManager();
            $manager->persist($achat);
            $manager->flush();
            $this->addFlash("error", "a été ajouté avec succès" );
            return $this->redirectToRoute('app_achat');
            
        }
        
        else{
            
            return $this->render('achat/new.html.twig', [
                'form' => $form->createView(),
            ]);
            
        }
    }



    
}