<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse as IlluminateJsonResponse;

class JsonResponse
{
    public static function success($message, $data = null)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, 200);
    }

    public static function notFound($message, $statusCode = 404)
    {
        $response = [
            'status' => 'not found',
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }

    public static function error($message, $output=null, $statusCode = 500)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($output !== null) {
            $response['output'] = $output;
        }

        return response()->json($response, $statusCode);
    }
}
