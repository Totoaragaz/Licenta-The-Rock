<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttribute('method', 'POST')
            ->add('content', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'viewThread.comment.placeholder',
                    'class' => 'comment-textarea',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'view-thread-submit-button',
                ],
                'label' => 'viewThread.submitComment',
                'label_html' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'validation_groups' => ['no-validation']
        ]);
    }
}
