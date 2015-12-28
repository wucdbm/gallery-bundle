<?php

namespace Wucdbm\Bundle\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\GalleryBundle\Filter\Image\ImageFilter;
use Wucdbm\Bundle\GalleryBundle\Form\Image\FilterType;

class GalleryController extends Controller {

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

    // TODO: Fix this crap w JMS Serializer
    public function pictureJsonAction($id, Request $request) {
        $filter = new ImageFilter();
        $filter->id = $id;
        $filter->join_sources = 1;
//        $filter->setOption(ImageFilter::OPTION_HYDRATION, ImageFilter::OPTION_HYDRATION_ARRAY);
        $repo = $this->get('app.repo.images');
        $image = $repo->filterOne($filter);
        $thumbWidth = $request->query->get('thumbWidth');
        $thumbId = $request->query->get('thumbId');
        $data = array(
            'id'            => $image->getId(),
            'md5'           => $image->getMd5(),
            'date_uploaded' => $image->getDateUploaded(),
            'extension'     => image_type_to_extension($image->getExtension()),
            'width'         => $image->getWidth(),
            'height'        => $image->getHeight(),
            'name'          => $image->getName(),
            'description'   => $image->getDescription(),
            'sources'       => $image->getSourcesCsv(),
            'path'          => $this->get('hashtag.twig.extension.img')->img($image, $thumbWidth, $thumbId)
        );

//        return new Response('');
        return new JsonResponse($data);
    }

}