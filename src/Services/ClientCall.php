<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ClientCall extends Exception
{
    const TYPE_GET = 'GET';
    const STANDARD = 'body';
    const JSON = 'json';
    public static function call($type, $url, $data, $header = [], $typeBody = self::JSON, $try = 0, ?SettingService $settings = null, ?string $callId = null)
    {

        $header && $type === self::TYPE_GET && $typeBody == self::JSON && $header['Content-type'] = 'application/json';
        $header['Connection'] = 'keep-alive';
        // $client = HttpClient::create();
        $client = new CurlHttpClient(['headers' => $header]);
        $body = [
            $typeBody => $data,
            'max_duration' => 420,
        ];

        ini_set('max_execution_time', 0);
        $res = ($type === self::TYPE_GET && $typeBody == self::STANDARD) ? $client->request($type, $url . '?' . http_build_query($data)) : $client->request($type, $url, $body);
        try {
            //TODO check what to do with this idle timeout
            $code = $res->getStatusCode();
        } catch (ExceptionInterface $ex) {
            error_log("!!!!!!!!!! ERRORE da gestire  !!!!!!!!!!!!- " . $try . " - " . $ex->getMessage());
            $boldText = stristr($url, 'pratiche/gestioneIncassi') ? "*Payment registration error*\n" : '';
            // LogHelper::writeOnLog(($callId ? "Call ID: {$callId}\n" : '') . $boldText . $ex->getMessage() . " with data\n```\n" . json_encode($data) . "\n```", __FILE__, __LINE__, null, $settings);
            if ($try > 0 && $try < 5) {
                return self::call($type, $url, $data, $header, $typeBody, ($try + 1));
            } else {
                return null;
            }
        }
        /*if (200 !== $res->getStatusCode()) {
        return $res;
        }*/
        return $res;
    }
}