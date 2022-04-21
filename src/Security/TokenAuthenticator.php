<?php
namespace App\Security;

use App\Entity\FormField\Settings;
use App\Entity\User;
use App\Services\AES;
use App\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $route;
    private $settings;

    public function __construct(EntityManagerInterface $em, RequestStack $request, SettingService $settings)
    {
        $request && $request = $request->getCurrentRequest();
        $request && $this->route = $request->attributes->get('_route');
        $this->settings = $settings;
        $this->em = $em;
    }

    public function supports(Request $request)
    {
        //TODO imbunatateste call-ul la api-uri (vezi blur la cif in registration)
        // return 'apiAnafValidation' !== $this->route && 'hotelExtra' !== $this->route && !$request->headers->has('X-AUTHORIZATION');
        return 'apiAnafValidation' !== $this->route && !$request->headers->has('X-AUTHORIZATION');
    }

    public function getCredentials(Request $request)
    {
        if (!$request->headers->has('X-AUTH-TOKEN')) {
            // error_log("Token error");
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Key'
            );
        }
        return $request->headers->get('X-AUTH-TOKEN');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Key'
            );
        }
        $aes_token = new AES(base64_decode($credentials), $this->settings->getSetting(Settings::NAME_API_SALT), 128, 'CBC');
        $result = $aes_token->decrypt();
        return $this->em->getRepository(User::class)
            ->findOneBy(['apiToken' => $result])
        ;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'errorMessage' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'errorMessage' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}