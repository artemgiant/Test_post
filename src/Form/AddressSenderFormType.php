<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressSenderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country',null,[
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'country',
                        'placeholder'=>'Country',
                        'autocomplete'=>'off'],
                'label'=>'Country',
                'required'=>true,
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
            ->add('regionRayon',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'Enter district',
                    'autocomplete'=>'off'],
                'label'=>'regionRayon',
                'required'=>false
            ])
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
            ->add('apartment',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'apartment',
                    'placeholder'=>'Enter number apartment',
                    'autocomplete'=>'off'],
                'label'=>'apartment',
                'required'=>false,
                'empty_data'=>''
            ])
            ->add('userFirstName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userFirstName',
                    'placeholder'=>'Enter first name',
                    'autocomplete'=>'off'],
                'label'=>'userFirstName',
                'required'=>true
            ])
            ->add('userLastName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userLastName',
                    'placeholder'=>'Enter last name',
                    'autocomplete'=>'off'],
                'label'=>'userLastName',
                'required'=>true
            ])
            ->add('userSecondName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userSecondName',
                    'placeholder'=>'Enter second name',
                    'autocomplete'=>'off'],
                'label'=>'userSecondName',
                'required'=>false
            ])
            ->add('phone',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'phone',
                    'placeholder'=>"Enter receivers phone",
                    'autocomplete'=>'off'],
                'label'=>'phone',
                'required'=>true
            ])
//            ->add('aliasOfAddress',null,[
//            'attr'=>[
//                'class'=>'form-control border-right-0',
//                'id'=>'aliasOfAddress',
//                'placeholder'=>"Enter alias of address",
//                'autocomplete'=>'off'],
//            'label'=>'aliasOfAddress',
//            'required'=>true
//            ])
            ->add('save', SubmitType::class, [
                                'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
                                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
