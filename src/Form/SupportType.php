<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Name',TextType::class,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'placeholder'=>'Your name',
                    'autocomplete'=>'off'],
                'label'=>'Name',
                'required'=>true
            ])
            ->add('Email',TextType::class,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'autocomplete'=>'off'],
                'label'=>'Email',
                'required'=>true,
            ])
            ->add('Subject',TextType::class,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'autocomplete'=>'off'],
                'label'=>'Subject',
                'required'=>true
            ])
            ->add('Message',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'autocomplete'=>'off'],
                'label'=>'Message',
                'required'=>true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
