<?php
namespace App\Twig;

use App\Services\MobileDetector;
use App\Services\RoutingService;
use App\Services\SettingService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class TemplateRuntime implements RuntimeExtensionInterface
{
    private $doctrine;
    private $language;
    private $request;
    private $router;
    private $params;
    private $settings;
    private $route;

    const SESSION_LOGO = 'logoEvent';
    const SESSION_CLIENT_CODE = 'clientCode';

    public function __construct(ManagerRegistry $doctrine, RequestStack $request, RoutingService $router, ParameterBagInterface $params, SettingService $settings)
    {
        $this->doctrine = $doctrine;
        $request && $this->request = $request->getCurrentRequest();
        $request && $this->route = $this->request->attributes->get('_route');
        $this->language = $this->request->getLocale();
        $this->params = $params;
        $this->router = $router;
        $this->settings = $settings;
    }

    public function isMobile()
    {
        $mobile = new MobileDetector();
        return $mobile->isMobile();
    }
    public function getLogo()
    {
        $urlLogo = $this->request->query->get('logo');
        $session = $this->request->getSession();
        empty($urlLogo) || $session->set(self::SESSION_LOGO, $urlLogo);
        $urlLogo == 'null' && $session->remove(self::SESSION_LOGO);
        $logo = $session->get(self::SESSION_LOGO);
        return $logo;
    }
    public function getMainCss()
    {
        return $_ENV['MAIN_THEME'] ?? 'main';
    }
    public function getUrl($name, $parameters = [], $isAbsolute = true)
    {
        return $this->router->getUrl($name, $parameters, $isAbsolute);
    }
    public function getEnvLanguages($languages)
    {
        return $this->settings->getAvailableLanguages($languages);
    }

    public function generateId()
    {
        return md5(uniqid(time()));
    }
}