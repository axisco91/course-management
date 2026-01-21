<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    // 👈 Quitamos el type-hint "mixed" porque solo existe en PHP 8
    public function sendResponse($result, string $message, int $statusCode = 200): JsonResponse
    {
        $responseData = [
            'success' => true,
            'message' => $message,
        ];

        if ($result instanceof LengthAwarePaginator) {
            $pagination = [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ];

            if ($result->hasMorePages()) {
                $pagination['next_page_url'] = $result->nextPageUrl();
            }

            if ($result->currentPage() > 1) {
                $pagination['prev_page_url'] = $result->previousPageUrl();
            }

            $responseData['data']       = $result->items();
            $responseData['pagination'] = $pagination;
        } else {
            $responseData['data'] = $result;
        }

        return response()->json($responseData, $statusCode);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
