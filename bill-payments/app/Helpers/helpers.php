<?php

use Illuminate\Support\Facades\DB; // Import DB facade

if (!function_exists('formatDate')) {
    /**
     * Format a given date.
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'Y-m-d')
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('generateUniqueUserId')) {
    /**
     * Generate a unique user ID
     *
     * @return string
     */
    function generateUniqueUserId()
    {
        do {
            $uniqueId = 'user' . rand(1000, 9999);
            $user = DB::table('api_userdata')
                  ->where('user_id', $uniqueId)
                  ->first();
        } while ($user);
    
        return $uniqueId;
    }
}

if (!function_exists('apiResponse')) {
    /**
     * Return a standard JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @param bool $success
     * @return \Illuminate\Http\JsonResponse
     */
    function apiResponse($data = [], $message = '', $statusCode = 200, $success = true)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}

if (!function_exists('apiErrorResponse')) {
    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    function apiErrorResponse($message = 'An error occurred', $statusCode = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}

if (!function_exists('getUserByUserId')) {
    function getUserByUserId($user_id)
    {
        $user = \App\Models\User::where('user_id', $user_id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return $user;
    }
}
