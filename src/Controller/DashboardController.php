<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function main(): Response
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/dashboard", name="dashboard", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $avatarUrl = $user->getAvatarUrl();
        $userEmail = $user->getEmail();
        return $this->render('Dashboard/dashboard.html.twig', [
            'avatarUrl' => $avatarUrl,
            'userEmail' => $userEmail,
        ]);
    }
}