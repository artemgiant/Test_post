<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormHelpsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'helps-text';
    protected $baseRouteName = 'helps-text';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('code')
            ->add('name')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->add('code')
            ->add('name')
            ->add('text')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->isCurrentRoute('create')) {
            $formMapper
                ->add('code')
                ->add('name',null,["label"=>"name"]);
        }else{
            $formMapper
                ->add('code',null,['disabled'=>true])
                ->add('name',null,["label"=>"name",'disabled'=>true]);
        }

        $formMapper ->add('text',null,["label"=>"text"])
            ->add('textRu',null,["label"=>"textRu"])
        ;
    }

}
