<?php

namespace Wucdbm\Bundle\GalleryBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WucdbmGalleryExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $bag = $container->getParameterBag();

        $bag->set('wucdbm_gallery.config', $config);

        $bag->set('wucdbm_gallery.aspect_ratios', $config['aspect_ratios']);
        $bag->set('wucdbm_gallery.sizes', $config['sizes']);
        $configs = $config['configs'];
        foreach ($configs as $key => $conf) {
            $configs[$key]['key'] = $key;
        }
        $bag->set('wucdbm_gallery.configs', $configs);

        $loader->load('services/forms.xml');
        $loader->load('services/managers.xml');
        $loader->load('services/repositories.xml');
        $loader->load('services/twig.xml');
    }

    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config/';
    }

    public function getNamespace() {
        return 'http://www.example.com/symfony/schema/';
    }

}