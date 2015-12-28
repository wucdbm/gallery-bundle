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

    /**
     * ConfigChoiceType constructor.
     * @param array $configs
     */
    public function __construct(array $configs = []) {
        foreach ($configs as $choiceName => $choiceConfig) {
            $this->choices[$choiceConfig['name']] = $choiceName;
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
            'choices' => $this->choices
        ]);
    }

}
