<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Image;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wucdbm\Bundle\GalleryBundle\Form\Config\ConfigChoiceType;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class UploadType extends AbstractType {

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
            ->add('image', FileType::class, [
                'label'       => 'Снимка',
                'constraints' => [
                    new Image([
                        'mimeTypes'        => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Image must be in PNG or JPG format'
                    ])
                ],
            ]);
    }

}
