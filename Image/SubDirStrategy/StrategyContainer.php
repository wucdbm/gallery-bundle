<?php

namespace Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy;

use Wucdbm\Bundle\GalleryBundle\Image\SubDirStrategy\Exception\SubDirStrategyNotFound;

class StrategyContainer {

    protected $strategies = [];

    /**
     * @param AbstractSubDirStrategy $strategy
     */
    public function addStrategy(AbstractSubDirStrategy $strategy) {
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * @param $name
     * @return AbstractSubDirStrategy
     * @throws SubDirStrategyNotFound
     */
    public function getStrategy($name) {
        if (!isset($this->strategies[$name])) {
            throw new SubDirStrategyNotFound(sprintf('SubDir Strategy "%s" not found', $name));
        }

        return $this->strategies[$name];
    }
}