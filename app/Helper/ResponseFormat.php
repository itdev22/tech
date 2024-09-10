<?php

namespace App\Helper;

class ResponseFormat {
    /**
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return array<string, mixed>
     */
    public static function Success($data, string $message, int $status): array
    {
        return [
            'message' => $message,
            'status' => $status,
            'data' => $data,
        ];

        return $response;
    }

    public static function BadRequest( string $message, int $status): array
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}

