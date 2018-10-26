<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    const MAX_RANKED_QCM_DAY = 5;
    const UNLOCK_TRAINING_MODE = 500;

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

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="profile_images", fileNameProperty="profileImageName")
     * @Assert\Image()
     *
     * @var File
     */
    private $profileImageFile;

    /**
     * @ORM\Column(name="profile_image_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $profileImageName;

    /**
     * @ORM\Column(name="profile_image_updated_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $profileImageUpdatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Quotation")
     * @ORM\JoinColumn(name="quotation_id", referencedColumnName="id")
     */
    private $quotation;

    /**
     * @ORM\Column(name="quotation_updated_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $quotationUpdatedAt;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setProfileImageFile(File $image = null)
    {
        $this->profileImageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->profileImageUpdatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getProfileImageFile()
    {
        return $this->profileImageFile;
    }

    /**
     * @param string $imageName
     *
     * @return User
     */
    public function setProfileImageName($imageName)
    {
        $this->profileImageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfileImageName()
    {
        return $this->profileImageName;
    }

    /**
     * Set profileImageUpdatedAt
     *
     * @param \DateTime $imageUpdatedAt
     *
     * @return User
     */
    public function setProfileImageUpdatedAt($imageUpdatedAt)
    {
        $this->profileImageUpdatedAt = $imageUpdatedAt;

        return $this;
    }

    /**
     * Get profileImageUpdatedAt
     *
     * @return \DateTime
     */
    public function getProfileImageUpdatedAt()
    {
        return $this->profileImageUpdatedAt;
    }

    /**
     * Set quotationUpdatedAt
     *
     * @param \DateTime $quotationUpdatedAt
     *
     * @return User
     */
    public function setQuotationUpdatedAt($quotationUpdatedAt)
    {
        $this->quotationUpdatedAt = $quotationUpdatedAt;

        return $this;
    }

    /**
     * Get quotationUpdatedAt
     *
     * @return \DateTime
     */
    public function getQuotationUpdatedAt()
    {
        return $this->quotationUpdatedAt;
    }

    /**
     * Set quotation
     *
     * @param \AppBundle\Entity\Quotation $quotation
     *
     * @return User
     */
    public function setQuotation(\AppBundle\Entity\Quotation $quotation = null)
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * Get quotation
     *
     * @return \AppBundle\Entity\Quotation
     */
    public function getQuotation()
    {
        return $this->quotation;
    }
}
