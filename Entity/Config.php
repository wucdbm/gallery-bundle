<?php

namespace Wucdbm\Bundle\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\GalleryBundle\Repository\ConfigRepository")
 * @ORM\Table(name="_wucdbm__gallery_configs",
 *      options={"collate"="utf8_general_ci"},
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="key", columns={"config_key"})
 *      }
 * )
 */
class Config {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="config_key", type="string", nullable=false)
     */
    protected $key;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\GalleryBundle\Entity\Image", mappedBy="config")
     */
    protected $images;

    /**
     * Constructor
     */
    public function __construct() {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Config
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return Config
     */
    public function setKey($key) {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Add image
     *
     * @param \Wucdbm\Bundle\GalleryBundle\Entity\Image $image
     *
     * @return Config
     */
    public function addImage(\Wucdbm\Bundle\GalleryBundle\Entity\Image $image) {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Wucdbm\Bundle\GalleryBundle\Entity\Image $image
     */
    public function removeImage(\Wucdbm\Bundle\GalleryBundle\Entity\Image $image) {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
    }
}
