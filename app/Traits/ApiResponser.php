<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successResponse($data, $statusCode, $metaMessage)
    {
        return response()->json(
            [
                'meta' => [
                    'success' => true,
                    'code' => 20000,
                    'message' => $metaMessage ?? 'Request success',
                ],
                'data' => $data,
            ],
            $statusCode
        );
    }

    protected function errorResponse($errorMessage, $statusCode, $metaCode)
    {
        return response()->json(
            [
                'meta' => [
                    'success' => false,
                    'code' => $metaCode,
                    'message' => $errorMessage,
                ],
                'data' => null,
            ],
            $statusCode,
        );
    }

    protected function showAll(Collection $collection, $statusCode = null, $metaMessage = null)
    {
        return $this->successResponse(
            $collection->values(),
            $statusCode ?? 200,
            $metaMessage,
        );
    }

    protected function showOne($object, $statusCode = null, $metaMessage = null)
    {
        return $this->successResponse(
            $object,
            $statusCode ?? 200,
            $metaMessage,
        );
    }

    protected function showPaginate($resultKey, $resultValues, $nextPageUrl, $statusCode = null, $metaMessage = null)
    {
        return response()->json(
            [
                'meta' => [
                    'success' => true,
                    'code' => 20000,
                    'message' => $metaMessage ?? 'Request success',
                ],
                'data' => [
                    $resultKey => $resultValues,
                    'next_page_url' => $nextPageUrl
                ]
            ],
            $statusCode ?? 200
        );
    }
}
