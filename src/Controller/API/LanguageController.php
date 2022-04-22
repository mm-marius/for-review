<?php
namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LanguageController extends AbstractController
{
    /**
     * @Route("/api/changeLanguage", name="apiChangeLanguage", methods={"POST"})
     */
    public function changeLanguage(Request $request, TranslatorInterface $translator): JsonResponse
    {
        $code = $request->request->get('code');
        $code && $request->getSession()->set('_locale', $code);
        return new JsonResponse(['language' => $code], Response::HTTP_OK);
    }
}