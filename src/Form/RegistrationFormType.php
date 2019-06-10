<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',null,[
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'signupInputEmail1',
                        'placeholder'=>'Enter email',
                        'autocomplete'=>'off'],
                'label'=>'Email address',
                'required'=>true
                ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'signupInputPassword1',
                        'placeholder'=>'Password',
                        'autocomplete'=>'off'],
                'label'=>'Password',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
           ->add('rePlainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'signupInputPassword1',
                        'placeholder'=>'Retype Password',
                        'autocomplete'=>'off'],
                'label'=>'Retype Password',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
              ->add('agreed', CheckboxType::class, [
            'required' => true,
        ])
        ->add('save', SubmitType::class, [
                                'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
                                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
