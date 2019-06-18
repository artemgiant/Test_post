<?php

namespace App\Admin;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PaymentsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'payments';
    protected $baseRouteName = 'payments';

    /**
     * @param \Sonata\AdminBundle\Route\RouteCollection $collection
     */
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection
               ->remove('edit')
               ->remove('batch')
               ->remove('create');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('number');
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
            ->add('order', null, [], EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'order',
            ])
            ->addIdentifier('number')
            ->addIdentifier('sum')
            ->add('status')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('liqpayInfo')
            ->add('createdAt');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $query = $this->modelManager
            ->getEntityManager('App\Entity\Order')
            ->createQuery(
                'SELECT o FROM App\Entity\Order o WHERE o.id not in (SELECT IDENTITY(t.order) FROM App\Entity\TransactionLiqPay t)'
            );

        $formMapper
            ->add('user', ModelType::class)
            ->add('order', ModelType::class, array('required' => true, 'query' => $query), array('edit' => 'standard'))
            ->add('number')
            ->add('sum')
            ->add('status')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('liqpayInfo');
    }
}
