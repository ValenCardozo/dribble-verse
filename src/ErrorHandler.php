<?php

declare(strict_types=1);

class ErrorHandler
{
    public static function handleError(
        int $errorNumber,
        string $errorMessage,
        string $errorFile,
        int $errorLine
    ): void
    {
        throw new ErrorException(
            $errorMessage,
            0,
            $errorNumber,
            $errorFile,
            $errorLine
        );
    }

    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }
}