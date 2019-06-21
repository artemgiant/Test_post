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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Country;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatarFile', FileType::class,[
                'required'=>false,
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
                    'readonly' => true,
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
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
//                        'placeholder'=>'Country',
                    'autocomplete'=>'off'],
                'choice_label' => 'name',
            ])
            ->add('regionOblast',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'Enter region',
                    'autocomplete'=>'off'],
                'label'=>'regionOblast',
                'required'=>true
            ])
//            ->add('regionRayon',null,[
//                'attr'=>[
//                    'class'=>'form-control border-right-0',
//                    'id'=>'country',
//                    'placeholder'=>'Enter district',
//                    'autocomplete'=>'off'],
//                'label'=>'regionRayon',
//                'required'=>false,
//                'empty_data'=> ' ',
//            ])
            ->add('city',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'Enter city',
                    'autocomplete'=>'off'],
                'label'=>'city',
                'required'=>true
            ])
            ->add('zip',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'Enter ZIP',
                    'autocomplete'=>'off'],
                'label'=>'zip',
                'required'=>true
            ])
            ->add('street',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'street',
                    'placeholder'=>'Enter street',
                    'autocomplete'=>'off'],
                'label'=>'street',
                'required'=>true
            ])
            ->add('house',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'zip',
                    'placeholder'=>'Enter number house',
                    'autocomplete'=>'off'],
                'label'=>'house',
                'required'=>true
            ])
//            ->add('apartment',null,[
//                'attr'=>[
//                    'class'=>'form-control border-right-0',
//                    'id'=>'apartment',
//                    'placeholder'=>'Enter number apartment',
//                    'autocomplete'=>'off'],
//                'label'=>'apartment',
//                'required'=>false,
//                'empty_data'=> ' ',
//            ])
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
