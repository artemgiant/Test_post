<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\Invoices;
use App\Form\InvoiceFormType;

use Sonata\AdminBundle\Controller\CRUDController;

class InvoicesAdminController extends CRUDController
{
    public function addInvoiceAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $order_id=$request->get('order',false);

        if ($order_id){
            $order= $entityManager->getRepository(Order::class)->find((int)$order_id);
            $invoiceOpen=$entityManager->getRepository(Invoices::class)->findOneBy(
                [
                    'orderId'=>$order,
                    'isPaid'=>false
                ]
            );
            $invoice=$invoiceOpen??new Invoices();
            if ($order) $invoice->order=$order->getId();
        }
        $form = $this->createForm(InvoiceFormType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ((int)$invoice->order>0){
                $order= $entityManager->getRepository(Order::class)->find((int)$invoice->order);
                /* @var Order $order*/
                if ($order) {
                    $shipCost=0;
                    $invoice->setOrderId($order);
                    $orderStatus=$entityManager->getRepository(OrderStatus::class)->findOneBy(['status'=>'getnewinvoice']);
                    $order->setOrderStatus($orderStatus);
                    if ($order->getInvoices()){
                        foreach($order->getInvoices() as $invoiceItem){
                            /** @var Invoices $invoiceItem */
                            $shipCost=$shipCost + $invoiceItem->getPrice();
                        }
                    }
                    $order->setShippingCosts($shipCost);

                }
            }

            $entityManager->persist($invoice);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('orders_edit',["id"=>$order_id]);
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }

        return $this->render('system/addInvoice.html.twig', [
           'form'=>$form->createView()
            ]);
    }
}

