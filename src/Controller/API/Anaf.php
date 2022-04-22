<?php
namespace App\Controller\API;

use App\Repository\GeneralRepository;
use App\Services\ClientAnaf;
use App\Services\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class Anaf extends AbstractController
{
    /**
     * @Route("/api/anafValidation", name="apiAnafValidation", methods={"POST", "GET"})
     */
    public function anafValidation(Request $request, TranslatorInterface $translator, SettingService $settings, GeneralRepository $general): JsonResponse
    {
        $errorMsg = "";
        $response = [];
        $confirmVatCode = true;
        $vatCode = $request->request->get("vatCode");
        if (!$vatCode) {
            $confirmVatCode = false;
            $errorMsg = $translator->trans('vatCode.emptyField', [], 'security');
        }
        if (strlen($vatCode) < 4 || strlen($vatCode) > 11) {
            $confirmVatCode = false;
            $errorMsg = $translator->trans('vatCode.fieldLength', [], 'security');
        }

        $cifra_control = substr($vatCode, -1);
        $cui = substr($vatCode, 0, -1);

        while (strlen($cui) != 9) {
            $cui = '0' . $cui;
        }

        $_suma = $cui[0] * 7 + $cui[1] * 5 + $cui[2] * 3 + $cui[3] * 2 + $cui[4] * 1 + $cui[5] * 7 + $cui[6] * 5 + $cui[7] * 3 + $cui[8] * 2;
        $suma = $_suma * 10;
        $rest = fmod($suma, 11);

        if ($rest == 10) {
            $rest = 0;
        }

        if ($rest != $cifra_control) {
            $confirmVatCode = false;
            $errorMsg = $translator->trans('vatCode.invalid', [], 'security');
        }

        if ($confirmVatCode) {
            $date = new \DateTime();
            $date = $date->format("Y-m-d");

            $anaf = new ClientAnaf();
            $anaf->addCif($vatCode, $date);
            $company = $anaf->first();
            $t = $company->getCounty();
            $s = $general->findByCountyName($company->getCounty());
            $countyCode = $general->findByCountyName($company->getCounty())[0]->getCode();

            $response = [
                'businessName' => $company->getName(),
                'phone' => $company->getPhone(),
                'addressFull' => $company->getFullAddress(),
                'county' => $countyCode,
                'city' => $company->getCity(),
                'street' => $company->getStreet(),
                'streetNumber' => $company->getStreetNumber(),
                'bloc' => $company->getBloc(),
                'scara' => $company->getScara(),
                'etaj' => $company->getEtaj(),
                'apart' => $company->getApart(),
                'cam' => $company->getCam(),
                'sect' => $company->getSector(),
                'comuna' => $company->getComuna(),
                'sat' => $company->getSat(),
                'other' => $company->getOther(),
                'zipCode' => $company->getZipCode(),
            ];

        }
        return new JsonResponse(['content' => $response, 'success' => true, "errorMsg" => $errorMsg], Response::HTTP_OK);
    }
}