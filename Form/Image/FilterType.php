<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Image;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\BaseFilterType;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\EntityFilterType;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType;

class FilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextFilterType::class, [
                'placeholder' => 'Name'
            ])
            ->add('config', EntityFilterType::class, [
                'class'        => 'Wucdbm\Bundle\GalleryBundle\Entity\Config',
                'placeholder'  => 'Config',
                'choice_label' => 'name'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\GalleryBundle\Filter\Image\ImageFilter'
        ]);
    }

}
