<?php

namespace App\Security;

use App\Form\LoginForm;
use App\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

class LoginPostAuthenticator extends AbstractFormLoginAuthenticator implements AuthenticatorInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    ){
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        if (strpos($request->getPathInfo(),'/post/login')===false ||
            $request->getMethod() != 'POST' ||
            $request->attributes->get('_route')!== 'connect_facebook_check') {
            return false;
        }
    dd('1');
        return true;
    }

    public function getCredentials(Request $request): array
    {
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['email']
        );

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            return false;
        }

        /** @var User $user */
        if ($user->isSuspended()) {
            throw new CustomUserMessageAuthenticationException('Suspended');
        }

        if (!$user->hasRole(User::POST_ROLE) && !$user->hasRole(User::ADMIN_ROLE)) {
            throw new CustomUserMessageAuthenticationException("You don't have permission to access that page.");
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->router->generate('user_login'));
    }

    protected function getLoginUrl(): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('user_login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        if (!empty($token)){
            $user=$token->getUser();
            return new RedirectResponse($this->router->generate('post_dashboard',['_locale'=>$user->getLocale()]));
        }
        return new RedirectResponse($this->router->generate('post_dashboard'));
    }
}
