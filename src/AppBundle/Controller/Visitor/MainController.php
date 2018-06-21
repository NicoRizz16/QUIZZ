<?php

namespace AppBundle\Controller\Visitor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/accueil", name="homepage")
     * @Security("has_role('ROLE_USER')")
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
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('homepage');
        }
        return $this->render('visitor/landing_page.html.twig');
    }

    /**
     * @Route("/render/nav/user", name="user_nav")
     */
    public function userNavAction()
    {
        $user = $this->getUser();
        return $this->render(':visitor/main:_user_nav.html.twig', array(
            'user' => $user
        ));
    }
}
