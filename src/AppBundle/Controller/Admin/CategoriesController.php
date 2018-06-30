<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends Controller
{
    /**
     * @Route("/admin/categories", name="admin_categories")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function categoriesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoriesList = $em->getRepository('AppBundle:Category')->findBy(array(), array('name' => 'ASC'));

        return $this->render(':admin/categories:index.html.twig', array(
            'categoriesList' => $categoriesList
        ));
    }

    /**
     * @Route("/admin/categories/ajouter", name="admin_categories_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La nouvelle catégorie a bien été enregistrée !');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render(':admin/categories:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/categories/modifier/{id}", name="admin_categories_edit", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editCategoryAction(Request $request, Category $category)
    {
        if (!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas.');
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée !');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render(':admin/categories:edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * @Route("/admin/categories/supprimer/{id}", name="admin_categories_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteCategoryAction(Request $request, Category $category)
    {
        $form = $this->get('form.factory')->create();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie "'.$category->getName().'" a bien été supprimée.');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render(':admin/categories:delete.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }
}
