<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolicyPageController extends AbstractController
{
    /**
     * @Route("/policy", name="policyPage")
     */
    public function index(): Response
    {
        return $this->render('Policy_page/policyPage.html.twig', [
            'controller_name' => 'PolicyPageController',
        ]);
    }
}