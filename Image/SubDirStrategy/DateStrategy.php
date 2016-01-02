<?php

namespace Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy;

use Wucdbm\Bundle\GalleryBundle\Entity\Image;

class DateStrategy extends AbstractSubDirStrategy {

    protected $defaults = [
        'format'     => 'Ymd',
        'include_id' => true
    ];

    public function doGetSubDirectory(Image $image, array $options) {
        $subDirs = [];

        $formats = str_split($options['format']);
        foreach ($formats as $format) {
            $subDirs[] = $image->getDateUploaded()->format($format);
        }

        $subDir = implode(DIRECTORY_SEPARATOR, $subDirs);

        if ($options['include_id']) {
            return $subDir . DIRECTORY_SEPARATOR . $image->getId();
        }

        return $subDir;
    }

}