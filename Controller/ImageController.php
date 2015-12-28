<?php

namespace Wucdbm\Bundle\GalleryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Exception\ConfigNotFoundException;
use Wucdbm\Bundle\GalleryBundle\Form\Image\CropType;
use Wucdbm\Bundle\GalleryBundle\Form\Image\SaveType;
use Wucdbm\Bundle\GalleryBundle\Form\Image\UploadType;
use Wucdbm\Bundle\GalleryBundle\Image\CropDimensions;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class ImageController extends BaseController {

    public function uploadAction($config, Request $request) {
        $form = $this->createForm(UploadType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['image']->getData();
            $manager = $this->container->get('wucdbm_gallery.manager.images');
            $file = $manager->moveFileToTemp($file);

            return $this->redirectToRoute('wucdbm_gallery_image_crop', [
                'config' => $config,
                'name'   => $file->getFilename()
            ]);
        }

        $data = [
            'config' => $config,
            'form'   => $form->createView()
        ];

        return $this->render('@WucdbmGallery/Image/upload/upload.html.twig', $data);
    }

    public function cropAction($config, $name, Request $request) {
        $manager = $this->get('wucdbm_gallery.manager.images');

        $dimensions = new CropDimensions();
        $dimensions->setConfig($config);
        $imagePath = $manager->getTempFilePath($name);
        $type = exif_imagetype($imagePath);
        $dimensions->setType($type);

        $form = $this->createForm(CropType::class, $dimensions, [
            'attr'   => [
                'id' => 'image_crop_form',
            ],
            'method' => 'POST',
            'action' => $this->generateUrl('wucdbm_gallery_image_crop', [
                'config' => $config,
                'name'   => $name
            ])
        ]);

        $form->handleRequest($request);

        $error = null;

        if ($form->isValid()) {
            try {
                ini_set('memory_limit', '256M');
                $file = $manager->cropTempImage($name, $dimensions);

                $post = $request->request->all();

                if (isset($post['cropAndEdit'])) {
                    return $this->redirectToRoute('wucdbm_gallery_image_crop', [
                        'config' => $dimensions->getConfig(),
                        'name'   => $file->getFilename()
                    ]);
                }

                return $this->redirectToRoute('wucdbm_gallery_image_save', [
                    'config' => $dimensions->getConfig(),
                    'name'   => $file->getFilename()
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $data = [
            'config' => $config,
            'name'   => $name,
            'image'  => $imagePath,
            'form'   => $form->createView(),
            'error'  => $error,
            'ratios' => $manager->getAspectRatios(),
            'sizes'  => $manager->getSizes()
        ];

        return $this->render('@WucdbmGallery/Image/crop/crop.html.twig', $data);
    }

    public function cropAndPreviewAction($config, $name, Request $request) {
        $manager = $this->get('wucdbm_gallery.manager.images');

        $dimensions = new CropDimensions();
        $imagePath = $manager->getTempFilePath($name);
        $type = exif_imagetype($imagePath);
        $dimensions->setType($type);

        $form = $this->createForm(CropType::class, $dimensions);

        $form->handleRequest($request);

        if ($form->isValid()) {
            ini_set('memory_limit', '256M');
            $file = $manager->cropTempImage($name, $dimensions);

            return $this->json([
                'url'     => $this->generateUrl('wucdbm_gallery_image_serve_temp', [
                    'config' => $config,
                    'name'   => $file->getFilename()
                ]),
                'success' => true
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = sprintf('<li>%s</li>', $error->getMessage());
        }

        $errors = implode('', $errors);

        $response = sprintf("The form has the following errors: <ul>%s</ul>", $errors);

        return $this->json([
            'success' => false,
            'error'   => $response,
            //
            'witter'  => [
                'title' => 'Error',
                'text'  => $response
            ]
        ]);
    }

    public function saveAction($config, $name, Request $request) {
        $manager = $this->get('wucdbm_gallery.manager.images');

        try {
            $configEntity = $manager->findConfigEntity($config);
        } catch (ConfigNotFoundException $ex) {
            $data = [
                'config' => $config
            ];

            return $this->render('@WucdbmGallery/Image/error/config_not_found.html.twig', $data);
        }


        $imagePath = $manager->getTempFilePath($name);

        $md5 = md5_file($imagePath);
        $image = $manager->getImageByMd5AndConfigId($md5, $configEntity->getId());

        if ($image instanceof Image) {
            return $this->redirectToRoute('wucdbm_gallery_image_edit', [
                'id' => $image->getId()
            ]);
        }

        $image = $manager->createEntityFromPath($imagePath);
        $image->setConfig($configEntity);

        $form = $this->createForm(SaveType::class, $image);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_gallery.manager.images');

            $manager->save($image);
            $manager->saveFile($name, $image);

            return $this->redirectToRoute('wucdbm_gallery_image_edit', [
                'id' => $image->getId()
            ]);
        }

        $data = [
            'form'      => $form->createView(),
            'image'     => $image,
            'config'    => $config,
            'name'      => $name,
            'imagePath' => $imagePath
        ];

        return $this->render('@WucdbmGallery/Image/save/save.html.twig', $data);
    }

    public function editAction(Image $image, Request $request) {
        $manager = $this->get('wucdbm_gallery.manager.images');

        $form = $this->createForm(SaveType::class, $image);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->save($image);
        }

        $data = [
            'form'   => $form->createView(),
            'image'  => $image,
            'config' => $image->getConfig()->getKey()
        ];

        return $this->render('@WucdbmGallery/Image/edit/edit.html.twig', $data);
    }

    public function serveTempAction($name) {
        // TODO: Maybe take advantage of the serve file library?
        $manager = $this->get('wucdbm_gallery.manager.images');
        $image = $manager->getTempFilePath($name);
        $file = new File($image);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Length', $file->getSize());
        $response->headers->set('Content-Disposition', 'inline; filename="' . $name . '"');
        $response->setContent(readfile($image));

        return $response;
    }

//    public function galleryAction($config) {
//        $repo = $this->container->get('app.repo.products.images');
//        $images = $repo->findByProductId($config);
//
//        $data = [
//            'images' => $images
//        ];
//
//        return $this->render('@Admin/Product/Image/gallery/gallery.html.twig', $data);
//    }
//
//    public function makeImageVisibleAction(Product\Image $image) {
//        return $this->setIsVisible($image, true);
//    }
//
//    public function makeImageInvisibleAction(Product\Image $image) {
//        return $this->setIsVisible($image, false);
//    }
//
//    public function setIsVisible(Product\Image $image, $bool) {
//        $manager = $this->container->get('app.manager.products.images');
//        $image->setIsVisible($bool);
//        $manager->save($image);
//
//        return $this->json([
//            'success' => true,
//            'refresh' => true
//        ]);
//    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Product\Image $image, Request $request) {
        if (!$request->request->get('is_confirmed')) {
            return $this->json([
                'success' => false
            ]);
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json([
                'success' => false,
                'bootbox' => 'Трябва да сте администратор, за да премахвате снимки.'
            ]);
        }
        $product = $image->getProduct();
        if ($product->getMainImage()->getId() == $image->getId()) {
            return $this->json([
                'success' => false,
                'bootbox' => 'Тази снимка е главната снимка на продутка. Моля, изберете друга преди да я изтриете'
            ]);
        }
        if ($product->getHoverImage()->getId() == $image->getId()) {
            return $this->json([
                'success' => false,
                'bootbox' => 'Тази снимка е ховър снимка на продутка. Моля, изберете друга преди да я изтриете'
            ]);
        }
        $manager = $this->container->get('app.manager.products.images');
        try {
            $manager->remove($image);
        } catch (\Exception $ex) {
            return $this->json([
                'success' => false,
                'bootbox' => 'Грешка: ' . $ex->getMessage()
            ]);
        }

        return $this->json([
            'success' => true,
            'refresh' => true,
            'bootbox' => 'Снимката беше успешно изтрита.'
        ]);
    }

}