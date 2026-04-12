<?php

require_once __DIR__ . '/../../includes/inventory_helpers.php';

header('Content-Type: application/json; charset=utf-8');

function inventory_api_respond(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function inventory_api_require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        inventory_api_respond([
            'success' => false,
            'message' => 'Method not allowed.',
        ], 405);
    }
}

function inventory_api_handle_exception(Throwable $exception): void
{
    $statusCode = 500;
    $message = 'Something went wrong while processing the request.';

    if ($exception instanceof PDOException && $exception->getCode() === '23000') {
        $statusCode = 422;
        $message = 'The submitted inventory data conflicts with an existing record.';
    }

    inventory_api_respond([
        'success' => false,
        'message' => $message,
    ], $statusCode);
}

