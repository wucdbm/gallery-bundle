<?php

namespace Wucdbm\Bundle\GalleryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SubDirStrategyCompiler implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (!$container->has('wucdbm_gallery.image.sub_dir_strategy.container')) {
            return;
        }

        $definition = $container->findDefinition('wucdbm_gallery.image.sub_dir_strategy.container');

        $taggedServices = $container->findTaggedServiceIds('wucdbm_gallery.sub_dir_strategy');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addStrategy',
                [new Reference($id)]
            );
        }
    }

}