<?php

namespace AppBundle\Controller\Visitor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/accueil", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        return $this->render('visitor/main/index.html.twig');
    }

    /**
     * @Route("/", name="landing_page")
     */
    public function landingPageAction(Request $request)
    {
        return $this->render('visitor/landing_page.html.twig');
    }
}
