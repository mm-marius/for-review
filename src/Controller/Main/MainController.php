<?php
namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main", methods={"GET","POST"})
     * @Route("/{_locale<%supported_locales%>}/", name="mainLang", methods={"GET","POST"})
     */
    public function index(): Response
    {
        return $this->render('Main/main.html.twig');
    }

    /**
     * @Route("/test", name="test", methods={"GET","POST"})
     * @Route("/{_locale<%supported_locales%>}/", name="testLang", methods={"GET","POST"})
     */
    public function test(): Response
    {
        return $this->render('Main/main.html.twig');
    }
}