<?php

namespace AppBundle\Controller\Visitor;

use AppBundle\Entity\Qcm;
use AppBundle\Form\EditProfileType;
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
        $user = $this->getUser();
        $this->get('app.manage_user_quotation')->manageUserQuotation($user);

        return $this->render('visitor/main/index.html.twig', array(
            'user' => $user
        ));
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

    /**
     * @Route("/utilisateur/profil", name="user_profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function userProfileAction(Request $request)
    {
        $user = $this->getUser();

        return $this->render('visitor/main/profile_view.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/utilisateur/profil/modifier", name="user_profile_edit")
     * @Security("has_role('ROLE_USER')")
     */
    public function userProfileEditAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('visitor/main/profile_edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/utilisateur/classement", name="user_rank")
     * @Security("has_role('ROLE_USER')")
     */
    public function userRankAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $userRank = $em->getRepository('AppBundle:User')->getUserRank($user);
        $top100 = $em->getRepository('AppBundle:User')->getTop100();

        return $this->render('visitor/main/rank.html.twig', array(
            'user' => $user,
            'userRank' => $userRank,
            'top100' => $top100
        ));
    }
}
