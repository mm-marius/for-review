<?php
namespace App\Controller\API;

use App\Services\ClientAnaf;
use App\Services\SettingService;
use Itrack\Anaf\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Anaf extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/api/anafValidation", name="apiAnafValidation", methods={"POST", "GET"})
     */
    public function anafValidation(Request $request, TranslatorInterface $translator, SettingService $settings): JsonResponse
    {
        $errorMsg = "";
        $confirmVatCode = true;
        $vatCode = $request->request->get("vatCode");
        // if (!$vatCode) {
        //     $confirmVatCode = false;
        //     $errorMsg = $translator->trans('vatCode.emptyField', [], 'security');
        // }
        // if (strlen($vatCode) != 10) {
        //     $confirmVatCode = false;
        //     $errorMsg = $translator->trans('vatCode.fieldLength', [], 'security');
        // }
        if ($confirmVatCode) {
            $date = new \DateTime();
            $date = $date->format("Y-m-d");

            $anaf = new ClientAnaf();
            $anaf->addCif($vatCode, $date);

            $company = $anaf->first();

            $t = $company->getSplitAddress()->getCounty();

            $response = [
                'businessName' => $company->getName(),
                'phone' => $company->getPhone(),
                'address' => '',
                'city' => '',
                'state' => '',
                'zipCode' => '',
            ];

        }
        return new JsonResponse(['content' => $response, 'success' => true, "errorMsg" => $errorMsg], Response::HTTP_OK);
    }
}