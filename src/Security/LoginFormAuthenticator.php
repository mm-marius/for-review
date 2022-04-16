<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $route;
    private $returnTo;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, RequestStack $request)
    {
        $request && $request = $request->getCurrentRequest();
        $request && $this->returnTo = $request->query->get("returnTo");
        $request && $this->route = $request->attributes->get('_route');
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): ?bool
    {
        $validRoutes = ['app_login', 'app_login_lang'];
        $validRegister = ['app_registration', 'app_registration_lang'];
        return (in_array($this->route, $validRoutes) ||
            (in_array($this->route, $validRegister) && !$request->request->get('registration_form')))
        && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $form = $request->get('login_form');
        $credentials = [
            'email' => $form['email'],
            'password' => $form['password'],
            'csrf_token' => $form['csfr_login_token'],
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('login_authenticate_token_38d7f', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('email.notFound');
        }
        return $user;
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
        // $request->getSession()->set(TemplateRuntime::SESSION_B2B, $user->getIsAgency());
        if ($this->returnTo) {
            return new RedirectResponse($this->urlGenerator->generate($this->returnTo));
        }
        return new RedirectResponse($this->urlGenerator->generate('dashboard'));
    }
    protected function getLoginUrl()
    {
        $route = $this->route;
        $params = $this->returnTo ? ['returnTo' => $this->returnTo] : [];
        $route = 'app_login';
        return $this->urlGenerator->generate($route, $params);
    }
    public function supportsRememberMe()
    {
        return true;
    }
}