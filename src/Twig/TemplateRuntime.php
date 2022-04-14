<?php
namespace App\Twig;

use App\Services\MobileDetector;
use App\Services\RoutingService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

    public function __construct(ManagerRegistry $doctrine, RequestStack $request, RoutingService $router, ParameterBagInterface $params)
    {
        $this->doctrine = $doctrine;
        $request && $this->request = $request->getCurrentRequest();
        $request && $this->route = $this->request->attributes->get('_route');
        $this->language = $this->request->getLocale();
        $this->params = $params;
        $this->router = $router;
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
}