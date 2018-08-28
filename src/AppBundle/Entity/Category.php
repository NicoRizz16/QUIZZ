<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @UniqueEntity("name")
 */
class Category
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Le nom de la catégorie doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Le nom de la catégorie doit faire moins de {{ limit }} caractères"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Qcm", mappedBy="categories")
     */
    private $qcms;


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add qcm
     *
     * @param \AppBundle\Entity\Qcm $qcm
     *
     * @return Category
     */
    public function addQcm(\AppBundle\Entity\Qcm $qcm)
    {
        $this->qcms[] = $qcm;

        return $this;
    }

    /**
     * Remove qcm
     *
     * @param \AppBundle\Entity\Qcm $qcm
     */
    public function removeChild(\AppBundle\Entity\Qcm $qcm)
    {
        $this->qcms->removeElement($qcm);
    }

    /**
     * Get qcms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQcms()
    {
        return $this->qcms;
    }
}
