<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class TestAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('trackingNumber')
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
            ->add('shipDate')
            ->add('createdAt')
            ->add('countryCode')
            ->add('country')
            ->add('fromCountry')
            ->add('city')
            ->add('zip')
            ->add('towarehouse')
            ->add('quantity')
            ->add('trNum')
            ->add('companySendToUsa')
            ->add('systemNum')
            ->add('companySendInUsa')
            ->add('systemNumInUsa')
            ->add('accountCountry')
            ->add('adminCreate')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('trackingNumber')
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
            ->add('shipDate')
            ->add('createdAt')
            ->add('countryCode')
            ->add('country')
            ->add('fromCountry')
            ->add('city')
            ->add('zip')
            ->add('towarehouse')
            ->add('quantity')
            ->add('trNum')
            ->add('companySendToUsa')
            ->add('systemNum')
            ->add('companySendInUsa')
            ->add('systemNumInUsa')
            ->add('accountCountry')
            ->add('adminCreate')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('id')
            ->add('trackingNumber')
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
            ->add('shipDate')
            ->add('createdAt')
            ->add('countryCode')
            ->add('country')
            ->add('fromCountry')
            ->add('city')
            ->add('zip')
            ->add('towarehouse')
            ->add('quantity')
            ->add('trNum')
            ->add('companySendToUsa')
            ->add('systemNum')
            ->add('companySendInUsa')
            ->add('systemNumInUsa')
            ->add('accountCountry')
            ->add('adminCreate')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('trackingNumber')
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
            ->add('shipDate')
            ->add('createdAt')
            ->add('countryCode')
            ->add('country')
            ->add('fromCountry')
            ->add('city')
            ->add('zip')
            ->add('towarehouse')
            ->add('quantity')
            ->add('trNum')
            ->add('companySendToUsa')
            ->add('systemNum')
            ->add('companySendInUsa')
            ->add('systemNumInUsa')
            ->add('accountCountry')
            ->add('adminCreate')
            ;
    }
}
