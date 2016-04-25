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

    protected $test = [];

    /**
     * ConfigChoiceType constructor.
     * @param array $configs
     */
    public function __construct(array $configs = []) {
        $this->test = $configs;
        foreach ($configs as $choiceName => $choiceConfig) {
            var_dump($choiceName);
            var_dump($choiceConfig['name']);
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
            'choice_value' => function ($asd) {
                return $asd;
            },
            'choice_label' => function ($asd) {
                return $this->test[$asd]['name'];
            }
        ]);
    }

}
