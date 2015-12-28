<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Image;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wucdbm\Bundle\GalleryBundle\Form\Config\ConfigChoiceType;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class CropType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('config', ConfigChoiceType::class, [
                'label'       => 'Config',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Config is mandatory'
                    ])
                ]
            ])
            ->add('width', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'Width',
                    'rel'         => 'tooltip',
                    'title'       => 'Width'
                ],
                'label'       => 'Width',
                'label_attr'  => [
                    'rel'   => 'tooltip',
                    'title' => 'Width'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Width is required'
                    ])
                ]
            ])
            ->add('height', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'Height',
                    'rel'         => 'tooltip',
                    'title'       => 'Height'
                ],
                'label'       => 'Height',
                'label_attr'  => [
                    'rel'   => 'tooltip',
                    'title' => 'Height'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Height is required'
                    ])
                ]
            ])
            ->add('x1', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'x1',
                    'class'       => 'jcrop-coord',
                    'id'          => 'x1'
                ],
                'label'       => 'X1',
                'constraints' => [
                    new NotBlank([
                        'message' => 'X1 is required'
                    ])
                ]
            ])
            ->add('x2', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'x2',
                    'class'       => 'jcrop-coord',
                    'id'          => 'x2'
                ],
                'label'       => 'X2',
                'constraints' => [
                    new NotBlank([
                        'message' => 'X2 is required'
                    ])
                ]
            ])
            ->add('y1', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'y1',
                    'class'       => 'jcrop-coord',
                    'id'          => 'y1'
                ],
                'label'       => 'Y1',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Y1 is required'
                    ])
                ]
            ])
            ->add('y2', IntegerType::class, [
                'attr'        => [
                    'placeholder' => 'y2',
                    'class'       => 'jcrop-coord',
                    'id'          => 'y2'
                ],
                'label'       => 'Y2',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Y2 is required'
                    ])
                ]
            ])
            ->add('cropAndSave', SubmitType::class, [
                'attr'  => [
                    'class' => 'btn btn-success',
                    'rel'   => 'tooltip',
                    'title' => 'Запис на снимката - преминава се към страничката за записване на данните на снимката'
                ],
                'label' => 'Запис'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\GalleryBundle\Image\CropDimensions'
        ]);
    }

}
