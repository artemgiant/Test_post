<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class UserAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('email');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('email')
            ->add('lastLogin')
            ->add('isSuspended')
            ->add('isVip')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('firstName')
            ->add('secondName')
            ->add('lastName')
            ->add('email')
            ->add('phone')
            ->add('plainPassword', TextType::class, [
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
            ])
            ->add('isVip',CheckboxType::class , [
            'required' => false,
             'label' => 'VIP',
                'attr'=>[
             'class'=>'isVip',
    ]
             ])
//            ->add('markup',null, [
//                'required' => false,
//                'label' => 'Markup'
//            ])
            ->add('isSuspended', null, [
                'required' => false,
                'label' => 'Suspended',

            ])
        ;
    }
}
