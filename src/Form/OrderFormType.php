<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use App\Entity\OrderProducts;
use App\Entity\OrderType;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;

use Doctrine\ORM\QueryBuilder;


use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrderFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user=$options['attr']['user'];
        $builder

            ->add('orderType', EntityType::class, [
                'class' => OrderType::class,
                'placeholder' => 'Select type',
                'choice_label' => 'name',
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control',
                    'id'=>'order_type',
                    'autocomplete'=>'off',
                ],
            ])

            ->add('trackingNumber',null,[
                'attr'=>[
                        'class'=>'form-control border-right-0',
                        'id'=>'trackingNumber',
//                        'placeholder'=>'trackingNumber',
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
//            ->add('shipDate',DateType::class,[
//                'attr'=>[
//                    'class'=>'form-control border-right-0 datepicker',
//                    'id'=>'shipDate',
////                    'placeholder'=>'shipDate',
//                    'autocomplete'=>'off'],
//                'label'=>'shipDate',
//                'widget' => 'single_text',
//                'html5' => false,
//                'format' => 'dd-MM-yyyy',
//                'required'=>true
//            ])
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
            ->add('products', CollectionType::class, array(
                'entry_type' => OrderProductsFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty'=>true,
                'label' => false,
                'by_reference' => false,
                'attr'=>[
                    'class'=>'product-list'
                ],
                'entry_options' => array(
                    'orderId'=>null,
                    'empty_data' => null
                )
            ))
           ->add('addresses', EntityType::class, [
               'class'        => Address::class,
               'query_builder' => function(AddressRepository $repo) use ($user) {
                   return $repo->getAdressListQuery($user);
               },
                'choice_label' => 'fullName',
                'label'        => 'addresses',
                'expanded'     => false,
                'multiple'     => false,
                'attr'         => [
                                    'class' =>'custom-select custom-select-mb',
                ],
            ])
            ->add('comment',null,[
                'attr'=>[
                    'class'=>'form-control',
                    'id'=>'country',
//                    'placeholder'=>'comment',
                    'autocomplete'=>'off'],
                'label'=>'comment',
                'required'=>false
            ])

            ->add('sendDetailWeight',null,[
                'attr'=>[
                    'class'=>'form-control',
//                    'placeholder'=>'sendDetailWeight placeholder',
                    'autocomplete'=>'off'],
                'label'=>'sendDetailWeight',
                'required'=>true
            ])
            ->add('sendDetailLength',null,[
                'attr'=>[
                    'class'=>'form-control',
//                    'placeholder'=>'sendDetailLength placeholder',
                    'autocomplete'=>'off'],
                'label'=>'sendDetailLength',
                'required'=>true
            ])
            ->add('sendDetailWidth',null,[
                'attr'=>[
                    'class'=>'form-control',
//                    'placeholder'=>'sendDetailWidth placeholder',
                    'autocomplete'=>'off'],
                'label'=>'sendDetailWidth',
                'required'=>true
            ])
            ->add('sendDetailHeight',null,[
                'attr'=>[
                    'class'=>'form-control',
//                    'placeholder'=>'sendDetailHeight placeholder',
                    'autocomplete'=>'off'],
                'label'=>'sendDetailHeight',
                'required'=>true
            ])
            ->add('shippingCosts',null,[
                'attr'=>[
                    'id'=>'shippingCosts',
                    'class'=>'form-control',
                    'readonly'=>true,
                    'autocomplete'=>'off'],
                'label'=>'shippingCosts',
                'required'=>false
            ])
            ->add('volumeWeigth',null,[
                'attr'=>[
                    'id'=>'volumeWeigth',
                    'class'=>'form-control',
                    'readonly'=>true,
                    'autocomplete'=>'off'],
                'label'=>'volumeWeigth',
                'required'=>false
            ])
            ->add('declareValue',null,[
                'attr'=>[
                    'id'=>'declareValue',
                    'class'=>'form-control',
                    'readonly'=>true,
                    'autocomplete'=>'off'],
                'label'=>'declareValue',
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
