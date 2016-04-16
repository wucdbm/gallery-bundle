<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Image;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class SaveType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Name',
                'attr'        => [
                    'placeholder' => 'Name'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Name is mandatory'
                    ])
                ]
            ])
            ->add('alt', TextType::class, [
                'label'       => 'Alternative image text',
                'attr'        => [
                    'placeholder' => 'Alternative image text'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Alt is mandatory'
                    ])
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\GalleryBundle\Entity\Image'
        ]);
    }

}
