<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Qcm
 *
 * @ORM\Table(name="qcm")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QcmRepository")
 */
class Qcm
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answerA", type="string", length=255)
     */
    private $answerA;

    /**
     * @var string
     *
     * @ORM\Column(name="answerB", type="string", length=255)
     */
    private $answerB;

    /**
     * @var string
     *
     * @ORM\Column(name="answerC", type="string", length=255)
     */
    private $answerC;

    /**
     * @var string
     *
     * @ORM\Column(name="answerD", type="string", length=255)
     */
    private $answerD;

    /**
     * @var string
     *
     * @ORM\Column(name="answerE", type="string", length=255)
     */
    private $answerE;

    /**
     * @var bool
     *
     * @ORM\Column(name="veracityA", type="boolean")
     */
    private $veracityA;

    /**
     * @var bool
     *
     * @ORM\Column(name="veracityB", type="boolean")
     */
    private $veracityB;

    /**
     * @var bool
     *
     * @ORM\Column(name="veracityC", type="boolean")
     */
    private $veracityC;

    /**
     * @var bool
     *
     * @ORM\Column(name="veracityD", type="boolean")
     */
    private $veracityD;

    /**
     * @var bool
     *
     * @ORM\Column(name="veracityE", type="boolean")
     */
    private $veracityE;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="countdown", type="integer")
     */
    private $countdown;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;



    public function __construct()
    {
        $this->setCreationDate(new \DateTime());
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Qcm
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return Qcm
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answerA
     *
     * @param string $answerA
     *
     * @return Qcm
     */
    public function setAnswerA($answerA)
    {
        $this->answerA = $answerA;

        return $this;
    }

    /**
     * Get answerA
     *
     * @return string
     */
    public function getAnswerA()
    {
        return $this->answerA;
    }

    /**
     * Set answerB
     *
     * @param string $answerB
     *
     * @return Qcm
     */
    public function setAnswerB($answerB)
    {
        $this->answerB = $answerB;

        return $this;
    }

    /**
     * Get answerB
     *
     * @return string
     */
    public function getAnswerB()
    {
        return $this->answerB;
    }

    /**
     * Set answerC
     *
     * @param string $answerC
     *
     * @return Qcm
     */
    public function setAnswerC($answerC)
    {
        $this->answerC = $answerC;

        return $this;
    }

    /**
     * Get answerC
     *
     * @return string
     */
    public function getAnswerC()
    {
        return $this->answerC;
    }

    /**
     * Set answerD
     *
     * @param string $answerD
     *
     * @return Qcm
     */
    public function setAnswerD($answerD)
    {
        $this->answerD = $answerD;

        return $this;
    }

    /**
     * Get answerD
     *
     * @return string
     */
    public function getAnswerD()
    {
        return $this->answerD;
    }

    /**
     * Set answerE
     *
     * @param string $answerE
     *
     * @return Qcm
     */
    public function setAnswerE($answerE)
    {
        $this->answerE = $answerE;

        return $this;
    }

    /**
     * Get answerE
     *
     * @return string
     */
    public function getAnswerE()
    {
        return $this->answerE;
    }

    /**
     * Set veracityA
     *
     * @param boolean $veracityA
     *
     * @return Qcm
     */
    public function setVeracityA($veracityA)
    {
        $this->veracityA = $veracityA;

        return $this;
    }

    /**
     * Get veracityA
     *
     * @return bool
     */
    public function getVeracityA()
    {
        return $this->veracityA;
    }

    /**
     * Set veracityB
     *
     * @param boolean $veracityB
     *
     * @return Qcm
     */
    public function setVeracityB($veracityB)
    {
        $this->veracityB = $veracityB;

        return $this;
    }

    /**
     * Get veracityB
     *
     * @return bool
     */
    public function getVeracityB()
    {
        return $this->veracityB;
    }

    /**
     * Set veracityC
     *
     * @param boolean $veracityC
     *
     * @return Qcm
     */
    public function setVeracityC($veracityC)
    {
        $this->veracityC = $veracityC;

        return $this;
    }

    /**
     * Get veracityC
     *
     * @return bool
     */
    public function getVeracityC()
    {
        return $this->veracityC;
    }

    /**
     * Set veracityD
     *
     * @param boolean $veracityD
     *
     * @return Qcm
     */
    public function setVeracityD($veracityD)
    {
        $this->veracityD = $veracityD;

        return $this;
    }

    /**
     * Get veracityD
     *
     * @return bool
     */
    public function getVeracityD()
    {
        return $this->veracityD;
    }

    /**
     * Set veracityE
     *
     * @param boolean $veracityE
     *
     * @return Qcm
     */
    public function setVeracityE($veracityE)
    {
        $this->veracityE = $veracityE;

        return $this;
    }

    /**
     * Get veracityE
     *
     * @return bool
     */
    public function getVeracityE()
    {
        return $this->veracityE;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Qcm
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Qcm
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set countdown
     *
     * @param integer $countdown
     *
     * @return Qcm
     */
    public function setCountdown($countdown)
    {
        $this->countdown = $countdown;

        return $this;
    }

    /**
     * Get countdown
     *
     * @return int
     */
    public function getCountdown()
    {
        return $this->countdown;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Qcm
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}

