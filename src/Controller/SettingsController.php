<?php
namespace App\Controller;

use App\Services\BreadcrumbController;
use App\Services\RoutingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function settings(Request $request, Breadcrumbs $breadcrumbs, RoutingService $router, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        $avatarUrl = $user->getAvatarUrl();
        $userEmail = $user->getEmail();

        $path = $request->getRequestUri();
        $breadCrumbs = new BreadcrumbController($router, $breadcrumbs, $translator);
        $breadCrumbs->setBreadcrumbs($path);

        return $this->render('Settings/settings.html.twig', [
            'avatarUrl' => $avatarUrl,
            'userEmail' => $userEmail,
        ]);
    }
}