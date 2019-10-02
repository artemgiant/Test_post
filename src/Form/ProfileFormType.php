<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Country;
use Symfony\Component\Validator\Constraints\File;

class ProfileFormType extends AbstractType
{
    private $UserCountry;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->UserCountry = ($options['data']->country)?:null;

        $builder
            ->add('avatarFile', FileType::class,[
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]

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
                'choice_attr' => function(country $category, $key, $value) {
                    $selected = false;
                    if($category->getName()=="Ukraine" && $this->UserCountry == null){
                        $selected = true;
                    };
                    return [
                        'class'=>'form-control border-right-0 '.$selected,
                        'selected'=>$selected,
                        'autocomplete'=>'off',
                    ];
                },
            ])
            ->add('regionOblast',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
//                    'id'=>'country',
                    'placeholder'=>'Enter region',
                    'autocomplete'=>'off'],
                'label'=>'regionOblast',
                'required'=>true
            ])

            ->add('city',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
//                    'id'=>'country',
                    'placeholder'=>'Enter city',
                    'autocomplete'=>'off'],
                'label'=>'city',
                'required'=>true
            ])
            ->add('zip',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
//                    'id'=>'country',
                    'placeholder'=>'Enter ZIP',
                    'autocomplete'=>'off'],
                'label'=>'zip',
                'required'=>true
            ])
            ->add('adress',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'street',
                    'placeholder'=>'Enter adress',
                    'autocomplete'=>'off'],
                'label'=>'adress',
                'required'=>true
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
