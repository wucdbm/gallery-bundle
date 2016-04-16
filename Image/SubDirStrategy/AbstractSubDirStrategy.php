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
        return array_pop(explode('\\', get_class()));
    }

}