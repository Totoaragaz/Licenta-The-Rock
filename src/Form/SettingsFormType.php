<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttribute('method', 'POST')
            ->add('darkMode', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    "onclick" => "reloadCss()"
                ]
            ])
            ->add('mainColumn', CheckboxType::class, [
                'required' => false,
            ])
            ->add('friendColumn', CheckboxType::class, [
                'required' => false,
            ])
            ->add('chatColumn', CheckboxType::class, [
                'required' => false,
            ])
            ->add('chatWarning', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'settings-button',
                    'onClick' => 'settings',
                ],
                'label' => 'settings.saveChanges'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
