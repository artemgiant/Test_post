<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'country',
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
//                'empty_data'=>' ',
//                'required'=>false
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
            ->add('address',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'street',
                    'placeholder'=>'Enter adress',
                    'autocomplete'=>'off'],
                'label'=>'adress',
                'required'=>true
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

//            ->add('userSecondName',null,[
//                'attr'=>[
//                    'class'=>'form-control border-right-0',
//                    'id'=>'userSecondName',
//                    'placeholder'=>'Enter second name',
//                    'autocomplete'=>'off'],
//                'label'=>'userSecondName',
//                'required'=>false
//            ])
            ->add('phone',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'phone',
                    'placeholder'=>"Enter receivers phone",
                    'autocomplete'=>'off'],
                'label'=>'phone',
                'required'=>true
            ])
            ->add('aliasOfAddress',null,[
            'attr'=>[
                'class'=>'form-control border-right-0',
                'id'=>'aliasOfAddress',
                'placeholder'=>"Enter alias of address",
                'autocomplete'=>'off'],
            'label'=>'aliasOfAddress',
            'required'=>true
            ])
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
