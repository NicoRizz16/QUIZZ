<?php

namespace AppBundle\Controller\Visitor;

use AppBundle\Entity\Qcm;
use AppBundle\Form\SuggestQcmType;
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

    /**
     * @Route("/qcm/proposer", name="suggest_qcm")
     * @Security("has_role('ROLE_USER')")
     */
    public function suggestQcmAction(Request $request)
    {
        $qcm = new Qcm();
        $form = $this->createForm(SuggestQcmType::class, $qcm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $qcm->setPublished(false);
            $qcm->setAuthor($this->getUser());
            $em->persist($qcm);
            $em->flush();

            return $this->render(':visitor/main:suggest_qcm_sent.html.twig');
        }

        return $this->render(':visitor/main:suggest_qcm.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
