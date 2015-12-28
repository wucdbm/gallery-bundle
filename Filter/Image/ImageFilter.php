<?php

namespace Wucdbm\Bundle\GalleryBundle\Filter\Image;

use Wucdbm\Bundle\GalleryBundle\Entity\Config;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class ImageFilter extends AbstractFilter {

    /** @var  string */
    protected $name;

    /** @var  Config */
    protected $config;

    /**
     * @return Config
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

}