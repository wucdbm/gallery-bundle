<?php

namespace Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy;

use Wucdbm\Bundle\GalleryBundle\Entity\Image;

class NoneStrategy extends AbstractSubDirStrategy {

    protected $defaults = [];

    public function doGetSubDirectory(Image $image, array $options) {
        return '';
    }

}