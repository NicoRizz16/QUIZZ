<?php
/**
 * Created by PhpStorm.
 * User: nicolasrizzon
 * Date: 18/06/2018
 * Time: 21:03
 */

namespace AppBundle\Controller\Visitor;

use AppBundle\Entity\Qcm;
use AppBundle\Entity\User;
use AppBundle\Form\TrainingQcmLauncherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_USER')")
 */
class TrainingQcmController extends Controller
{
    /**
     * @Route("/qcm/entrainement", name="qcm_training_launcher")
     */
    public function trainingQcmLauncherAction(Request $request)
    {
        $form = $this->createForm(TrainingQcmLauncherType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $post = $form->getData();

            // Initialiser la série d'entraînement.
            $session = $request->getSession();
            $session->set('serieLaunched', true); // Une série d'entraînement est en cours
            $session->set('serieLength', $post['serieLength']); // Longueur de la série
            $session->set('qcmCategories', $post['categories']); // Catégories des qcms joués dans la série
            $session->set('qcmDone', 0); // Catégories des qcms joués dans la série
            $session->set('points', 0); // Catégories des qcms joués dans la série
            $session->set('trainingQcmID', null);

            // Lancement de la série
            return $this->redirectToRoute('qcm_training_play');
        }

        $serieLaunched = $request->getSession()->get('serieLaunched');

        return $this->render('visitor/qcm/qcm_training_launcher.html.twig', array(
            'form' => $form->createView(),
            'serieLaunched' => $serieLaunched
        ));
    }

    /**
     * @Route("/qcm/entrainement/jouer", name="qcm_training_play")
     */
    public function trainingQcmPlayAction(Request $request)
    {
        $session = $request->getSession(); // Récupération de la session
        $qcmID = $session->get('trainingQcmID'); // Récupération de la variable qcm stockée dans la session
        $serieLaunched = $session->get('serieLaunched');

        if(isset($serieLaunched)) // Si une série est en cours
        {
            $qcmDone = $session->get('qcmDone');
            $serieLength = $session->get('serieLength');
            $qcmDone<$serieLength ? $isQcmLeftInSerie = true : $isQcmLeftInSerie = false;
            if(!isset($qcmID)){ // Si pas de QCM en cours => Lancer un nouveau
                if($isQcmLeftInSerie){// Si il reste des QCMs à faire dans la série => Jouer un QCM
                    $qcmCategories = $session->get('qcmCategories');
                    if($qcmCategories->count() == 0){ // Si aucune catégorie spécifiquement sélectionnée pour l'entraînement
                        $qcm = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm')->getOneRandomPublishedQcm();
                    } else { // Sinon Générer 1 qcm aléatoire parmi les catégories choisies
                        $qcm = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm')->getOneRandomPublishedQcmByCategories($qcmCategories);
                    }
                    // Stocker qcm en session avec datetime du lancement
                    $session->set('trainingQcmID', $qcm->getId()); $session->set('trainingQcmPlayedAt', new \DateTime());
                    // Afficher vue du QCM
                    return $this->render(':visitor/qcm:qcm_training_play.html.twig', array('qcm' => $qcm, 'corrected' => false, 'countDown' => $qcm->getCountDown(), 'serieLength' => $serieLength, 'qcmAt' => ($qcmDone+1)));
                } else { // SINON afficher résultats et reset session
                    $points = $session->get('points');
                    $note = ($points/($serieLength*10))*20;

                    return $this->render(':visitor/qcm:qcm_training_results.html.twig', array('note' => $note, 'serieLength' => $serieLength));
                }
            } else { // Reprendre ou corriger le QCM en cours
                $qcmPlayedAt = $session->get('trainingQcmPlayedAt');
                $qcm = $this->getDoctrine()->getRepository(Qcm::class)->find($request->getSession()->get('trainingQcmID')); // Récupération du QCM session
                $timeLeft = $qcm->getCountdown() - ((new \DateTime())->getTimestamp() - $qcmPlayedAt->getTimestamp());
                if($timeLeft > 0){ // Si (datetime actuel - datetime de lancement du qcm) < au countdown du qcm => Afficher QCM avec countdown adapté.
                    return $this->render(':visitor/qcm:qcm_training_play.html.twig', array('qcm' => $qcm, 'corrected' => false, 'countDown' => $timeLeft, 'serieLength' => $serieLength, 'qcmAt' => ($qcmDone+1)));
                } else { // Afficher la correction directement, MAJ avancement de la série
                    $this->correctTrainingQcm($request);
                    $qcmDone = $session->get('qcmDone'); // Récupération du nouveau nombre de QCM fait
                    $qcmDone<$serieLength ? $isQcmLeftInSerie = true : $isQcmLeftInSerie = false;
                    return $this->render(':visitor/qcm:qcm_training_play.html.twig', array('qcm' => $qcm, 'corrected' => true, 'answerA' => false, 'answerB' => false, 'answerC' => false, 'answerD' => false, 'answerE' => false, 'isQcmLeftInSerie' => $isQcmLeftInSerie, 'serieLength' => $serieLength, 'qcmAt' => $qcmDone));
                }
            }
        } else {
            return $this->redirectToRoute('qcm_training_launcher');
        }

    }

    /**
     * @Route("/ajax/qcm/training/correction", name="ajax_training_qcm_correction")
     * @Method("POST")
     */
    public function correctTrainingQcmAjaxAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using AJAX !'), 400);
        }
        $answerA = $request->request->get('answerA') == "true" ? true : false; // Récupération des réponses de l'utilisateur envoyées par AJAX en POST
        $answerB = $request->request->get('answerB') == "true" ? true : false;
        $answerC = $request->request->get('answerC') == "true" ? true : false;
        $answerD = $request->request->get('answerD') == "true" ? true : false;
        $answerE = $request->request->get('answerE') == "true" ? true : false;

        $qcm = $this->getDoctrine()->getRepository(Qcm::class)->find($request->getSession()->get('trainingQcmID')); // Récupération du QCM session
        if(!$qcm){return new JsonResponse(array('message' => 'Erreur de récupération du QCM en session'), 400);}

        // Traitement des réponses
        $this->correctTrainingQcm($request, $answerA, $answerB, $answerC, $answerD, $answerE);

        // Reste-t-il des qcms dans la série
        $session = $request->getSession();
        $qcmDone = $session->get('qcmDone');
        $serieLength = $session->get('serieLength');
        $qcmDone<$serieLength ? $isQcmLeftInSerie = true : $isQcmLeftInSerie = false;

        $response = $this->renderView('visitor/qcm/_training_qcm_correction.html.twig', array( // Génération de la vue correction
            'qcm' => $qcm,
            'answerA' => $answerA,
            'answerB' => $answerB,
            'answerC' => $answerC,
            'answerD' => $answerD,
            'answerE' => $answerE,
            'isQcmLeftInSerie' => $isQcmLeftInSerie
        ));

        return new JsonResponse(array('response' => $response), 200);
    }

    private function correctTrainingQcm(Request $request, $answerA = false, $answerB = false, $answerC = false, $answerD = false, $answerE = false){
        sleep(1.5);// Effet de chargement de la correction

        $session = $request->getSession();
        // Calcul du score au QCM joué
        $score = $this->get('app.calculate_qcm_score')->calculateQcmScore($session->get('trainingQcmID'), $answerA, $answerB, $answerC, $answerD, $answerE);
        // Ajout des points au total sur la série
        $points = $session->get('points');
        $session->set('points', ($points + $score));

        $qcmDone = $session->get('qcmDone');
        $session->set('qcmDone', ($qcmDone+1)); // Catégories des qcms joués dans la série
        $request->getSession()->set('trainingQcmID', null); // Réinitialisation de la session

        return $score;
    }

    /**
     * @Route("/qcm/entrainement/reset", name="qcm_training_reset")
     */
    public function trainingQcmResetAction(Request $request)
    {
        $session = $request->getSession();

        $session->set('serieLaunched', null); // RESET SESSION
        $session->set('serieLength', null);
        $session->set('qcmCategories', null);
        $session->set('qcmDone', null);
        $session->set('points', null);
        $session->set('trainingQcmID', null);
        $session->set('trainingQcmPlayedAt', null);

        return $this->redirectToRoute('qcm_training_launcher');
    }

}