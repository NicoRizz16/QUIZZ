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

    public function __construct()
    {
        parent::__construct();
        $this->setRankedQcmDoneToday(0);
        $this->setLastRankedQcmDate(new \DateTime());
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
}
