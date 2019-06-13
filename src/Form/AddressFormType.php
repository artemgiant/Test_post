<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AddressFormType extends AbstractType
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
                'required'=>true
                ])
            ->add('regionOblast',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'regionOblast',
                    'autocomplete'=>'off'],
                'label'=>'regionOblast',
                'required'=>true
            ])
            ->add('regionRayon',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'regionRayon',
                    'autocomplete'=>'off'],
                'label'=>'regionRayon',
                'required'=>false
            ])
            ->add('city',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'city',
                    'autocomplete'=>'off'],
                'label'=>'city',
                'required'=>true
            ])
            ->add('zip',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'zip',
                    'autocomplete'=>'off'],
                'label'=>'zip',
                'required'=>true
            ])
            ->add('street',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'street',
                    'placeholder'=>'zip',
                    'autocomplete'=>'off'],
                'label'=>'street',
                'required'=>true
            ])
            ->add('house',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'zip',
                    'placeholder'=>'zip',
                    'autocomplete'=>'off'],
                'label'=>'house',
                'required'=>true
            ])
            ->add('appartment',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'appartment',
                    'placeholder'=>'appartment',
                    'autocomplete'=>'off'],
                'label'=>'appartment',
                'required'=>false
            ])
            ->add('userFirstName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userFirstName',
                    'placeholder'=>'userFirstName',
                    'autocomplete'=>'off'],
                'label'=>'userFirstName',
                'required'=>true
            ])
            ->add('userLastName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userLastName',
                    'placeholder'=>'userLastName',
                    'autocomplete'=>'off'],
                'label'=>'userLastName',
                'required'=>true
            ])
            ->add('userSecondName',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'userSecondName',
                    'placeholder'=>'userSecondName',
                    'autocomplete'=>'off'],
                'label'=>'userSecondName',
                'required'=>false
            ])
            ->add('phone',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'phone',
                    'placeholder'=>'phone',
                    'autocomplete'=>'off'],
                'label'=>'phone',
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
