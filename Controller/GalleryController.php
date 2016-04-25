<?php

namespace Wucdbm\Bundle\GalleryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Filter\Image\ImageFilter;
use Wucdbm\Bundle\GalleryBundle\Form\Image\FilterType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class GalleryController extends BaseController {

    public function galleryAction(Request $request) {
        $filter = new ImageFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(FilterType::class, $filter);
        $filter->load($request, $filterForm);
        $repo = $this->get('wucdbm_gallery.repo.images');
        $images = $repo->filter($filter);

        $data = [
            'images'     => $images,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmGallery/Gallery/browse.html.twig', $data);
    }

    public function imageJsonAction($id) {
        $manager = $this->container->get('wucdbm_gallery.manager.images');
        $image = $manager->getImage($id);

        $data = [
            'id'            => $image->getId(),
            'md5'           => $image->getMd5(),
            'date_uploaded' => $image->getDateUploaded()->format('Y-m-d H:i:s'),
            'extension'     => image_type_to_extension($image->getExtension()),
            'width'         => $image->getWidth(),
            'height'        => $image->getHeight(),
            'name'          => $image->getName(),
            'alt'           => $image->getAlt(),
            'path'          => $manager->getImagePath($image),
            'url'           => $manager->getImageUrl($image)
        ];

        return $this->json($data);
    }

    public function refreshAction(Image $image) {
        $data = [
            'image' => $image
        ];

        return $this->render('@WucdbmGallery/Gallery/browse_image.html.twig', $data);
    }

}