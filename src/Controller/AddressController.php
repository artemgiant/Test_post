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
 * @Route("/post/addresses")
 */
class AddressController extends CabinetController
{
    private $user;

    /**
     * @Route("/", name="post_addresses")
     */
    public function addressesAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());

        return $this->render('cabinet/addresses/addresses.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            'page_id'=>'post_addresses'
        ]);
    }

    /**
     * @Route("/create", name="post_address_create")
     */
    public function adressEditAction(Request $request): Response
    {
        $this->user = $this->getUser();
        $address = new Address();
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

           // $entityManager = $this->getDoctrine()->getManager();

            //$entityManager->persist($address);
           // $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_addresses');
        }

        return $this->render('cabinet/addresses/editform.html.twig', [
            'form' => $form->createView(),
            'user' => $this->user,
            'my_address' => $this->getMyAddress($this->user->getId()),
            'page_id'=>'post_addresses'
        ]);

    }
}

