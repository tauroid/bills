<?php

namespace App\Http;

use Illuminate\Http\Response;

class APIResponse {
    private APIResponseType $type;
    private string $message;
    private array $data;
    private int $statusCode;

    function __construct(
        APIResponseType $type,
        string $message,
        array $data,
        int $statusCode
    ) {
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    function toResponse(): Response {
        return response([
            'type' => $this->type,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->statusCode);
    }
}
