<?php

namespace App\Admin;

use App\Entity\OrderStatus;
use App\Entity\OrderType;
use App\Entity\User;
//use Doctrine\DBAL\Types\TextType;
use App\Service\SkladUsaService;
use Proxies\__CG__\App\Entity\Order;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sonata\AdminBundle\Form\Type\AdminType;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\InvoiceFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Contracts\Translation\TranslatorInterface;

class OrdersAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'orders';
    protected $baseRouteName = 'orders';
    protected $router;
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('orderStatus');
    }

    public function __construct(string $code, string $class, string $baseControllerName,UrlGeneratorInterface $router)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->router=$router;

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
            ->addIdentifier('comment')
            ->addIdentifier('orderStatus')
            ->add('orderType', EntityType::class, [
                'class' => OrderType::class,
//                'placeholder' => 'Select type',
                'choice_label' => 'code',
                'choice_translation_domain' => 'messages',
                'label' => $this->trans( "OrderType"),
//                'required'=>true,
//                'attr'=>[
//                    'class'=>'form-control',
//                    'id'=>'order_type',
//                    'autocomplete'=>'off',
//                ],
            ])

            ->add('trNum',null,['label'=>'Трек для пользователя'])
            ->add('trackingNumber',null,['label'=>'Трекномер Новой Почты'])
            ->add('systemNum',null,['label'=>'Трек системный(Посылка едет в страну назначения)'])
            ->add('systemNumInUsa',null,['label'=>'Трек системный(Посылка едет к аддресу назначения)'])
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
        $invoicesStr='';
        /* @var Order $object*/
        $object=$this->getSubject();

        if (!empty($object->getInvoices())){
            $invoicesStr='<table class="table"><thead>'.
            '<th>'.$this->trans( "Price").'</th>'.
            '<th>'.$this->trans( "Comment").'</th>'.
            '<th>'.$this->trans( "Status").'</th>'.
                '</thead><tbody>';
            foreach ($object->getInvoices() as $invoice){
                /* @var \App\Entity\Invoices $invoice */
                $invoiceStatus=($invoice->isPaid())?
                    '<span class="label label-success">'.$this->trans( "paid").'</span>'
                    :
                    '<span class="label label-danger">'.$this->trans( "nopaid").'</span>';
                $invoicesStr .='<tr>'.
                        '<td>'.$invoice->getPrice().'</td>'.
                        '<td>'.$invoice->getComment().'</td>'.
                        '<td>'.$invoiceStatus.'</td>'.
                        '</tr>';
            }
            $invoicesStr .='</tbody></table>'.
            '<a class="btn btn-info" href="'.$this->router->generate("invoices_add-invoice",["order"=>$object->getId()],UrlGeneratorInterface::ABSOLUTE_URL).'">'.$this->trans("Add Invoice").'</a>';
        }

        $userFieldOptions = [];
        $orderStatusFieldOptions = [];
        $addressesFieldOptions = [];
        $carrierCodes = [
            'Select company'                                => null,
            'DHL'                                           => 'dhl',
            'FedEx'                                         => 'fedex',
            'USPS'                                          => 'usps',
            'Parcel Priority with Delcon (14 - 21) days'    => 'apc',
            'UPS'                                           => 'ups',

//            'Нова Пошта'                                    => 'nova-poshta',
        ];
        $formMapper
            ->add('user', ModelType::class, $userFieldOptions)
            ->add('orderType', EntityType::class, [
                'class' => OrderType::class,
                'placeholder' => 'Select type',
                'choice_label' => 'code',
                'choice_translation_domain' => 'messages',
                'label' => $this->trans( "OrderType"),
                'required'=>true,
                'attr'=>[
                    'class'=>'form-control',
                    'id'=>'order_type',
                    'autocomplete'=>'off',
                ],
            ])
            ->add('orderStatus', ModelType::class, $orderStatusFieldOptions)
            ->add('addresses', ModelType::class, $addressesFieldOptions)
            ->add('trackingNumber',null,['label'=>'Трек новой почты'])
            ->add('trNum',null,['label'=>'Трек для пользователя','disabled'=>true,'required'=>false])
            ->add('companySendToUsa', ChoiceType::class, [
                        'choices'  => $carrierCodes,
                        'label'=>'Компания доставки(Посылка едет в страну назначения)'
                 ]
            )
            ->add('systemNum',null,['label'=>'Трек системный(Тот что меняет админ)','required'=>false])
            ->add('companySendInUsa', ChoiceType::class, [
                    'choices'  => $carrierCodes,
                    'label'=>'Компания доставки(Посылка едет к аддресу назначения)'
                ]
            )
            ->add('systemNumInUsa',null,['label'=>'Трек системный(Посылка едет к аддресу назначения)','required'=>false])
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
            ->add('quantity')
            ->add('invoicesStr', TextType::class,[
                'label'=>'Invoices',
                'required'=>false,
                'attr'=>['class'=>'hide'],
                //'label_attr'=>['class'=>'hideddd'],
                ],['help'=>$invoicesStr]);
    }

    /**
     * @param $order
     */
    public function postUpdate($order) {
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $original = $em->getUnitOfWork()->getOriginalEntityData($order);
        if($order->getOrderStatus()->getId() == 2 && $original['status'] != 2){
            $service = new SkladUsaService();
            $service->sendOrderToSklad($order);
        }
    }
}
