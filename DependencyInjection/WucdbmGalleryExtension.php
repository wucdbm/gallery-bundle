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

        $ratios = $config['aspect_ratios'];
        $sizes = $config['sizes'];

        $bag->set('wucdbm_gallery.aspect_ratios', $ratios);
        $bag->set('wucdbm_gallery.sizes', $sizes);
        $configs = $config['configs'];
        foreach ($configs as $key => $conf) {
            $defaults = $conf['defaults'];
            if ($defaults['ratio'] && !isset($ratios[$defaults['ratio']])) {
                throw new \Exception(sprintf('Ratio "%s" not found in the configured ratios', $defaults['ratio']));
            }
            if ($defaults['size'] && !isset($sizes[$defaults['size']])) {
                throw new \Exception(sprintf('Size "%s" not found in the configured ratios', $defaults['size']));
            }
            $configs[$key]['key'] = $key;
        }
        $bag->set('wucdbm_gallery.configs', $configs);

        $loader->load('services/forms.xml');
        $loader->load('services/managers.xml');
        $loader->load('services/repositories.xml');
        $loader->load('services/sub_dir_strategies.xml');
        $loader->load('services/twig.xml');
    }

    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config/';
    }

    public function getNamespace() {
        return 'http://www.example.com/symfony/schema/';
    }

}