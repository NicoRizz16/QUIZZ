<?php
/**
 * Created by PhpStorm.
 * User: nicolasrizzon
 * Date: 14/06/2018
 * Time: 11:58
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Qcm;
use Doctrine\ORM\EntityManager;


class CalculateQcmScore
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function calculateQcmScore($qcmId, $answerA, $answerB, $answerC, $answerD, $answerE)
    {
        $qcm = $this->em->getRepository(Qcm::class)->find($qcmId);
        $errorNbr = 0;
        if($answerA != $qcm->getVeracityA()){$errorNbr++;}
        if($answerB != $qcm->getVeracityB()){$errorNbr++;}
        if($answerC != $qcm->getVeracityC()){$errorNbr++;}
        if($answerD != $qcm->getVeracityD()){$errorNbr++;}
        if($answerE != $qcm->getVeracityE()){$errorNbr++;}

        switch ($errorNbr){
            case 0:
                return 10;
                break;
            case 1:
                return 5;
                break;
            case 2:
                return 2;
                break;
            default:
                return 0;
        }
    }

}