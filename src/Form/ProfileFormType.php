<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatarFile', FileType::class,[
//                'data_class' => null,
//                'empty_data' => ' ',
                'required'=>false,
               /* 'constraints' => [
                    new NotBlank([
                        'message' => 'Please upload avatar',
                    ]),

                ],*/
            ])
            ->add('firstName',null,[
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'firs_name',
                        'placeholder'=>'',
                        'autocomplete'=>'off'],
                'label'=>'firs_name',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter first name',
                    ]),
                ],
            ])
            ->add('secondName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userSecondName',
                    'placeholder'=>'',
                    'autocomplete'=>'off'],
                'label'=>'userSecondName',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter second name',
                    ]),
                ],
            ])
            ->add('lastName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'last_name',
                    'placeholder'=>'',
                    'autocomplete'=>'off'],
                'label'=>'last_name',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter last name',
                    ]),
                ],
            ])
            ->add('email',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'email',
                    'placeholder'=>'',
                    'autocomplete'=>'off'],
                'label'=>'email',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter email',
                    ]),
                ],
            ])
            ->add('phone',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'phone',
                    'placeholder'=>'',
                    'autocomplete'=>'off'],
                'label'=>'phone',
                'required'=>true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter phone',
                    ]),
                ],
            ])
            ->add('save_avatar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-block btn-primary mt-3 mx-auto d-block'],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-block btn-primary mt-3 mx-auto d-block'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
