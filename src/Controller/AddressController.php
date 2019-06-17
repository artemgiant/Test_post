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

use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/post/addresses")
 */
class AddressController extends CabinetController
{

    /**
     * @Route("/", name="post_addresses")
     */
    public function addressesAction(Request $request, PaginatorInterface $paginator): Response
    {
        $this->getTemplateData();
        $this->optionToTemplate['page_id']='post_addresses';
        $this->optionToTemplate['page_title']='Address List';

        $entityManager = $this->getDoctrine()->getManager();

        $addressListQuery=$entityManager->getRepository(Address::class)
                       ->getAdressList($this->user);

        $addressList = $paginator->paginate(
            $addressListQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('cabinet/addresses/addresses.html.twig', array_merge($this->optionToTemplate,['items'=>$addressList]));
    }

    /**
     * @Route("/create", name="post_address_create")
     * @param Request $request
     * @return Response
     */
    public function addressCreateAction(Request $request): Response
    {
        $this->getTemplateData();
        $errors =[];
        $this->optionToTemplate['page_id']='post_address_create';
        $this->optionToTemplate['page_title']='Address Create';

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
        $twigoption=array_merge($this->optionToTemplate,[
            'addressForm' => $form->createView(),
            'error' => $errors,]);

        return $this->render('cabinet/addresses/editform.html.twig', $twigoption);

    }

    /**
     * @Route("/{id}/edit", name="post_address_edit")
     */
    public function addressEditAction(Request $request): Response
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
        $twigoption=array_merge($this->optionToTemplate,[
            'addressForm' => $form->createView(),
            'error' => $errors,
            ]);

        return $this->render('cabinet/addresses/editform.html.twig', $twigoption);

    }
}
