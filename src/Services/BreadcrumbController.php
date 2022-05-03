<?php
namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadcrumbController extends AbstractController
{
    private $router;
    private $breadcrumbs;
    private $translator;
    public function __construct(RoutingService $router, Breadcrumbs $breadcrumbs, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->breadcrumbs = $breadcrumbs;
        $this->translator = $translator;
    }

    public function setBreadcrumbs($path)
    {
        $home_trans = $this->translator->trans("paths.home", [], 'general');

        self::getBreadCrumb()->addItem($home_trans, self::getRouter()->getUrl("app_main"));
        $uriArr = explode("/", $path);
        foreach ($uriArr as $route) {
            if ($route) {
                $name = $this->translator->trans(("paths." . $route), [], 'general');
                if (self::getRouter()->getUrl($route)) {
                    self::getBreadCrumb()->addItem($name, self::getRouter()->getUrl($route));
                } else {
                    self::getBreadCrumb()->addItem($name);
                }
            }
        }
        // Example with parameter injected into translation "user.profile"
        // $breadcrumbs->addItem($txt, $url, ["%user%" => $user->getName()]);
    }

    private function getBreadCrumb()
    {
        return $this->breadcrumbs;
    }
    private function getRouter()
    {
        return $this->router;
    }
}