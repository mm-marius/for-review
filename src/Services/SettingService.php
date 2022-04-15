<?php
namespace App\Services;

use App\Twig\TemplateRuntime;
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
    public function getSetting($settingName)
    {
        if (isset($this->cachedSettings[$settingName])) {
            return $this->cachedSettings[$settingName];
        }
        $setting = $this->em->getRepository(Settings::class)->findOneBy(['name' => $settingName]);
        $ret = $setting ? $setting->getValue() : null;
        $this->cachedSettings[$settingName] = $ret;
        return $ret;
    }
    public function getSessionCode()
    {
        $sessionClientCode = $this->session->get(TemplateRuntime::SESSION_CLIENT_CODE, null);
        $engineClientCode = $this->user ? $this->user->getClientCode($this) : $this->getSetting(Settings::NAME_VIP_DEFAULT_CLIENT);
        return empty($sessionClientCode) ? $engineClientCode : $sessionClientCode;
    }
}
