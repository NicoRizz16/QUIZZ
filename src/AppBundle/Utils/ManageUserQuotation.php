<?php
/**
 * Created by PhpStorm.
 * User: nicolasrizzon
 * Date: 05/07/2018
 * Time: 12:08
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Quotation;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ManageUserQuotation
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function manageUserQuotation(User $user)
    {
        $date = new \DateTime();
        if($user->getQuotation() != null){
            if ($user->getQuotationUpdatedAt() != null){
                if($date->format('Ymd') == $user->getQuotationUpdatedAt()->format('Ymd')){ // Si la citation a déjà été modifiée ajd => return
                    return;
                }
            }
        }

        $em = $this->em;
        $randomQuotation = $em->getRepository(Quotation::class)->getOneRandomQuotation();
        $user->setQuotation($randomQuotation);
        $user->setQuotationUpdatedAt($date);
        $em->persist($user);
        $em->flush();
    }
}