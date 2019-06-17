<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\OrderProducts;
use App\Controller\CabinetController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\OrderFormType;

use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/post/parcels")
 */
class ParcelsController extends CabinetController
{

    /**
     * new orders list
     * @Route("/", name="post_parcels")
     */
    public function parcelsAction(Request $request, PaginatorInterface $paginator): Response
    {
        $this->getTemplateData();
        $this->optionToTemplate['page_id']='post_parcels';
        $this->optionToTemplate['page_title']='Address List';

        $entityManager = $this->getDoctrine()->getManager();

        $orders = $entityManager
            ->getRepository(Order::class)
            ->getNewOrders($this->user->getId());

        $ordersList = $paginator->paginate(
            $orders,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('cabinet/parcels/parcels.html.twig'
            , array_merge($this->optionToTemplate,['items'=>$ordersList])
        );
    }

    /**
     * @Route("/create", name="post_parcels_create")
     */
    public function parcelsCreateAction(Request $request): Response
    {
        $this->getTemplateData();
        $errors =[];
        $this->optionToTemplate['page_id']='post_parcels_create';
        $this->optionToTemplate['page_title']='Parcels Create';

        $order = new Order();

        $orderForm=$request->request->get('order_form',false);
        if ($orderForm)
        {
            if ($products=$orderForm['products']??false){
                foreach($products as $product){
                    $orderProduct=new OrderProducts();
                    $order->addProduct($orderProduct);
                }
            }
        }
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($order->getProducts()){
                foreach ($order->getProducts() as &$product){
                    $product->setOrderId($order);
                    $entityManager->persist($product);
                }
            }
            $order->setUser($this->user);
            $entityManager->persist($order);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_parcels');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }
        $twigoption=array_merge($this->optionToTemplate,['form' => $form->createView(),
            'error' => $errors,]);

        return $this->render('cabinet/parcels/editform.html.twig', $twigoption);

    }

    /**
     * @Route("/{id}/edit", name="post_parcels_edit")
     */
    public function parcelsEditAction(Request $request): Response
    {
        $this->getTemplateData();
        $entityManager = $this->getDoctrine()->getManager();
        $errors =[];
        $this->optionToTemplate['page_id']='post_parcels';
        $this->optionToTemplate['page_title']='Order Edit';
        $id = $request->get('id',false);
        if ($id && (int)$id>0){
            $order =$entityManager->getRepository(Order::class)->find((int)$id);
            if(empty($order) || $order->getUser()!=$this->getUser()){
                throw new ServiceException('Not found');
            }

        }
        /* @var $order Order */
        $orderForm=$request->request->get('order_form',false);
        if ($orderForm)
        {
            if ($products=$orderForm['products']??false){
                foreach($products as &$product){
                    $orderProduct=new OrderProducts();
                    $order->addProduct($orderProduct);
                }
            }
        }
        //$address = new Address();
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($order->getProducts()){
                foreach ($order->getProducts() as &$product){
                    if (empty($product->getdescEn())){
                        $entityManager->remove($product);
                        continue;
                    }
                    $product->setOrderId($order);
                    $entityManager->persist($product);
                }
            }
            unset($product);
            $entityManager->persist($order);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_parcels');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }
        $twigoption=array_merge($this->optionToTemplate,['form' => $form->createView(),
            'error' => $errors,]);

        return $this->render('cabinet/parcels/editform.html.twig', $twigoption);

    }
}

