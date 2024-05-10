<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\SearchType;
use App\Repository\ClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Client::class);
        $clients = $repository->findAll();
      
       return $this->render('client/index.html.twig', [
       'clients' => $clients,
   
       ]);
    }


    #[Route('/client/add', name: 'add_client')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        
    $client = new Client();   
    $form = $this->createForm(ClientType::class,$client);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {

        $manager = $doctrine->getManager();
        $manager->persist($client);
        $manager->flush();
        $this->addFlash("error", "a été ajouté avec succès" );
        return $this->redirectToRoute('app_client');
        
    }
    
    else{
        
        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
    
}


#[Route('/client/delete/{id}', name: 'client_delete')]
public function delete(Request $request,ManagerRegistry $doctrine, int $id): Response
{
    $client = new Client();
    $client = $doctrine->getRepository(Client::class)->find($id);

    if($client)
    {
        $manager = $doctrine->getManager();
        $manager->remove($client);
        $manager->flush();
        $this->addFlash("success","Suppression réussi");
        
        return $this->redirectToRoute('app_client');
       
    }else{
        $this->addFlash('error','Plat inexistant');
    }

      return $this->redirectToRoute('app_client');
}



#[Route('/client/edit/{id}', name: 'client_edit')]
public function edit(Request $request,ManagerRegistry $doctrine,$id)
{
    
    $client = new Client(); 
    $client = $doctrine->getRepository(Client::class)->find($id);  
    $form = $this->createForm(ClientType::class,$client);
    $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid())
   {
      
       $manager = $doctrine->getManager();
       $manager->persist($client);
       $manager->flush();
       return $this->redirectToRoute('app_client');
   }
   return $this->render('client/edit.html.twig',array(
       'form'=>$form->createView(),
       
   ));
}


#[Route('/client/search', name: 'app_search_client')]
public function search(ManagerRegistry $doctrine,Request $request,ClientRepository $clientRepository): Response
{
       $donnees = [];
       $form = $this->createForm(SearchType::class);
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
        
        $nom = $form->getData();
        
        $donnees = $clientRepository->search($nom);
        
        
       }
   return $this->render('client/search.html.twig', [
    'form'=> $form->createView(),
    'donnees'=> $donnees,
   ]);
}




















}