<?php

namespace App\Controller;

use App\Entity\Jwt;
use App\Services\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JwtController extends AbstractController
{
    const SESSION_SEARCH = 'sessionId';
    /**
     * @Route("/checkcode/{jwt}", name="checkJwt", methods={"GET"})
     */
    public function checkJwt($jwt, JwtService $jwtManager)
    {
        $jwt = $jwtManager->JwtHandler($jwt);
        return $this->render('Administration/response.html.twig', ["response" => $jwt['mess'], "error" => $jwt['err']]);
    }
}