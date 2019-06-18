<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressFormType;
/**
 * @Route("/post/profile")
 */
class ProfileController extends CabinetController
{
    public $user;

    public function __construct()
    {}
    /**
     * @Route("/", name="post_profile")
     */
    public function profileAction(Request $request): Response
    {
        $this->user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $this->user);
        $form->handleRequest($request);

        $errors =[];
        $mess='';
        if ($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('avatarFile')->getData();
            if($file)
            {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move($this->getParameter('avatar_directory'), $fileName);
                //kostil
                $this->user->setAvatar('sklad-express/uploads/avatars/'.$fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->user);
            $entityManager->flush();
            $mess='Save success';
        }
        elseif($form->isSubmitted() && !$form->isValid())
        {
            $errors=(string)$form->getErrors(true);
        }

        return $this->render('cabinet/profile/profile.html.twig', [
            'user' => $this->user,
            'profileForm' => $form->createView(),
            'errors'=>$errors,
            'mess'=>$mess,
            'my_address' => $this->getMyAddress($this->user->getId()),
            'page_id'=>'post_profile'
        ]);
    }

    /**
     * @Route("/from-address", name="post_profile_from_address")
     */
    public function fromAddressAction(Request $request): Response
    {
        $this->getTemplateData();
        $entityManager = $this->getDoctrine()->getManager();
        $errors =[];
        $this->optionToTemplate['page_id']='post_profile_from_address';
        $this->optionToTemplate['page_title']='post_profile_from_address';
       if (!empty($this->optionToTemplate['my_address']))
       {
           $address=$this->optionToTemplate['my_address'];
       }else{
           $address= new Address();

       }

        //$address = new Address();
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password


            $address->setUser($this->user);
            $address->setIsMyAddress(true);
            $entityManager->persist($address);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('post_profile_from_address');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $form->getErrors(true);
        }
        $twigoption=array_merge($this->optionToTemplate,[
            'addressForm' => $form->createView(),
            'error' => $errors,
        ]);

        return $this->render('cabinet/addresses/editform.html.twig', $twigoption);
    }


    /**
    * @return string
    */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}

