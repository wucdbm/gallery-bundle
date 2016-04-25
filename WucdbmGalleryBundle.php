<?php

namespace Wucdbm\Bundle\GalleryBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wucdbm\Bundle\GalleryBundle\DependencyInjection\Compiler\SubDirStrategyCompiler;

class WucdbmGalleryBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new SubDirStrategyCompiler());
    }

}
