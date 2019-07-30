<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Invoices;

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

class InvoiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('order',HiddenType::class)
            ->add('price',null,['label'=>'price','attr'=>['class'=>'form-control']])
            ->add('comment',null,['label'=>'comment','attr'=>['class'=>'form-control']])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-block btn-primary mt-3'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoices::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'InvoiceFormType';
    }
}
