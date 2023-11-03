<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\UpdateBookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/addF', name:'BookaddF')]
    public function addF(ManagerRegistry $mr, Request $req, AuthorRepository $repo): Response
    {
      
       $book=new Book();
      
   
        $form=$this->createForm( BookType::class, $book);
        $form->handleRequest($req);


    if ($form->isSubmitted() ){
        $book->setPublished(true);
        $em=$mr->getManager();
        $em->persist($book); 
        $auth= $book->getAuthor();
       $auth->setNbBooks($auth->getNbBooks()+1);
       $em->flush();
       
         return $this->redirectToRoute('fetch');}
        return $this->render('book/add.html.twig', [
            'f'=>$form->createView()]);
    }


    #[Route('/book/showpublished', name:'ShowPublished')]
    public function showpub( BookRepository $repo): Response
    {
      
        $result=$repo->BookListByAuthor();
        return $this->render('book/show.html.twig', [ 'response' => $result ]);
    }



    #[Route('/book/updateF/{ref}', name:'BookupdateF')]
    public function updateF(ManagerRegistry $mr,BookRepository $repo, Request $req, int $ref): Response
    {
        $em=$mr->getManager();
          $book=$repo->find($ref);
          $old_author=$book->getAuthor();
        $form=$this->createForm( UpdateBookType::class, $book);
        $form->handleRequest($req);
       

    if ($form->isSubmitted() && $form->isValid() ){
        $new_author=$book->getAuthor();
        if ($old_author !== $new_author) {
            $new_author->setNbBooks($new_author->getNbBooks()+ 1);
            $old_author->setNbBooks($old_author->getNbBooks()- 1);  
        }
        $em->flush();
         return $this->redirectToRoute('ShowPublished');}
        return $this->render('book/update.html.twig', [
            'f'=>$form->createView()]);
    }



    #[Route('/book/remove/{ref}', name:'Bookremove')]
    public function remove(int $ref, BookRepository $repo, ManagerRegistry $mr): Response
    {
       $book=$repo->find($ref);
       
       $em=$mr->getManager();
        $em->remove($book);
        $auth=$book->getAuthor();
        $auth->setNbBooks($auth->getNbBooks()-1);
        $em->flush();
        return $this->redirectToRoute('ShowPublished');
    }

    #[Route('/book/show/{ref}', name:'ShowBook')]
    public function show(int $ref, BookRepository $repo): Response
    {
       $book=$repo->find($ref);
       
      
       return $this->render('book/details.html.twig', [
        'book'=>$book]);
    }
   




    #[Route('/dql', name:'dql')]
    public function SearchBook(EntityManagerInterface $em, Request $request, BookRepository $repo): Response{
        $result=$repo->findAll();
        if ($request->isMethod('post')){
            $value=$request->get('test') ; 
            $result=$repo->SearchBookByRef($value);
        }
        return $this->render('book/show.html.twig', [
            'response'=>$result]);


}


}