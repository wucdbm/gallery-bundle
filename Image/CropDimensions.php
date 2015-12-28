<?php

namespace Wucdbm\Bundle\GalleryBundle\Image;

class CropDimensions {

    protected $type;

    protected $width;

    protected $height;

    protected $x1;

    protected $x2;

    protected $y1;

    protected $y2;

    protected $config;

    /**
     * @return mixed
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getX1() {
        return $this->x1;
    }

    /**
     * @param mixed $x1
     */
    public function setX1($x1) {
        $this->x1 = $x1;
    }

    /**
     * @return mixed
     */
    public function getX2() {
        return $this->x2;
    }

    /**
     * @param mixed $x2
     */
    public function setX2($x2) {
        $this->x2 = $x2;
    }

    /**
     * @return mixed
     */
    public function getY1() {
        return $this->y1;
    }

    /**
     * @param mixed $y1
     */
    public function setY1($y1) {
        $this->y1 = $y1;
    }

    /**
     * @return mixed
     */
    public function getY2() {
        return $this->y2;
    }

    /**
     * @param mixed $y2
     */
    public function setY2($y2) {
        $this->y2 = $y2;
    }

}