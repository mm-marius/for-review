<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

//Martin Router King
class RoutingService
{
    /** @var Request $request */
    private $request;
    private $session;
    private $doctrine;
    private $settings;

    public function __construct(RequestStack $requestStack, RouterInterface $router, ManagerRegistry $doctrine, SettingService $settings)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $this->request->getSession();
        $this->router = $router;
        $this->settings = $settings;
        $this->doctrine = $doctrine;
    }

    public function getUrl(string $routeName, array $parameters = [], bool $isAbsolute = true): string
    {
        $referencePath = $isAbsolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::RELATIVE_PATH;
        return $this->router->generate($routeName, $parameters, $referencePath);
    }
}