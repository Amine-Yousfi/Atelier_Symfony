<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
  // /home/4

    #[Route('/home/{id}', name:'home')]
    public function home($id)
    {
        //return new Response( content: "hello 3A".$id);
       // render (view, paramétre);
      return $this->render( 'blog/home.html.twig',['ghassen'=> $id]);
}



}
