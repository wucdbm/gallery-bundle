<?php

namespace Wucdbm\Bundle\GalleryBundle\Form\Image;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class UploadType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('image', FileType::class, [
                'label'       => 'Снимка',
                'constraints' => [
                    new Image([
                        'mimeTypes'        => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Логото трябва да е в PNG или JPG формат'
                    ])
                ],
            ]);
    }

}
