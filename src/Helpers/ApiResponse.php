<?php

namespace Habib\Dashboard\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ApiResponse
{
    /**
     * @param string $message
     * @param array $error
     * @param int $code
     * @return JsonResponse
     */
    public static function error(string $message, array $error = [], int $code = 400): JsonResponse
    {
        $status = false;
        return response()->json(compact('message', 'error', 'status'), $code);
    }

    /**
     * @param string $message
     * @param array|JsonResponse|JsonResource|Collection|Model $data
     * @param int $code
     * @return JsonResponse|JsonResource
     */
    public static function success(
        string $message,
        array|JsonResponse|JsonResource|Collection|Model $data = [],
        int $code = 200
    ): JsonResponse|JsonResource {
        $status = true;
        if ($data instanceof JsonResource) {
            return $data;
        }
        return response()->json(compact('message', 'data', 'status'), $code);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function notFound(?string $message = null): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message ?? __('main.not_found')], 404);
    }
}
