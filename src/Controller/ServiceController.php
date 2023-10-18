<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/service/{name}', name:'service')]
    public function showService($name)
    {
        return $this->render( 'service/showService.html.twig',[
            'name'=> $name]);
    }
    #[Route('/gotoindex', name:'gotoindex')]
    public function goToIndex(): Response
    {
         return $this->redirectToRoute('app_home');
    }
}
