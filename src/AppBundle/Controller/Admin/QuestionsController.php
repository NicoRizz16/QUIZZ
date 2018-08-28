<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Qcm;
use AppBundle\Form\AdminQcmType;
use AppBundle\Repository\QcmRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuestionsController extends Controller
{
    /**
     * @Route("/admin/questions", name="admin_questions")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function questionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoriesList = $em->getRepository('AppBundle:Category')->findBy(array(), array('name' => 'ASC'));
        $qcmRepository = $em->getRepository('AppBundle:Qcm');
        $notPublishedQcmCount = $qcmRepository->getNotPublishedQcmCount();
        $totalQcm = $qcmRepository->countAll();
        $qcmPublishedWithoutCategoryCount = $qcmRepository->getQcmPublishedWithoutCategoryCount();


        return $this->render(':admin/questions:index.html.twig', array(
            'categoriesList' => $categoriesList,
            'notPublishedQcmCount' => $notPublishedQcmCount,
            'totalQcm' => $totalQcm,
            'qcmPublishedWithoutCategoryCount' => $qcmPublishedWithoutCategoryCount
        ));
    }

    /**
     * @Route("/admin/questions/categorie/{category_id}/{orderBy}/{orderSens}/{page}", name="admin_questions_list_by_category", requirements={"id": "\d+", "page": "\d+"}, defaults={"orderBy": "creationDate", "orderSens": "DESC", "page": 1})
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}, "exclude": {"order_by", "order_sens", "page"}})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function listByCategoryAction(Request $request, Category $category, $orderBy, $orderSens, $page)
    {
        if(!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas.');
        }
        if($page<1){$page = 1;}

        $qcmRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm');
        $qcmList = $qcmRepository->getPublishedQcmWithCategoryByOrderByPage($category->getId(), $orderBy, $orderSens, $page);
        $nbPageTotal = ceil(count($qcmList)/QcmRepository::NUM_BY_LIST_ADMIN);

        if($page>$nbPageTotal && $page != 1){$page = $nbPageTotal;}

        return $this->render('admin/questions/list_by_category.html.twig', array(
            'qcmList' => $qcmList,
            'nbPageTotal' => $nbPageTotal,
            'page' => $page,
            'orderBy' => $orderBy,
            'orderSens' => $orderSens,
            'category' => $category
        ));
    }

    /**
     * @Route("/admin/questions/sanscat/{orderBy}/{orderSens}/{page}", name="admin_questions_list_without_category", requirements={"page": "\d+"}, defaults={"orderBy": "creationDate", "orderSens": "DESC", "page": 1})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function listWithoutCategoryAction(Request $request, $orderBy, $orderSens, $page)
    {
        if($page<1){$page = 1;}

        $qcmRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm');
        $qcmList = $qcmRepository->getPublishedQcmWithoutCategoryByOrderByPage($orderBy, $orderSens, $page);
        $nbPageTotal = ceil(count($qcmList)/QcmRepository::NUM_BY_LIST_ADMIN);

        if($page>$nbPageTotal && $page != 1){$page = $nbPageTotal;}

        return $this->render('admin/questions/list_without_category.html.twig', array(
            'qcmList' => $qcmList,
            'nbPageTotal' => $nbPageTotal,
            'page' => $page,
            'orderBy' => $orderBy,
            'orderSens' => $orderSens
        ));
    }

    /**
     * @Route("/admin/questions/nonpublies/{orderBy}/{orderSens}/{page}", name="admin_questions_list_not_published", requirements={"page": "\d+"}, defaults={"orderBy": "creationDate", "orderSens": "ASC", "page": 1})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function listNotPublishedAction(Request $request, $orderBy, $orderSens, $page)
    {
        if($page<1){$page = 1;}

        $qcmRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Qcm');
        $qcmList = $qcmRepository->getNotPublishedQcmByOrderByPage($orderBy, $orderSens, $page);
        $nbPageTotal = ceil(count($qcmList)/QcmRepository::NUM_BY_LIST_ADMIN);

        if($page>$nbPageTotal && $page != 1){$page = $nbPageTotal;}

        return $this->render('admin/questions/list_not_published.html.twig', array(
            'qcmList' => $qcmList,
            'nbPageTotal' => $nbPageTotal,
            'page' => $page,
            'orderBy' => $orderBy,
            'orderSens' => $orderSens
        ));
    }

    /**
     * @Route("/admin/questions/ajouter", name="admin_questions_add")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function addQcmAction(Request $request)
    {
        $qcm = new Qcm();
        $form = $this->createForm(AdminQcmType::class, $qcm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $qcm->setAuthor($this->getUser());
            $em->persist($qcm);
            $em->flush();

            // ADDFLASH
            $this->addFlash('success', 'Le nouveau QCM a bien été enregistré !');
            return $this->redirectToRoute('admin_questions');
        }

        return $this->render('admin/questions/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/questions/supprimer/{id}", name="admin_questions_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function deleteQcmAction(Request $request, Qcm $qcm)
    {
        $form = $this->get('form.factory')->create();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($qcm);
            $em->flush();

            $this->addFlash('success', 'Le QCM a bien été supprimé.');
            return $this->redirectToRoute('admin_questions');
        }

        return $this->render(':admin/questions:delete.html.twig', array(
            'form' => $form->createView(),
            'qcm' => $qcm
        ));
    }

    /**
     * @Route("/admin/questions/previsualiser/{id}", name="admin_questions_preview", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function previewQcmAction(Qcm $qcm)
    {
        if(!$qcm) {
            throw $this->createNotFoundException('Ce QCM n\'existe pas.');
        }

        return $this->render('admin/questions/preview.html.twig', array(
            'qcm' => $qcm,
            'answerA' => false,
            'answerB' => false,
            'answerC' => false,
            'answerD' => false,
            'answerE' => false

        ));
    }

    /**
     * @Route("/admin/questions/modifier/{id}", name="admin_questions_edit", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function editQcmAction(Request $request, Qcm $qcm)
    {
        if(!$qcm) {
            throw $this->createNotFoundException('Ce QCM n\'existe pas.');
        }

        $form = $this->createForm(AdminQcmType::class, $qcm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qcm);
            $em->flush();

            $this->addFlash('success', 'Le QCM a bien été modifié !');
            return $this->redirectToRoute('admin_questions');
        }

        return $this->render('admin/questions/edit.html.twig', array(
            'form' => $form->createView(),
            'qcm' => $qcm
        ));
    }

}
