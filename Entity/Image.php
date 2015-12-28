<?php

namespace Wucdbm\Bundle\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\GalleryBundle\Repository\ImageRepository")
 * @ORM\Table(name="_wucdbm__gallery_images",
 *      options={"collate"="utf8_general_ci"},
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="md5_config", columns={"md5", "config_id"})
 *      }
 * )
 */
class Image {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="alt", type="string", nullable=false)
     */
    protected $alt;

    /**
     * @ORM\Column(name="md5", type="string", length=32, options={"fixed" = true})
     */
    protected $md5;

    /**
     * @ORM\Column(name="date_uploaded", type="datetime")
     */
    protected $dateUploaded;

    /**
     * @ORM\Column(name="width", type="smallint", options={"unsigned"=true})
     */
    protected $width;

    /**
     * @ORM\Column(name="height", type="smallint", options={"unsigned"=true})
     */
    protected $height;

    /**
     * @ORM\Column(name="extension", type="smallint", options={"unsigned"=true})
     */
    protected $extension;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\GalleryBundle\Entity\Config", inversedBy="images")
     * @ORM\JoinColumn(name="config_id", referencedColumnName="id")
     */
    protected $config;

    /**
     * Constructor
     */
    public function __construct() {
        $this->dateUploaded = new \DateTime();
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
     * Set md5
     *
     * @param string $md5
     *
     * @return Image
     */
    public function setMd5($md5) {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string
     */
    public function getMd5() {
        return $this->md5;
    }

    /**
     * Set dateUploaded
     *
     * @param \DateTime $dateUploaded
     *
     * @return Image
     */
    public function setDateUploaded($dateUploaded) {
        $this->dateUploaded = $dateUploaded;

        return $this;
    }

    /**
     * Get dateUploaded
     *
     * @return \DateTime
     */
    public function getDateUploaded() {
        return $this->dateUploaded;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Image
     */
    public function setWidth($width) {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Image
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set height
     *
     * @param integer $extension
     *
     * @return Image
     */
    public function setExtension($extension) {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
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
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt) {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt() {
        return $this->alt;
    }

    /**
     * Set product
     *
     * @param \Wucdbm\Bundle\GalleryBundle\Entity\Config $config
     *
     * @return Image
     */
    public function setConfig(\Wucdbm\Bundle\GalleryBundle\Entity\Config $config) {
        $this->config = $config;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Wucdbm\Bundle\GalleryBundle\Entity\Config
     */
    public function getConfig() {
        return $this->config;
    }
}
