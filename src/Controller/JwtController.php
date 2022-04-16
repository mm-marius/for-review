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
        //TODO validation codice jwt, che sia di attivazione o di altro, capire se spostare la schermata
        //come in reset-password
        //altrimenti pagina di jwt scaduto
        return $this->render('Administration/response.html.twig', ["response" => $jwt]);
    }
}