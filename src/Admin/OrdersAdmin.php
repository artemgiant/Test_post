<?php

namespace App\Admin;

use App\Entity\OrderStatus;
use App\Entity\User;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrdersAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'orders';
    protected $baseRouteName = 'orders';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('orderStatus');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('id')
            ->add('user', null, [], EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('order_status', null, [], EntityType::class, [
                'class' => OrderStatus::class,
                'choice_label' => 'order_status',
            ])
            ->addIdentifier('sendFromAddress')
            ->addIdentifier('comment');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $userFieldOptions = [];
        $orderStatusFieldOptions = [];
        $addressesFieldOptions = [];

        $formMapper
            ->add('user', ModelType::class, $userFieldOptions)
            ->add('orderStatus', ModelType::class, $orderStatusFieldOptions)
            ->add('addresses', ModelType::class, $addressesFieldOptions)
            ->add('trackingNumber',null,['label'=>'Трек новой почты'])
            ->add('trNum',null,['label'=>'Трек для пользователя','disabled'=>true,'required'=>false])
            ->add('systemNum',null,['label'=>'Трек системный(Тот что меняет админ)','required'=>false])
            ->add('volumeWeigth')
            ->add('declareValue')
            ->add('sendFromAddress')
            ->add('sendFromIndex')
            ->add('sendFromCity')
            ->add('sendFromPhone')
            ->add('sendFromEmail')
            ->add('sendDetailPlaces')
            ->add('sendDetailWeight')
            ->add('sendDetailLength')
            ->add('sendDetailWidth')
            ->add('sendDetailHeight')
            ->add('comment')
            ->add('email')
            ->add('address')
            ->add('shippingCosts')
            ->add('deliveryStatus')
            ->add('country')
            ->add('fromCountry')
            ->add('city')
            ->add('zip')
            ->add('towarehouse')
            ->add('quantity');
    }
}
