<?php

require_once __DIR__ . '/bootstrap.php';

inventory_api_require_post();

try {
    $inventoryId = isset($_POST['inventory_id']) ? (int) $_POST['inventory_id'] : 0;

    if ($inventoryId > 0) {
        $result = inventory_update_item($pdo, $inventoryId, $_POST);
    } else {
        $result = inventory_insert_item($pdo, $_POST);
    }

    inventory_api_respond($result, $result['success'] ? 200 : 422);
} catch (Throwable $exception) {
    inventory_api_handle_exception($exception);
}

