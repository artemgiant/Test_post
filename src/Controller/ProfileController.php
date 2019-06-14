<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            $errors=(string)$form->getErrors(true, false);
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
    * @return string
    */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}

