<?php

require_once __DIR__ . '/../../includes/settings_helpers.php';

header('Content-Type: application/json; charset=utf-8');

function settings_api_respond(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function settings_api_error_message(Throwable $exception): string
{
    if ($exception instanceof PDOException && $exception->getCode() === '23000') {
        return 'That option already exists or is still used by other records.';
    }

    return 'Something went wrong while updating the dropdown.';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    settings_api_respond([
        'success' => false,
        'message' => 'Method not allowed.',
    ], 405);
}

$action = trim((string) ($_POST['action'] ?? ''));
$dropdownKey = trim((string) ($_POST['dropdown_key'] ?? ''));

try {
    if (settings_dropdown_definition($dropdownKey) === null) {
        settings_api_respond([
            'success' => false,
            'message' => 'Invalid dropdown.',
        ], 422);
    }

    if ($action === 'add') {
        $result = settings_add_dropdown_option($pdo, $dropdownKey, (string) ($_POST['option_label'] ?? ''));
        settings_api_respond($result, $result['success'] ? 200 : 422);
    }

    if ($action === 'delete') {
        $result = settings_delete_dropdown_option($pdo, $dropdownKey, (int) ($_POST['option_id'] ?? 0));
        settings_api_respond($result, $result['success'] ? 200 : 422);
    }

    settings_api_respond([
        'success' => false,
        'message' => 'Invalid action.',
    ], 422);
} catch (Throwable $exception) {
    settings_api_respond([
        'success' => false,
        'message' => settings_api_error_message($exception),
    ], 500);
}
