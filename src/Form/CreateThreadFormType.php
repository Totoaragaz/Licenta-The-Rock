<?php

declare(strict_types=1);

namespace App\Form;

use App\Constants\ErrorMessages;
use App\Entity\Thread;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateThreadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => ErrorMessages::ENTER_TITLE,
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => ErrorMessages::TITLE_LENGTH_ERROR,
                        'max' => 255,
                        'maxMessage' => ErrorMessages::TITLE_LENGTH_ERROR,
                    ]),
                ],
                'attr' => [
                    'class' => 'create-thread-title',
                    'placeholder' => 'createThread.title.placeholder',
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'create-thread-content',
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'input',
                    'style' => 'padding-bottom: 10px'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => ErrorMessages::MAXIMUM_IMAGE_SIZE,
                        'mimeTypes' => ['image/png', 'image/jpg', 'image/bmp', 'image/jpeg'],
                        'mimeTypesMessage' => ErrorMessages::INSERT_VALID_IMAGE,
                    ]),
                ],
                'label' => 'register.profilePicture',
                'label_attr' => [
                    'class' => 'label'
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'createThread.createThread',
                'attr' => [
                    'class' => 'button',
                    'onClick' => 'createThread();'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class
        ]);
    }
}
