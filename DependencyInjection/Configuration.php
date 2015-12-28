<?php

namespace Wucdbm\Bundle\GalleryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wucdbm_gallery');

        $rootNode
            ->children()
                ->arrayNode('configs')
                    ->isRequired()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('aspect_ratios')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->integerNode('width')->end()
                            ->integerNode('height')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sizes')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->integerNode('width')->end()
                            ->integerNode('height')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}