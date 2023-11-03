<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }



    #[Route('/fetch', name:'fetch')]
    public function show(AuthorRepository $repo): Response
    {
        $result= $repo->listAuthorByEmail();
        return $this->render('author/show.html.twig', [ 'response' => $result]);
    }



    #[Route('/add', name:'add')]
    public function add(ManagerRegistry $mr): Response
    {
        
       $a=new Author();
       $a->setUsername('test');
       $a->setEmail('test@gmail.com');
        $em=$mr->getManager();
        $em->persist($a); 
        $em->flush();
        return $this->redirectToRoute('fetch');
    }


    #[Route('/addF', name:'addF')]
    public function addF(ManagerRegistry $mr, Request $req): Response
    {
      
       $auth=new Author();
   
        $form=$this->createForm( AuthorType::class, $auth);
        $form->handleRequest($req);


    if ($form->isSubmitted() ){
        $em=$mr->getManager();
        $em->persist($auth); 
        $em->flush();
       
         return $this->redirectToRoute('fetch');}
        return $this->render('author/add.html.twig', [
            'f'=>$form->createView()]);
    }


    #[Route('/remove/{id}', name:'remove')]
    public function remove(int $id, AuthorRepository $repo, ManagerRegistry $mr): Response
    {
       $auth=$repo->find($id);
       
       $em=$mr->getManager();
        $em->remove($auth);
        $em->flush();
        return $this->redirectToRoute('fetch');
    }


    #[Route('/updateF/{id}', name:'updateF')]
    public function updateF(ManagerRegistry $mr,AuthorRepository $repo, Request $req, int $id): Response
    {
        $em=$mr->getManager();
          $auth=$repo->find($id);
        $form=$this->createForm( AuthorType::class, $auth);
        $form->handleRequest($req);


    if ($form->isSubmitted() && $form->isValid() ){
        $em->flush();
         return $this->redirectToRoute('fetch');}
        return $this->render('author/update.html.twig', [
            'f'=>$form->createView()]);
    }

    #[Route('/removezerobooks', name:'RemoveByNbBooks')]
    public function removeAuthorbyNbbooks(AuthorRepository $repo, ManagerRegistry $mr){
        $list=$repo->findAll();
        $em=$mr->getManager();
        foreach($list as $author){
            if ($author->getNbBooks()==0){
                $em->remove($author);
                $em->flush();
             }

        }
         return $this->redirectToRoute('fetch');
    }


    #[Route('/AuthorSearchdql', name:'AuthorSearchdql')]
    public function SearchAuthor(EntityManagerInterface $em, Request $request, AuthorRepository $repo) : Response {
        $result=$repo->findAll();
        if ($request->isMethod('post')){
            $min=$request->get('minNumber') ; 
            $max=$request->get('maxNumber') ;
            $result=$repo->SearchAuthorbyNbBooks($min,$max);
        }
        return $this->render('author/show.html.twig', [
            'response'=>$result]);
    }
}
