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
                            ->scalarNode('path')->end()
                            ->scalarNode('host')->end()
                            ->scalarNode('temp')->end()
                            ->scalarNode('name')->end()
                            ->arrayNode('strategy')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->variableNode('name')->defaultValue('identifier')->end()
                                    ->variableNode('options')->defaultValue([])->end()
                                ->end()
                            ->end()
                            ->arrayNode('defaults')
                                ->children()
                                    ->scalarNode('ratio')->defaultValue(null)->end()
                                    ->scalarNode('size')->defaultValue(null)->end()
                                ->end()
                            ->end()
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