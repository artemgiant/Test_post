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

class ExpressDeliveryPriceAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'settings';
    protected $baseRouteName = 'settings';
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_per_page' => '2',
    );
    protected $perPageOptions = ['2'];
    protected $maxPerPage = '2';

    /**
     * @param \Sonata\AdminBundle\Route\RouteCollection $collection
     */
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection
            ->remove('batch')
            ->remove('delete')
            ->remove('create');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
//        $datagridMapper->add('name');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('id')
            ->add('name')
            ->add('value')
            ->add('code')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
            ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {

        if ($this->isCurrentRoute('create')) {
            $formMapper
                ->add('name')
                ->add('code')
            ;
        }else{
            $formMapper
                ->add('name',null,["disabled"=>true])
                ->add('code',null,["disabled"=>true])
            ;
        }
        $formMapper
            ->add('value')
        ;
    }
}
