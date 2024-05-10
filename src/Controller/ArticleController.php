<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AjoutType;
use App\Form\SearchType;
use App\Form\ArticleType;
use App\Entity\QuantiteAjoute;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Article::class);
        $articles = $repository->findAll();
        
        
    

       return $this->render('article/index.html.twig', [
       'articles' => $articles,
   
       ]);
    }


    #[Route('/article/add', name: 'add_article')]
    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        
    $rand = chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). sprintf('%03d', mt_rand(0,999));
    $article = new Article();   
    $form = $this->createForm(ArticleType::class,$article);
    $form->remove('code');
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {


      
        $image = $form->get('image')->getData();

        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move(
                    $this->getParameter('article_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $article->setImage($newFilename);
        }

        $article->setCode($rand);
        $manager = $doctrine->getManager();
        $manager->persist($article);
        $manager->flush();
        $this->addFlash("error", "a été ajouté avec succès" );
        return $this->redirectToRoute('app_article');
        
    }
    
    else{
        
        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
    
}



#[Route('/quantite/{id}/add', name: 'add_quantite')]
public function ajoutquantite(int $id,ManagerRegistry $doctrine, Request $request,ArticleRepository $articleRepository): Response
{
   
    $article = $articleRepository->find($id); 
    $manager = $doctrine->getManager();
    $mot = $request->get('rejet');
    $article->setQuantite($article->getQuantite() + (float)$mot);
    $manager->persist($article);
    $manager->flush();

    $quantite = new QuantiteAjoute();
    $article = $articleRepository->find($id); 
    $manager = $doctrine->getManager();
    $quantite->setArticle($article);
    $quantite->setQuantite($mot);
    $manager->persist($quantite);
    $manager->flush();
    
    return $this->redirectToRoute('app_article');
}





#[Route('/article/delete/{id}', name: 'article_delete')]
public function delete(Request $request,ManagerRegistry $doctrine, int $id): Response
{
    $article = new Article();
    $article = $doctrine->getRepository(Article::class)->find($id);

    if($article)
    {
        $manager = $doctrine->getManager();
        $manager->remove($article);
        $manager->flush();
        $this->addFlash("success","Suppression réussi");
        
        return $this->redirectToRoute('app_article');
       
    }else{
        $this->addFlash('error','Plat inexistant');
    }

      return $this->redirectToRoute('app_article');
}



#[Route('/article/edit/{id}', name: 'article_edit')]
public function edit(Request $request,ManagerRegistry $doctrine,$id,SluggerInterface $slugger)
{
    
   $article = new Article();
   $article = $doctrine->getRepository(Article::class)->find($id);
   $form = $this->createForm(ArticleType::class, $article);
   $form->remove('code');
   
   $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid())
   {


    $image = $form->get('image')->getData();

    // this condition is needed because the 'brochure' field is not required
    // so the PDF file must be processed only when a file is uploaded
    if ($image) {
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $this->getParameter('article_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        // updates the 'brochureFilename' property to store the PDF file name
        // instead of its contents
        $article->setImage($newFilename);
    }

    
    
      
       $manager = $doctrine->getManager();
       $manager->persist($article);
       $manager->flush();
       return $this->redirectToRoute('app_article');
   }
   return $this->render('article/edit.html.twig',array(
       'form'=>$form->createView(),
       
   ));
}









#[Route('/article/search', name: 'app_search')]
public function search(ManagerRegistry $doctrine,Request $request,ArticleRepository $articleRepository): Response
{
       $donnees = [];
       $form = $this->createForm(SearchType::class);
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
        
        $nom = $form->getData();
      
        $donnees = $articleRepository->search($nom);
        
        
       }
   return $this->render('article/search.html.twig', [
    'form'=> $form->createView(),
    'donnees'=> $donnees,
   ]);
}


      

    







    
}