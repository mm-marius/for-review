<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class SettingService
{
    private $em;
    private $cachedSettings;
    /** @var User $user */
    private $user;
    /** @var Request $request */
    private $request;
    private $enabledModules;
    private $session;

    public function __construct(EntityManagerInterface $em, Security $security, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->cachedSettings = [];
        $this->user = $security->getUser();
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $this->request->getSession();
    }
}