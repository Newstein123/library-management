<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function successResponse(array $data = null, $errors = null) {
        return response()->json([
            'success' => true,
            'data' => $data,
            'error' => $errors
        ], 200);
    }

    public function failResponse(array $data = null, array $error = null) {
        return response()->json([
            'success' => false,
            'data' => null,
            'error' => [
                'code' => $error['code'],
                'message' => $error['message'],
                'details' => $error['details'] ?? null,
            ],
        ], 500);
    }
}
