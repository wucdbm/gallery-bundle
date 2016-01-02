<?php

namespace Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy;

use Wucdbm\Bundle\GalleryBundle\Entity\Image;

class IdentifierStrategy extends AbstractSubDirStrategy {

    protected $defaults = [
        'dirs' => 128
    ];

    public function doGetSubDirectory(Image $image, array $options) {
        $subDir = $image->getId() % $options['dirs'];

        return $subDir . DIRECTORY_SEPARATOR . $image->getId();
    }

}