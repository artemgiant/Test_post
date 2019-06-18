<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddressesAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'addresses';
    protected $baseRouteName = 'addresses';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('aliasOfAddress');
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
            ->addIdentifier('zip')
            ->addIdentifier('country')
            ->add('city')
            ->add('regionOblast')
            ->add('regionRayon')
            ->add('street')
            ->add('house')
            ->add('apartment')
            ->add('userFirstName')
            ->add('userSecondName')
            ->add('userLastName')
            ->add('aliasOfAddress')
            ->add('phone')
            ->add('isMyAddress', null, [
                'required' => false,
                'label' => 'My address'
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $userFieldOptions = [];

        $formMapper
            ->add('user', ModelType::class, $userFieldOptions)
            ->add('zip')
            ->add('country')
            ->add('city')
            ->add('regionOblast')
            ->add('regionRayon')
            ->add('street')
            ->add('house')
            ->add('apartment')
            ->add('userFirstName')
            ->add('userSecondName')
            ->add('userLastName')
            ->add('aliasOfAddress')
            ->add('phone')
            ->add('isMyAddress', null, [
                'required' => false,
                'label' => 'My address'
            ]);
    }
}
