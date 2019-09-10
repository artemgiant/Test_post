<?php

namespace App\Admin;

use App\Entity\OrderStatus;
use App\Entity\User;
use Proxies\__CG__\App\Entity\Order;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InvoicesAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'invoices';
    protected $baseRouteName = 'invoices';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {

        // $datagridMapper->add('orderStatus');
    }



    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        // parent::configureRoutes($collection);
        $collection->add('add-invoice', 'add-invoice');
        $collection->remove('create');
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('id')
            ->add('orderId', null, ['admin_code' => 'app.admin.orders'], EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'name',
            ])
            ->addIdentifier('price')
            ->addIdentifier('comment')
            ->addIdentifier('isPaid')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $userFieldOptions = [];

        $formMapper
            ->add('orderId', ModelType::class, $userFieldOptions,['admin_code'    => 'app.admin.orders'])
            ->add('price',null,['label'=>'price'],['admin_code'    => 'app.admin.orders'])
            ->add('comment',null,['label'=>'comment'],['admin_code'    => 'app.admin.orders'])
            ->add('isPaid');
    }
}
