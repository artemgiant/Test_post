<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function profileAction(): Response
    {
        $this->user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $this->user);

        return $this->render('cabinet/profile/profile.html.twig', [
            'user' => $this->user,
            'profileForm' => $form->createView(),
            'my_address' => $this->getMyAddress($this->user->getId()),
            'page_id'=>'post_profile'
        ]);
    }

    /**
     * @Route("/edit", name="post_profile_edit")
     * @param Request $request
     * @return RedirectResponse
     */
    public function profileEditAction(Request $request)
    {
        $this->user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $this->user);
        $form->handleRequest($request);
        $errors =[];

        if ($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('avatar')->getData();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move($this->getParameter('avatar_directory'), $fileName);
            //kostil
            $this->user->setAvatar('sklad-express/uploads/avatars/'.$fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->user);
            $entityManager->flush();

            return $this->redirectToRoute('post_profile');
        }
        elseif ($form->isSubmitted() && !$form->isValid())
        {
            $errors = $form->getErrors(true);

            return $this->redirectToRoute('post_profile',[
                'errors' => $errors,
            ]);
        }
    }

    /**
    * @return string
    */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}

