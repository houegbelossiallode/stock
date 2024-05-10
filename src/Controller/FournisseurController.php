<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FourniType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_fournisseur')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Fournisseur::class);
        $fournisseurs = $repository->findAll();
        return $this->render('fournisseur/index.html.twig', [
       'fournisseurs' => $fournisseurs,
   
       ]);
    }



    #[Route('/fournisseur/add', name: 'add_fournisseur')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        
    $fournisseur = new Fournisseur();   
    $form = $this->createForm(FourniType::class,$fournisseur);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {

        $manager = $doctrine->getManager();
        $manager->persist($fournisseur);
        $manager->flush();
        $this->addFlash("error", "a été ajouté avec succès" );
        return $this->redirectToRoute('app_fournisseur');
        
    }
    
    else{
        
        return $this->render('fournisseur/new.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
    
}









    
}