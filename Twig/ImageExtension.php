<?php

namespace Wucdbm\Bundle\GalleryBundle\Twig;

use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Manager\ImageManager;

class ImageExtension extends \Twig_Extension {

    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function __construct(ImageManager $manager) {
        $this->imageManager = $manager;
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('wucdbmGalleryImage', [$this, 'wucdbmGalleryImage']),
        ];
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('wucdbmGalleryImage', [$this, 'wucdbmGalleryImage']),
        ];
    }

    public function wucdbmGalleryImage(Image $image) {
        return $this->imageManager->getImageUrl($image);
    }

    public function getName() {
        return 'wucdbm_gallery_image';
    }

    public function getAlias() {
        return 'wucdbm_gallery_image';
    }

}
