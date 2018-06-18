<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const MAX_RANKED_QCM_DAY = 5;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="ranked_qcm_done_today", type="integer")
     */
    private $rankedQcmDoneToday;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_ranked_qcm_date", type="datetime")
     */
    private $lastRankedQcmDate;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    public function __construct()
    {
        parent::__construct();
        $this->setRankedQcmDoneToday(0);
        $this->setLastRankedQcmDate(new \DateTime());
        $this->setPoints(0);
    }

    /**
     * Set rankedQcmDoneToday
     *
     * @param integer $rankedQcmDoneToday
     *
     * @return User
     */
    public function setRankedQcmDoneToday($rankedQcmDoneToday)
    {
        $this->rankedQcmDoneToday = $rankedQcmDoneToday;

        return $this;
    }

    /**
     * Get rankedQcmDoneToday
     *
     * @return integer
     */
    public function getRankedQcmDoneToday()
    {
        return $this->rankedQcmDoneToday;
    }

    /**
     * Set lastRankedQcmDate
     *
     * @param \DateTime $lastRankedQcmDate
     *
     * @return User
     */
    public function setLastRankedQcmDate($lastRankedQcmDate)
    {
        $this->lastRankedQcmDate = $lastRankedQcmDate;

        return $this;
    }

    /**
     * Get lastRankedQcmDate
     *
     * @return \DateTime
     */
    public function getLastRankedQcmDate()
    {
        return $this->lastRankedQcmDate;
    }

    public function isRankedQcmLeftToday()
    {
        $date = new \DateTime();
        if($date->format('Ymd') == $this->getLastRankedQcmDate()->format('Ymd')){
            if($this->getRankedQcmDoneToday() >= User::MAX_RANKED_QCM_DAY){
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getExactRankedQcmNumberDoneToday()
    {
        $date = new \DateTime();
        if($date->format('Ymd') == $this->getLastRankedQcmDate()->format('Ymd')){
            return $this->getRankedQcmDoneToday();
        } else {
            return 0;
        }
    }


    /**
     * Set points
     *
     * @param integer $points
     *
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }
}
