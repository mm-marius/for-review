<?php

namespace App\Handlers\VIP;

use App\Entity\FormField\Settings;
use App\Entity\User;
use App\Handlers\VIP\Models\UserVIP;
use App\Services\ClientCall;
use App\Services\ResponseReq;
use App\Services\SettingService;
use Symfony\Contracts\Translation\TranslatorInterface;

class VipHandler
{
    const DEFAULT_LANGUAGE = "ro";
    const URL_MANAGE_USER = 'ManageUser';

    private $doctrine;
    private $settings;
    private $language;
    private $host;
    private $urls;

    public function __construct(SettingService $settings, $doctrine = null, $language = self::DEFAULT_LANGUAGE)
    {
        $this->settings = $settings;
        $this->language = $language;
        $dataConnection = $this->getConnectionData();
        $this->host = $dataConnection['host'] . '/standard-atlante-plus/rest/';
        $this->urls = [
            // self::URL_SEARCH_HOTELS => $this->host . 'ricercaListini/ricercaHOT',
        ];
        $this->doctrine = $doctrine;
    }

    public function getConnectionData()
    {
        return [
            'host' => $this->settings->getSetting(Settings::NAME_VIP_HOST) ?: '',
            'society' => $this->settings->getSetting(Settings::NAME_VIP_SOCIETY) ?: '',
            'op_unit' => $this->settings->getSetting(Settings::NAME_VIP_OP_UNIT) ?: '',
            'default_client' => $this->settings->getSetting(Settings::NAME_VIP_DEFAULT_CLIENT) ?: '',
            'user' => $this->settings->getSetting(Settings::NAME_VIP_USER) ?: '',
            'password' => $this->settings->getSetting(Settings::NAME_VIP_PASSWORD) ?: '',
        ];
    }
    public function getAuth()
    {
        $dataConnection = $this->getConnectionData();
        return [
            "id" => $dataConnection['user'],
            "password" => $dataConnection['password'],
        ];
    }
    public function getClientCode($user = null)
    {
        $dataConnection = $this->getConnectionData();
        return $user ? $user->getClientCode($this->settings) : $dataConnection['default_client'];
    }

    public function createUser(TranslatorInterface $translator, $user = null)
    {
        $usrSettings = new \stdClass;
        $usrSettings->auth = $this->getAuth();
        $exist = $this->checkUserExistence($user);
        error_log("create user on vip");
        if ($exist->success) {
            error_log("create user exist and code return" . $exist->data['structAnaCliente']['datiGeneraliCliente']['codCliente12']);
            return ResponseReq::showResponse($exist->data['structAnaCliente']['datiGeneraliCliente']['codCliente12']);
        } else {
            error_log("check exist not success " . print_r($exist, true));
            error_log("current errorMessage " . $exist->errorMessage);
            if (strpos($exist->errorMessage, "ATL_WS_E0") === false && !empty($exist->errorMessage ?? '')) {
                error_log("errore non array e non vuoto " . is_array($exist->errorMessage));
                return ResponseReq::showResponse(false, $translator->trans($exist->errorMessage, [], 'security'), false);
            }
        }
        $searchObj = new UserVIP($usrSettings, $user, $this->settings, UserVIP::ACTION_INSERIMENTO_PURO);
        // LogHelper::writeOnLog(self::URL_MANAGE_USER, json_encode($searchObj), $this->urls[self::URL_MANAGE_USER]);
        // $val = ClientCall::call('POST', $this->urls[self::URL_MANAGE_USER], $searchObj);
        $val = "";
        if (!$val) {
            // LogHelper::writeVIPResponse(self::URL_MANAGE_USER);
            return ResponseReq::showResponse(null);
        }
        $result = $val->toArray();
        // LogHelper::writeVIPResponse(self::URL_MANAGE_USER, json_encode($result));
        if (isset($result['structAnaCliente']['datiGeneraliCliente']['codCliente12'])) {
            return ResponseReq::showResponse($result['structAnaCliente']['datiGeneraliCliente']['codCliente12']);
        }
        if (!$result['success']) {
            $message = $result['errorMessage'] ?? $result['error'] ?? $result['messaggio'] ?? null;
            if (is_array($message) && $message['code'] == "ATL_WS_E05") {
                // LogHelper::sendToChat('*' . self::URL_MANAGE_USER . ' in warning* errore creazione utente ' . ($searchObj->structAnaCliente->datiGeneraliCliente->email ?? '(couldn\'t find email)') . ': ' . json_encode($message), __FILE__, __LINE__, null, $this->settings);
                return ResponseReq::showResponse(false, $translator->trans('USER_SUSPENDED', [], 'security'), false);
            }
            // LogHelper::sendToChat('*' . self::URL_MANAGE_USER . ' in warning* errore creazione utente ' . ($searchObj->structAnaCliente->datiGeneraliCliente->email ?? '(couldn\'t find email)') . ': ' . json_encode($message), __FILE__, __LINE__, null, $this->settings);
            return $message ? ResponseReq::showResponse(false, $message['description'] ?? $message, false) : ResponseReq::showResponse(null);
        }
        return ResponseReq::showResponse($result);
    }
    public function getUser(User $user)
    {
        $usrSettings = new \stdClass();
        $usrSettings->auth = $this->getAuth();
        $searchObj = new UserVIP($usrSettings, $user, $this->settings, UserVip::ACTION_CONSULTAZIONE);
        // LogHelper::writeVIPRequest(self::URL_MANAGE_USER, json_encode($searchObj), $this->urls[self::URL_MANAGE_USER]);
        // $val = ClientCall::call('POST', $this->urls[self::URL_MANAGE_USER], $searchObj);
        $val = "";
        if (!$val) {
            // LogHelper::writeVIPResponse(self::URL_MANAGE_USER);
            return ['success' => false, 'data' => null, 'error' => null, 'responseSuccess' => null];
        }
        $result = $val->toArray();
        // LogHelper::writeVIPResponse(self::URL_MANAGE_USER, json_encode($result));
        if (!$result['success']) {
            $message = $result['errorMessage'] ?? $result['error'] ?? $result['messaggio'] ?? null;
            return ['success' => false, 'data' => false, 'error' => $message, 'responseSuccess' => false];
        }
        return ['success' => true, 'data' => $result];
    }

    private function checkUserExistence($user)
    {
        $vipUser = $this->getUser($user);

        if (!$vipUser['success']) {
            // LogHelper::writeOnLog('No match found', self::URL_MANAGE_USER, LogHelper::PLATFORM_VIP . '/' . self::URL_MANAGE_USER);
            return ResponseReq::showResponse($vipUser['data'], $vipUser['error'], $vipUser['responseSuccess']);
        }
        $data = $vipUser['data'];
        if (
            !isset($data['structAnaCliente']) ||
            !isset($data['structAnaCliente']['datiGeneraliCliente']) ||
            !isset($data['structAnaCliente']['datiContabiliCliente'])
        ) {
            // LogHelper::writeOnLog('Missing data from response', self::URL_MANAGE_USER, LogHelper::PLATFORM_VIP . '/' . self::URL_MANAGE_USER);
            return ResponseReq::showResponse(null);
        }
        $taxCode = strtoupper($data['structAnaCliente']['datiContabiliCliente']['codiceFiscale']);
        $vatCode = strtoupper($data['structAnaCliente']['datiContabiliCliente']['partitaIva']);
        $email = $data['structAnaCliente']['datiGeneraliCliente']['email'];
        $email = trim(strtolower($email));
        $currentEmail = trim(strtolower($user->getEmail()));
        $currentTaxCode = strtoupper($user->getTaxCode());
        $currentVatCode = strtoupper($user->getVatCode());
        $vatCheck = !(empty($user->getVatCode()) || $currentVatCode === $vatCode);
        $taxCheck = !(empty($currentTaxCode) || $currentTaxCode === $taxCode || $currentVatCode === $taxCode);

        if (($currentEmail === $email && $vatCheck) || ($currentEmail && $taxCheck)) {
            // LogHelper::writeOnLog('Tax doesn\'t match email', self::URL_MANAGE_USER, LogHelper::PLATFORM_VIP . '/' . self::URL_MANAGE_USER);
            return ResponseReq::showResponse(null, 'TAX_NO_MATCH_EMAIL', false);
        }
        if ($taxCode === $currentTaxCode && $currentEmail !== $email) {
            // LogHelper::writeOnLog('Email doesn\'t match tax', self::URL_MANAGE_USER, LogHelper::PLATFORM_VIP . '/' . self::URL_MANAGE_USER);
            return ResponseReq::showResponse(null, 'EMAIL_NO_MATCH_TAX', false);
        }
        // LogHelper::writeOnLog('Match found', self::URL_MANAGE_USER, LogHelper::PLATFORM_VIP . '/' . self::URL_MANAGE_USER);
        return ResponseReq::showResponse($data);
    }
    public function modifyUser($user)
    {
        $usrSettings = new \stdClass;
        $usrSettings->auth = $this->getAuth();
        $searchObj = new UserVIP($usrSettings, $user, $this->settings, UserVip::ACTION_VARIZAIONE_PURO);
        if (!$searchObj->structAnaCliente->datiGeneraliCliente->codCliente12) {
            // LogHelper::sendToChat('*' . self::URL_MANAGE_USER . " in error*: Richiesta di variazione sull\'utente vetrina con " . $searchObj->structAnaCliente->datiGeneraliCliente->email, __FILE__, __LINE__, null, $this->settings);
            return ResponseReq::showResponse(null);
        }
        // LogHelper::writeVIPRequest(self::URL_MANAGE_USER, json_encode($searchObj), $this->urls[self::URL_MANAGE_USER]);
        $val = ClientCall::call('POST', $this->urls[self::URL_MANAGE_USER], $searchObj);
        if (!$val) {
            // LogHelper::writeVIPResponse(self::URL_MANAGE_USER);
            return ResponseReq::showResponse(null);
        }
        $result = $val->toArray();
        // LogHelper::writeVIPResponse(self::URL_MANAGE_USER, json_encode($result));
        if (!$result['success']) {
            $message = $result['errorMessage'] ?? $result['error'] ?? $result['messaggio'] ?? null;
            // LogHelper::sendToChat('*' . self::URL_MANAGE_USER . ' in error*: ' . json_encode($message), __FILE__, __LINE__, null, $this->settings);
            return $message ? ResponseReq::showResponse(false, $message, false) : ResponseReq::showResponse(null);
        }
        return ResponseReq::showResponse($val->toArray());
    }

    public function callVIP($searchObj, $serviceName, $writeOnLog = false, $method = 'POST', $header = [], $typeBody = ClientCall::JSON, $try = 1)
    {
        $callUrl = $this->urls[$serviceName];
        $callId = uniqid($serviceName, true);
        // $writeOnLog && LogHelper::writeVIPRequest($serviceName, "Call ID: {$callId}\n" . json_encode($searchObj), $callUrl);
        $val = ClientCall::call($method, $callUrl, $searchObj, $header, $typeBody, $try, $this->settings, $callId);
        if (!$val) {
            // LogHelper::writeVIPResponse($serviceName, "Call ID: {$callId}\nNO RESPONSE", $writeOnLog ? false : $callUrl); //Write on log anyway if didn't get resposne
            return ['data' => null];
        }
        $result = $val->toArray();
        // $writeOnLog && LogHelper::writeVIPResponse($serviceName, "Call ID: {$callId}\n" . json_encode($result));
        if (!$result['success']) {
            $errorMessage = $result['error'] ?? $result['errorMessage'] ?? null;
            $logError = $errorMessage ?: "";
            is_string($logError) || $logError = json_encode($logError);
            // LogHelper::sendToChat("*{$serviceName} with call ID {$callId} in error*:\n{$logError}", __FILE__, __LINE__, null, $this->settings);
            // uncomment this when search exception will be fixed VIP side $writeOnLog || LogHelper::writeVIPCall($serviceName, json_encode($searchObj), json_encode($result), $callUrl);
            return ['data' => null, 'error' => $errorMessage, 'success' => false];
        }
        return ['data' => 'map', 'result' => $result];
    }

}