<?php

namespace AppBundle\Controller\Visitor;

use AppBundle\Entity\Qcm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/qcm")
 * @Security("has_role('ROLE_USER')")
 */
class QcmController extends Controller
{
    /**
     * @Route("/classe", name="qcm_ranked_home")
     */
    public function rankedQcmHomeAction(Request $request)
    {
        return $this->render('visitor/qcm/qcm_ranked_home.html.twig');
    }

    /**
     * @Route("/classe/jouer", name="qcm_ranked_play")
     */
    public function rankedQcmPlayAction(Request $request)
    {
        $session = $request->getSession(); // Récupération de la session
        $qcmID = $session->get('qcmID'); // Récupération de la variable qcm stockée dans la session
        $user = $this->getUser(); // Récupération de l'utilisateur

        if(!isset($qcmID)){ // Si pas de qcm en session => Alors jouer un nouveau qcm

            if($user->isRankedQcmLeftToday()){ // Si il reste des qcm à faire pour l'utilisateur aujourd'hui
                // Générer 1 qcm aléatoirement parmi la base
                $qcm = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm')->getOneRandomPublishedQcm();
                // Stocker qcm en session avec datetime du lancement
                $session->set('qcmID', $qcm->getId()); $session->set('qcmPlayedAt', new \DateTime());
                $this->updateRankedQcmLeftToday(); // Mise à jour du nombre de QCM restant à l'utilisateur
                // Afficher vue du QCM
                return $this->render(':visitor/qcm:qcm_ranked_play.html.twig', array('qcm' => $qcm, 'corrected' => false, 'countDown' => $qcm->getCountDown()));
            } else {
                // erreur => Plus de QCM aujourd'hui, revenir demain
                return $this->render('visitor/qcm/no_more_ranked_qcm_left.html.twig');
            }

        } else { // Sinon traiter le QCM en session (afficher correction / reprendre avec countdown adapté)
            $qcmPlayedAt = $session->get('qcmPlayedAt');
            $qcm = $this->getDoctrine()->getRepository(Qcm::class)->find($request->getSession()->get('qcmID')); // Récupération du QCM session
            $timeLeft = $qcm->getCountdown() - ((new \DateTime())->getTimestamp() - $qcmPlayedAt->getTimestamp());
            if($timeLeft > 0){ // Si (datetime actuel - datetime de lancement du qcm) < au countdown du qcm => Afficher QCM avec countdown adapté.
                return $this->render(':visitor/qcm:qcm_ranked_play.html.twig', array('qcm' => $qcm, 'corrected' => false, 'countDown' => $timeLeft));
            } else { // Afficher la correction directement, suppression du qcm en session, traitement du gain de classement
                $this->correctRankedQcm($request);
                return $this->render(':visitor/qcm:qcm_ranked_play.html.twig', array('qcm' => $qcm, 'corrected' => true, 'answerA' => false, 'answerB' => false, 'answerC' => false, 'answerD' => false, 'answerE' => false));
            }
        }

        return $this->render('visitor/qcm/qcm_ranked_play.html.twig');
    }

    /**
     * @Route("/ajax/qcm/ranked/correction", name="ajax_ranked_qcm_correction")
     * @Method("POST")
     */
    public function correctRankedQcmAjaxAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using AJAX !'), 400);
        }
        $answerA = $request->request->get('answerA') == "true" ? true : false; // Récupération des réponses de l'utilisateur envoyées par AJAX en POST
        $answerB = $request->request->get('answerB') == "true" ? true : false;
        $answerC = $request->request->get('answerC') == "true" ? true : false;
        $answerD = $request->request->get('answerD') == "true" ? true : false;
        $answerE = $request->request->get('answerE') == "true" ? true : false;

        $qcm = $this->getDoctrine()->getRepository(Qcm::class)->find($request->getSession()->get('qcmID')); // Récupération du QCM session
        if(!$qcm){return new JsonResponse(array('message' => 'Erreur de récupération du QCM en session'), 400);}

        // Traitement des réponses
        $this->correctRankedQcm($request, $answerA, $answerB, $answerC, $answerD, $answerE);

        $response = $this->renderView('visitor/qcm/_qcm_correction.html.twig', array( // Génération de la vue correction
            'qcm' => $qcm,
            'answerA' => $answerA,
            'answerB' => $answerB,
            'answerC' => $answerC,
            'answerD' => $answerD,
            'answerE' => $answerE
        ));

        return new JsonResponse(array('response' => $response), 200);
    }

    private function correctRankedQcm(Request $request, $answerA = false, $answerB = false, $answerC = false, $answerD = false, $answerE = false){
        $em = $this->getDoctrine()->getManager();
        // Calcul du score au QCM joué
        $score = $this->get('app.calculate_qcm_score')->calculateQcmScore($request->getSession()->get('qcmID'), $answerA, $answerB, $answerC, $answerD, $answerE);
        // Ajout du score au classement utilisateur
        $user = $this->getUser();
        $user->setPoints($user->getPoints() + $score);
        $em->flush();

        $request->getSession()->set('qcmID', null); // Réinitialisation de la session
    }

    private function updateRankedQcmLeftToday()
    {
        $date = new \DateTime();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if($date->format('Ymd') == $user->getLastRankedQcmDate()->format('Ymd')){
            $user->setRankedQcmDoneToday($user->getRankedQcmDoneToday() + 1);
        } else {
            $user->setLastRankedQcmDate(new \DateTime());
            $user->setRankedQcmDoneToday(1);
        }
        $em->flush();
    }

}
