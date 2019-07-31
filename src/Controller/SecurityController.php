<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\LoginForm;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use Swift_Message;

class SecurityController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminLoginAction(): Response
    {
        $form = $this->createForm(LoginForm::class, [
            'email' => $this->authenticationUtils->getLastUsername()
        ]);

        return $this->render('security/login.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'form' => $form->createView(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function adminLogoutAction(): void
    {
    }

    /**
     * @Route("/post/login", name="user_login")
     */
    public function loginAction(): Response
    {

        if (empty($this->getUser())
            ||
            !($this->getUser() instanceof User)) {
            $form = $this->createForm(LoginForm::class, [
                'email' => $this->authenticationUtils->getLastUsername()
            ]);

            return $this->render('security/user_login.html.twig', [
                'last_username' => $this->authenticationUtils->getLastUsername(),
                'form' => $form->createView(),
                'error' => $this->authenticationUtils->getLastAuthenticationError(),
            ]);
        }else{
            return $this->redirectToRoute('post_dashboard');
        }
    }

    /**
     * @Route("/post/logout", name="user_logout")
     */
    public function logoutAction(): void
    {
    }

    /**
     * @Route("/post/fogot-pasword", name="user_logout_fogot_pasword")
     */

    public function fogotPasswordAction(Request $request, \Swift_Mailer $mailer): Response
    {
        if ($request->isMethod('post')) {
            $email = $request->request->get('_username');
            $entityManager = $this->getDoctrine()->getManager();
            /* @var $em EntityMenedger */
            $user = $entityManager->getRepository('App:User')->findOneBy(['email' => $email]);
            /* @var $user User */

            if (isset($user)) {
                $newPassword = $this->generatePassword();
                $user->setPlainPassword($newPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                // $this->get('email_service')->sendRecoverPassword($user, $newPassword);
                $template = $this->render('Email/password.html.twig');
                $template = str_replace("{{name}}", $user->getUsername(), $template);
                $template = str_replace("{{password}}", $newPassword, $template);
                $message = (new \Swift_Message('Password recovery in  site expressposhta.com'));
                $message      ->setSubject('Password recovery in  site expressposhta.com')
                    ->setFrom('admin@expressposhta.com')
                    ->setTo($user->getEmail())
                    ->setBody(html_entity_decode($template),'text/html');
                $mailer->send($message);

                $success = true;
                $message = "New password was sent to you on your email";

            } else {
                $success = false;
                $message = "Email not found";
            }
        } else {
            $success = false;
            $message = "";
        }

        return $this->render('security/forgot.html.twig', ['success' => $success, 'message' => $message]);
    }

    protected function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
