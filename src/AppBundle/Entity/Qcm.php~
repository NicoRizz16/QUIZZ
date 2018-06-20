<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Qcm
 *
 * @ORM\Table(name="qcm")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QcmRepository")
 * @Vich\Uploadable
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $author;

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
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="qcms")
     * @ORM\JoinTable(name="qcms_categories",
     *      joinColumns={@ORM\JoinColumn(name="qcm_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *  )
     */
    private $categories;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="qcm_images", fileNameProperty="questionImageName")
     * @Assert\Image()
     *
     * @var File
     */
    private $questionImageFile;

    /**
     * @ORM\Column(name="question_image_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $questionImageName;

    /**
     * @ORM\Column(name="question_image_updated_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $questionImageUpdatedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="qcm_images", fileNameProperty="commentImageName")
     * @Assert\Image()
     *
     * @var File
     */
    private $commentImageFile;

    /**
     * @ORM\Column(name="comment_image_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $commentImageName;

    /**
     * @ORM\Column(name="comment_image_updated_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $commentImageUpdatedAt;



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
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Qcm
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Qcm
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Has category
     *
     * @param \AppBundle\Entity\Category $category
     * @return boolean
     */
    public function hasCategory(Category $category)
    {
        foreach ($this->categories as $item) {
            if($item == $category){
                return true;
            }
        }
        return false;
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
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
     * @return Qcm
     */
    public function setQuestionImageFile(File $image = null)
    {
        $this->questionImageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->questionImageUpdatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getQuestionImageFile()
    {
        return $this->questionImageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Qcm
     */
    public function setQuestionImageName($imageName)
    {
        $this->questionImageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuestionImageName()
    {
        return $this->questionImageName;
    }

    /**
     * Set questionImageUpdatedAt
     *
     * @param \DateTime $imageUpdatedAt
     *
     * @return Qcm
     */
    public function setQuestionImageUpdatedAt($imageUpdatedAt)
    {
        $this->questionImageUpdatedAt = $imageUpdatedAt;

        return $this;
    }

    /**
     * Get questionImageUpdatedAt
     *
     * @return \DateTime
     */
    public function getQuestionImageUpdatedAt()
    {
        return $this->questionImageUpdatedAt;
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
     * @return Qcm
     */
    public function setCommentImageFile(File $image = null)
    {
        $this->commentImageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->commentImageUpdatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getCommentImageFile()
    {
        return $this->commentImageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Qcm
     */
    public function setCommentImageName($imageName)
    {
        $this->commentImageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentImageName()
    {
        return $this->commentImageName;
    }

    /**
     * Set commentImageUpdatedAt
     *
     * @param \DateTime $imageUpdatedAt
     *
     * @return Qcm
     */
    public function setCommentImageUpdatedAt($imageUpdatedAt)
    {
        $this->commentImageUpdatedAt = $imageUpdatedAt;

        return $this;
    }

    /**
     * Get commentImageUpdatedAt
     *
     * @return \DateTime
     */
    public function getCommentImageUpdatedAt()
    {
        return $this->commentImageUpdatedAt;
    }
}

