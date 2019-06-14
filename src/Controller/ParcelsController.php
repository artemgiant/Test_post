<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use App\Controller\CabinetController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;

/**
 * @Route("/post/parcels")
 */
class ParcelsController extends CabinetController
{

    /**
     * @Route("/", name="post_parcels")
     */
    public function parcelsAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getOrders($this->user->getId());

        return $this->render('cabinet/parcels/parcels.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            'orders' => $orders,
            'page_id'=>'post_parcels'
        ]);
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

        $address = new Address();
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $entityManager = $this->getDoctrine()->getManager();
            $address->setUser($this->user);
            $entityManager->persist($address);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_addresses');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }
        $twigoption=array_merge($this->optionToTemplate,['form' => $form->createView(),
            'error' => $errors,]);

        return $this->render('cabinet/addresses/editform.html.twig', $twigoption);

    }

    /**
     * @Route("/{id}/edit", name="post_parcels_edit")
     */
    public function adressEditAction(Request $request): Response
    {
        $this->getTemplateData();
        $entityManager = $this->getDoctrine()->getManager();
        $errors =[];
        $this->optionToTemplate['page_id']='post_addresses';
        $this->optionToTemplate['page_title']='Address Edit';
        $id = $request->get('id',false);
        if ($id && (int)$id>0){
            $address =$entityManager->getRepository(Address::class)->find((int)$id);
            if(empty($address) || $address->getUser()!=$this->getUser()){
                throw new ServiceException('Not found');
            }

        }
        //$address = new Address();
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password


            $address->setUser($this->user);
            $entityManager->persist($address);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_addresses');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }
        $twigoption=array_merge($this->optionToTemplate,['form' => $form->createView(),
            'error' => $errors,]);

        return $this->render('cabinet/addresses/editform.html.twig', $twigoption);

    }
}

