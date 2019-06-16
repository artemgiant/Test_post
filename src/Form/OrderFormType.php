<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trackingNumber',null,[
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'trackingNumber',
                        'placeholder'=>'trackingNumber',
                        'autocomplete'=>'off'],
                'label'=>'trackingNumber',
                'required'=>true
                ])
            /*
            ->add('shippingCompany',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'shipDate',
                    'placeholder'=>'shippingCompany',
                    'autocomplete'=>'off'],
                'label'=>'shippingCompany',
                'required'=>true
            ])
            */
            ->add('shipDate',DateType::class,[
                'attr'=>[
                    'class'=>'form-control border-right-0 datepicker',
                    'id'=>'shipDate',
                    'placeholder'=>'shipDate',
                    'autocomplete'=>'off'],
                'label'=>'shipDate',
                'widget' => 'single_text',
                'html5' => false,
                'required'=>true
            ])
            /*
            ->add('product',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'product',
                    'autocomplete'=>'off'],
                'label'=>'product',
                'required'=>false
            ])
            */
            ->add('addresses', EntityType::class, [
                'class'        => Address::class,
                'choice_label' => 'fullName',
                'label'        => 'Who is fighting in this round?',
                'expanded'     => false,
                'multiple'     => false,
            ])
            ->add('comment',null,[
                'attr'=>[
                    'class'=>'form-control border-right-0',
                    'id'=>'country',
                    'placeholder'=>'comment',
                    'autocomplete'=>'off'],
                'label'=>'comment',
                'required'=>false
            ])
        ->add('save', SubmitType::class, [
                                'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
                                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
