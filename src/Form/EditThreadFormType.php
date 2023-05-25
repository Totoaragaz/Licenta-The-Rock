<?php

declare(strict_types=1);

namespace App\Form;

use App\Constants\ErrorMessages;
use App\Entity\Thread;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class EditThreadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '10M',
                            'maxSizeMessage' => ErrorMessages::MAXIMUM_IMAGE_SIZE,
                            'mimeTypes' => ['image/png', 'image/jpg', 'image/bmp', 'image/jpeg'],
                            'mimeTypesMessage' => ErrorMessages::INSERT_VALID_IMAGE,
                        ]),
                    ])
                ],
                'attr' => [
                    'style' => 'display: none'
                ],
                'label' => '<i class="fa fa-paperclip"></i>',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'create-thread-add-image-button'
                ]
            ])
            ->add('content', CollectionType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'entry_type' => TextareaType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => 'create-thread-content-text',
                        ],
                    'required' => false,
                    'label' => false,
                ],
                'allow_add' => true,
                'prototype' => true,
                'attr' => [
                    'class' => 'view-thread-content',
                    'data-index' => 0
                ]
            ])
            ->add('closed', CheckboxType::class, [
                'label' => 'editThread.closeThread',
                'required' => false,
                'label_attr' => [
                    'class => edit-thread-close-thread'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'editThread.saveChanges',
                'attr' => [
                    'class' => 'view-thread-submit-button',
                    'onClick' => 'editThread'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class,
            'validation_groups' => ['no-validation']
        ]);
    }
}
