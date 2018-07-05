<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Quotation;
use AppBundle\Form\QuotationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuotationController extends Controller
{
    /**
     * @Route("/admin/citations", name="admin_quotations")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function quotationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quotationsList = $em->getRepository('AppBundle:Quotation')->findBy(array(), array('author' => 'ASC'));

        return $this->render(':admin/quotations:index.html.twig', array(
            'quotationsList' => $quotationsList
        ));
    }

    /**
     * @Route("/admin/citations/ajouter", name="admin_quotations_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addQuotationAction(Request $request)
    {
        $quotation = new Quotation();

        $form = $this->createForm(QuotationType::class, $quotation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($quotation);
            $em->flush();

            $this->addFlash('success', 'La nouvelle citation a bien été enregistrée !');
            return $this->redirectToRoute('admin_quotations');
        }

        return $this->render(':admin/quotations:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/citations/supprimer/{id}", name="admin_quotations_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteQuotationAction(Request $request, Quotation $quotation)
    {
        $form = $this->get('form.factory')->create();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Suppression de la citation chez les utilisateurs liés à elle
            $usersWithQuotation = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array('quotation' => $quotation));
            foreach ($usersWithQuotation as $user){
                $user->setQuotation(null);
            }

            $em->remove($quotation);
            $em->flush();

            $this->addFlash('success', 'La citation a bien été supprimée.');
            return $this->redirectToRoute('admin_quotations');
        }

        return $this->render(':admin/quotations:delete.html.twig', array(
            'form' => $form->createView(),
            'quotation' => $quotation
        ));
    }
}
