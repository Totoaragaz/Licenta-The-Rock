<?php

namespace App\Form;

use App\Constants\ErrorMessages;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttribute('method', 'POST')
            ->add('bio', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'profile-bio',
                ],
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => ErrorMessages::BIO_LENGTH_ERROR,
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'profile-picture img',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => ErrorMessages::MAXIMUM_IMAGE_SIZE,
                        'mimeTypes' => ['image/png', 'image/jpg', 'image/bmp', 'image/jpeg'],
                        'mimeTypesMessage' => ErrorMessages::INSERT_VALID_IMAGE,
                    ]),
                ],
                'label' => 'profile.changeProfilePicture',
                'label_attr' => [
                    'class' => 'profile-change-picture'
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'profile-button',
                    'onClick' => 'editProfile',
                ],
                'label' => 'profile.save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
