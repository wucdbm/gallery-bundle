<?php

namespace Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\GalleryBundle\Entity\Image;

abstract class AbstractSubDirStrategy {

    protected $defaults = [];

    /**
     * @var OptionsResolver
     */
    protected $resolver;

    public function __construct() {
        $this->resolver = new OptionsResolver();
        $this->resolver->setDefaults($this->defaults);
    }

    protected function processOptions($options) {
        return $this->resolver->resolve($options);
    }

    abstract protected function doGetSubDirectory(Image $image, array $options);

    public function getSubDirectory(Image $image, array $options) {
        $options = $this->processOptions($options);

        return $this->doGetSubDirectory($image, $options);
    }

    public function getName() {
        $fqcn = get_class($this);
        if (preg_match('~([^\\\\]+?)(strategy)?$~i', $fqcn, $matches)) {
            return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), $matches[1]));
        }
    }

}