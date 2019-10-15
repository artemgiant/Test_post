<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\OrderType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class CouponAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('Code')
            ->add('quantity')
            ->add('ShippingType')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {

        $listMapper
            ->add('quantity')
//            ->add('ShippingType')
            ->add('Code')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
         parent::configureRoutes($collection);
        $collection->add('coupone_ajax','coupone_ajax');
    }
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('quantity',IntegerType::class)
//            ->add('ShippingType', ChoiceType::class, [
//                'label' =>'Shipping Type',
//                'choices' => $this->getShippingType()
//            ])
//            ->add('Discount')
        ;


    }
    protected function getShippingType(){

        $em =  $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
       $data = $em->getRepository(OrderType::class)->findAll();
       $ShipingType = array([]);

       foreach($data as $v){
           $ShipingType[$v->getName()]=$v->getCode();
       }
       return $ShipingType;

    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('quantity')
//            ->add('ShippingType')
//            ->add('Discount')
            ;
    }
}
