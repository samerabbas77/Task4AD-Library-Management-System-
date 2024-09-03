<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     *  successResponse when the response is success
     * @param mixed $data
     * @param string $message
     * @param int $httpResponseCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse( $data , string $message = 'Success', int $httpResponseCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => null,
        ], $httpResponseCode);
    }
}
