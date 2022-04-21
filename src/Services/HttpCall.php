<?php
namespace App\Services;

use App\Services\Exceptions\LimitExceeded;
use App\Services\Exceptions\RequestFailed;
use App\Services\Exceptions\ResponseFailed;

class HttpCall
{
    /** @var string API URL for v6 */
    private const apiURL = 'https://webservicesp.anaf.ro/PlatitorTvaRest/api/v6/ws/tva';

    /** @var int Limit for one time call */
    public const CIF_LIMIT = 500;

    /**
     * @param array $cifs
     * @return mixed
     * @throws LimitExceeded
     * @throws RequestFailed
     * @throws ResponseFailed
     */
    public static function call(array $cifs)
    {
        // Limit maxim numbers of cifs
        if (count($cifs) >= self::CIF_LIMIT) {
            throw new LimitExceeded('You can check one time up to 500 cifs.');
        }

        // Make request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($cifs),
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        // Check http code
        if (!isset($info['http_code']) || $info['http_code'] !== 200) {
            throw new ResponseFailed("Response status: {$info['http_code']} | Response body: {$response}");
        }

        // Get items
        $responseData = json_decode($response, true);

        // Check if have json because ANAF return errors in plain text
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ResponseFailed("Json parse error | Response body: {$response}");
        }

        // Check success stats
        if ("SUCCESS" !== $responseData['message'] || 200 !== $responseData['cod']) {
            throw new RequestFailed("Response message: {$responseData['message']} | Response body: {$response}");
        }

        return $responseData['found'];
    }

}