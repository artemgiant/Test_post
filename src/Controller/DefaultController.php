<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    
    /**
     * @Route("/post/dashboard", name="post_dashboard")
     */
    public function indexAction(): Response
    {
      
        return $this->render('dashboard/dashboard.html.twig');
    }
    
    /**
     * @Route("/", name="homepage")
     */
    
    public function homepage()
    {
        
       return $this->redirectToRoute('post_dashboard');
    }
    
}
