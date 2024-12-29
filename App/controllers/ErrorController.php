<?php

namespace App\Controllers;


class ErrorController
{
    public static function notFound(string $message = 'notFound')
    {
        http_response_code(404);
        loadView('error', [
            'status' => 404,
            'message' => $message
        ]);
    }

    public static function UNAUTHORIZED(string $message = 'UNAUTHORIZED')
    {
        http_response_code(403);
        loadView('error', [
            'status' => 403,
            'message' => $message
        ]);
    }
}