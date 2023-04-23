<?php

declare(strict_types=1);

namespace App\Form;

use App\Constants\ErrorMessages;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => ErrorMessages::ENTER_USERNAME,
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => ErrorMessages::FIELD_DOES_NOT_MEET_LENGTH_REQUIREMENT,
                        'max' => 30,
                        'maxMessage' => ErrorMessages::FIELD_DOES_NOT_MEET_LENGTH_REQUIREMENT,
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\-_]{1,40}$/',
                        'message' => ErrorMessages::NO_NONALPHANUMERIC,
                    ]),
                ],
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'register.username.placeholder',
                ],
                'label' => 'register.username',
                'label_attr' => [
                    'class' => 'label'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => ErrorMessages::ENTER_EMAIL,
                    ]),
                    new Regex([
                        'pattern' => '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
                        'message' => ErrorMessages::NO_NONALPHANUMERIC,
                    ]),
                ],
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'register.email.placeholder',
                ],
                'label' => 'register.email',
                'label_attr' => [
                    'class' => 'label'
                ]
            ])
            ->add('bio', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'input',
                ],
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => ErrorMessages::FIELD_DOES_NOT_MEET_LENGTH_REQUIREMENT,
                    ])
                ],
                'label' => 'register.bio',
                'label_attr' => [
                    'class' => 'label'
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'input',
                    'style' => 'padding-bottom: 30px'
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
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => ErrorMessages::PASSWORDS_MUST_MATCH,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'attr' => [
                        'class' => 'input',
                        'placeholder' => 'register.password.placeholder',
                    ],
                    'label' => 'register.password',
                    'label_attr' => [
                        'class' => 'label'
                    ]
                ],
                'second_options' => [
                    'label' => 'register.confirmPassword',
                    'label_attr' => [
                        'class' => 'label'
                    ],
                    'attr' => [
                        'class' => 'input',
                        'placeholder' => 'register.confirmPassword.placeholder',
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => ErrorMessages::FIELD_IS_REQUIRED,
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => ErrorMessages::PASSWORD_TOO_SHORT,
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=[A-Za-z0-9@#$%^&+!=]+$)^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,}).*$/',
                        'message' => ErrorMessages::FIELD_DOES_NOT_MEET_REQUIRED_COMPLEXITY,
                    ]),
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'register.register',
                'attr' => [
                    'class' => 'button',
                    'onClick' => 'register();'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
