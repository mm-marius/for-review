<?php

namespace App\Services;

use App\Models\Response;

class ResponseReq
{
    /**
     * show response if error, empty (no results) or correct
     */
    public static function showResponse($data, $error = null, $success = null, $target = null)
    {
        if (!$data) {
            return new Response(false, 400, null, print_r($error, true));
        }

        $controlSuccess = $success && isset($data[$success]) && $data[$success];

        if (!$controlSuccess && $success) {
            if ($error && isset($data[$error])) {
                return new Response(false, 500, null, 'Error: ' . $data[$error]);
            }
            return new Response(false, 500, null, 'Error 500');
        }

        if (!$target) {
            return new Response(true, 200, $data);
        }
        if (isset($data[$target])) {
            return new Response(true, 200, $data[$target]);
        }

        return new Response(false, 404, null, 'Target not found');

    }

}