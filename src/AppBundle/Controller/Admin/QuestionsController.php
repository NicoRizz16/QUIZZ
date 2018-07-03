<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Qcm;
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
     * @Security("has_role('ROLE_ADMIN')")
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

}
