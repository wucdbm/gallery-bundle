<?php

namespace Wucdbm\Bundle\GalleryBundle\Manager;

use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Wucdbm\Bundle\GalleryBundle\Entity\Config;
use Wucdbm\Bundle\GalleryBundle\Entity\Image;
use Wucdbm\Bundle\GalleryBundle\Exception\ConfigNotFoundException;
use Wucdbm\Bundle\GalleryBundle\Image\CropDimensions;
use Wucdbm\Bundle\WucdbmBundle\Manager\AbstractManager;

/**
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
     * @param Config $config
     * @return File
     */
    public function moveFileToTemp(UploadedFile $file, $config) {
        $newFile = $file->move($this->getTempDir($config), $this->getFileMd5Name($file));

        return $newFile;
    }

    protected function getTempDir($config) {
        $config = $this->getConfig($config);

        return $config['temp'];
    }

    public function getFileMd5Name(UploadedFile $file) {
        $ext = $file->guessExtension();
        $md5 = md5_file($file->getRealPath());
        $name = $md5 . '.' . $ext;

        return $name;
    }

    public function getTempFilePath($config, $name) {
        $dir = $this->getTempDir($config);

        return $dir . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * @param $name
     * @param CropDimensions $dimensions
     * @return File
     */
    public function cropTempImage($name, CropDimensions $dimensions) {
        $config = $dimensions->getConfig();
        $path = $this->getTempFilePath($config, $name);
        $oldExtension = pathinfo($path, PATHINFO_EXTENSION);
        $img = ImageManagerStatic::make($path);
        $img->crop($dimensions->getWidth(), $dimensions->getHeight(), $dimensions->getX1(), $dimensions->getY1());

        $unique = uniqid();
        // Just if two people would edit the same image. Rather unlikely, but just in case
        $name = '_crop_' . $unique . '_' . $name;
        $tempPath = $this->getTempFilePath($config, $name);
        $img->save($tempPath);
        $img->destroy();

        $file = new File($tempPath);
        $md5 = md5_file($tempPath);
        $newName = $md5 . '.' . $oldExtension;
        $file = $file->move($this->getTempDir($config), $newName);

        return $file;
    }

    public function save(Image $image) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');
        $repo->save($image);
    }

    public function getImage($id) {
        return $this->container->get('wucdbm_gallery.repo.images')->findOneById($id);
    }

    public function getMd5FromPath($name) {
        $matches = array();
        preg_match('/([0-9a-f]{32})\..*/', $name, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * @param $md5
     * @param $configId
     * @return null|Image
     */
    public function getImageByMd5AndConfigId($md5, $configId) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');

        return $repo->findOneByMd5AndConfigId($md5, $configId);
    }

    /**
     * @param $md5
     * @return Image[]
     */
    public function getImagesByMd5($md5) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');

        return $repo->findByMd5($md5);
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
        $config = $image->getConfig()->getKey();
        $path = $this->getTempFilePath($config, $name);
        $file = new File($path);
        $dir = $this->getImageDirectory($image);
        $file = $file->move($dir, $name);

        return $file;
    }

    public function getImageDirectory(Image $image) {
        $imagesDir = $this->getImagesDir($image->getConfig());
        $path = $this->getImageSubDir($image);
        $dir = $imagesDir . ($path ? DIRECTORY_SEPARATOR . $path : '');

        return $dir;
    }

    protected function getImageSubDir(Image $image) {
        $config = $this->getConfig($image->getConfig()->getKey());
        $strategy = $config['strategy'];
        $impl = $this->container->get('wucdbm_gallery.image.sub_dir_strategy.container')->getStrategy($strategy['name']);

        return $impl->getSubDirectory($image, $strategy['options']);
    }

    protected function getImagesDir(Config $config) {
        $config = $this->getConfig($config->getKey());

        return $config['path'];
    }

    public function remove(Image $image) {
        $repo = $this->container->get('wucdbm_gallery.repo.images');
        $repo->remove($image);
        $path = $this->getImagePath($image);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    protected function getImageName(Image $image) {
        $extension = image_type_to_extension($image->getExtension());

        return $image->getMd5() . $extension;
    }


    public function getImageUrl(Image $image) {
        $config = $this->getConfig($image->getConfig()->getKey());
        $host = $config['host'];
        $subDir = $this->getImageSubDir($image);
        $name = $this->getImageName($image);

        return sprintf($host, ($subDir ? $subDir . '/' : '') . $name);
    }

    public function getImagePath(Image $image) {
        $path = $this->getImageDirectory($image);
        $name = $this->getImageName($image);

        return $path . DIRECTORY_SEPARATOR . $name;
    }

}