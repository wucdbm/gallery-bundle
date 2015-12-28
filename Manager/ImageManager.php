<?php

namespace Wucdbm\Bundle\GalleryBundle\Manager;

use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Exception\ConfigNotFoundException;
use Wucdbm\Bundle\GalleryBundle\Image\CropDimensions;
use Wucdbm\Bundle\WucdbmBundle\Manager\AbstractManager;

/**
 * TODO: Subdir strategies:
 * 1. none
 * 2. date
 * 3. id%something
 * 4. custom
 * dir_structure:
 *      strategy: id%something
 *      options:
 *          some: option // use options resolver here
 * TODO: Default aspect ratio
 * Class ImageManager
 * @package Wucdbm\Bundle\GalleryBundle\Manager
 */
class ImageManager extends AbstractManager {

    public function getAspectRatios() {
        return $this->container->getParameter('wucdbm_gallery.aspect_ratios');
    }

    public function getSizes() {
        return $this->container->getParameter('wucdbm_gallery.sizes');
    }

    public function getConfigs() {
        return $this->container->getParameter('wucdbm_gallery.configs');
    }

    /**
     * @param $key
     * @return mixed
     * @throws ConfigNotFoundException
     */
    public function getConfig($key) {
        $configs = $this->getConfigs();
        if (!isset($configs[$key])) {
            throw new ConfigNotFoundException(sprintf('Config %s not found', $key));
        }

        return $configs[$key];
    }

    public function findConfigEntity($key) {
        $config = $this->getConfig($key);
        $repo = $this->container->get('wucdbm_gallery.repo.configs');
        $name = $config['name'];

        return $repo->saveIfNotExists($key, $name);
    }

    /**
     * @param UploadedFile $file
     * @return UploadedFile
     */
    public function moveFileToTemp(UploadedFile $file) {
        $newFile = $file->move($this->getTempDir(), $this->getFileMd5Name($file));

        return $newFile;
    }

    protected function getTempDir() {
        return $this->container->getParameter('uploads.images.temp');
    }

    public function getFileMd5Name(UploadedFile $file) {
        $ext = $file->guessExtension();
        $md5 = md5_file($file->getRealPath());
        $name = $md5 . '.' . $ext;

        return $name;
    }

    public function getTempFilePath($name) {
        $dir = $this->getTempDir();

        return $dir . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * @param $name
     * @param CropDimensions $dimensions
     * @return File
     */
    public function cropTempImage($name, CropDimensions $dimensions) {
        $path = $this->getTempFilePath($name);
        $oldExtension = pathinfo($path, PATHINFO_EXTENSION);
        $img = ImageManagerStatic::make($path);
        $img->crop($dimensions->getWidth(), $dimensions->getHeight(), $dimensions->getX1(), $dimensions->getY1());

        $unique = uniqid();
        // Just if two people would edit the same image. Rather unlikely, but just in case
        $tempPath = $this->getTempFilePath('_crop_' . $unique . '_' . $name);
        $img->save($tempPath);
        $img->destroy();

        $file = new File($tempPath);
        $md5 = md5_file($tempPath);
        $newName = $md5 . '.' . $oldExtension;
//        $newName = $md5 . image_type_to_extension($dimensions->getType());
        $file = $file->move($this->getTempDir(), $newName);

        return $file;
    }

    public function save(Image $image) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');
        $repo->save($image);
    }

    public function getImage($id) {
        return $this->container->get('wucdbm_gallery.repo.images')->findOneById($id);
    }

    public function getImageByMd5AndConfigId($md5, $configId) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');

        return $repo->findOneByMd5AndConfigId($md5, $configId);
    }

    public function createEntityFromPath($path) {
        $img = ImageManagerStatic::make($path);
        $md5 = md5_file($path);
        $extensionId = exif_imagetype($path);

        $image = new Image();
        $image->setMd5($md5);
        $image->setWidth($img->width());
        $image->setHeight($img->height());
        $image->setExtension($extensionId);

        return $image;
    }

    public function saveFile($name, Image $image) {
        $path = $this->getTempFilePath($name);
        $file = new File($path);
        $dir = $this->getImageDirectory($image);
        $file = $file->move($dir, $name);

        return $file;
    }

    public function getImageDirectory(Image $image) {
        $imagesDir = $this->getImagesDir();
        $path = $this->getImageSubDir($image);
        $dir = $imagesDir . DIRECTORY_SEPARATOR . $path;

        return $dir;
    }

    protected function getImageSubDir(Image $image) {
        // TODO: Add config option - mandatory, this should be selected based on the current image sub dir strategy config
        $subDir = $image->getId() % 100;

        return $subDir . DIRECTORY_SEPARATOR . $image->getId();
    }

    protected function getImagesDir() {
        return $this->container->getParameter('uploads.images.path');
    }

    public function remove(Image $image) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');
        $repo->remove($image);
        $path = $this->getImagePath($image);
        if (file_exists($path)) {
            unlink($path);
        }
    }


    // TODO: Down below

    protected function getImageName(Image $image) {
        $extension = image_type_to_extension($image->getExtension());

        return $image->getMd5() . $extension;
    }


    public function getImageUrl(Image $image) {
        $host = $this->container->getParameter('uploads.images.host');
        $subDir = $this->getImageSubDir($image);
        $name = $this->getImageName($image);

        return $host . '/' . $subDir . '/' . $name;
    }

    public function getImagePath(Image $image) {
        $path = $this->getImagePath($image);
        $name = $this->getImageName($image);

        return $path . DIRECTORY_SEPARATOR . $name;
    }

}