<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Config;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class ConfigChoiceType extends AbstractType {

    /**
     * @var array
     */
    protected $choices = [];

    protected $configs = [];

    /**
     * ConfigChoiceType constructor.
     * @param array $configs
     */
    public function __construct(array $configs = []) {
        $this->configs = $configs;
        foreach ($configs as $choiceName => $choiceConfig) {
            $this->choices[$choiceName] = $choiceName;
        }
    }


    public function getParent() {
        return ChoiceType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'choices'      => $this->choices,
            'choice_value' => function ($config) {
                return $config;
            },
            'choice_label' => function ($config) {
                return $this->configs[$config]['name'];
            }
        ]);
    }

}
