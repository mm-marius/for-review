<?php

namespace App\Services;

use App\Entity\Jwt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class JwtService
{
    /** @var User $user */
    private $requester;
    /** @var User $user */
    private $user;
    private $request;
    private $em;
    private $jwts = [];
    private $translator;
    public function __construct(EntityManagerInterface $em, Security $security, TranslatorInterface $translator, RequestStack $request)
    {
        $this->em = $em;
        $this->translator = $translator;
        $security && $this->requester = $security->getUser(); //TODO maybe i need to check if is already authenticated for some role actions or checks
        $request && $this->request = $request->getCurrentRequest();

    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function getUser($jwtData)
    {
        is_object($jwtData) || $jwtData = $this->getJwtData($jwtData);
        return $jwtData ? $jwtData->getUser() : null;

    }
    public function generateJwt($type, $user = null)
    {
        $jwtData = new Jwt($user ?? $this->user, $type);
        $this->em->persist($jwtData);
        $this->em->flush();
        return $jwtData;
    }
    public function getJwtData($jwt)
    {
        isset($this->jwts[$jwt]) || $this->jwts[$jwt] = $this->em->getRepository(Jwt::class)->findOneBy(['id' => $jwt]);
        return $this->jwts[$jwt];
    }
    public function useJwt($jwtData)
    {
        is_object($jwtData) || $jwtData = $this->getJwtData($jwtData);
        if (!$jwtData) {return false;}
        $jwtData->setUsed(true);
        $this->em->persist($jwtData);
        $this->em->flush();
        return true;
    }

    public function JwtHandler($jwt)
    { //TODO user request for change routing returns!
        //redirect to other pages based on jwt type
        $result = null;
        $jwtData = $this->getJwtData($jwt);
        if ($this->checkJwt($jwtData)) {
            switch ($jwtData->getType()) {
                case JWT::TYPE_ACTIVATION:
                    //TODO login? and activation property
                    $user = $jwtData->getUser();
                    $user->setActive(true);
                    $this->em->persist($user);
                    $this->em->flush();
                    $this->useJwt($jwt);
                    return $this->translator->trans('user.activated', ['{{ user }}' => $user->getEmail()], 'security');
                case JWT::TYPE_FORGOT:
                    return $jwt;
                case JWT::TYPE_LOGIN:
                    //TODO integrate login with jwt generated from administrator FASE 4 - impersonator
                    //main page after login
                    break;
                default:
            }
        } else {
            $result = $this->translator->trans('jwt.expiredOrNotValidLink', [], 'validators');
        }
        return $result;
    }
    public function checkJwt($jwtData, $type = null)
    {
        is_object($jwtData) || $jwtData = $this->getJwtData($jwtData);
        if (!$jwtData) {return false;}
        if ($type && $jwtData->getType() != $type) {return false;}
        if (new \DateTime() > $jwtData->getExpirationDateTime() || $jwtData->getUsed()) {return false;}
        return true;
    }
}