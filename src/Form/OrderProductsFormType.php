<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\OrderProducts;
use App\Entity\Address;

//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrderProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descEn', null, [
                'attr' => [
                    'class' => 'form-control',
                    'data-name'   => 'descEn',
                    'placeholder' => 'descEn',
                    'autocomplete' => 'off'],
                'label' => 'descEn',
                'required' => true
            ])
            ->add('price', null, [
                'attr' => [
                    'class' => 'form-control',
                    'data-name'   => 'price',
                    'placeholder' => 'price',
                    'autocomplete' => 'off'],
                'label' => 'price',
                'required' => false
            ])
            ->add('count', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'data-name'   => 'count',
                    'placeholder' => 'count',
                    'autocomplete' => 'off'],
                'label' => 'count',
                'required' => false
            ])
            ->add('orderId', HiddenType::class,array(
                'data' => $options['orderId'],
                'required'          => false
            ));
       // ->add('save', SubmitType::class, [
       //                         'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
       //                         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderProducts::class,
            'orderId' => null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'OrderProductsFormType';
    }
}
