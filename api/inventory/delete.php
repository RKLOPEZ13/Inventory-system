<?php

require_once __DIR__ . '/bootstrap.php';

inventory_api_require_post();

try {
    $inventoryId = isset($_POST['inventory_id']) ? (int) $_POST['inventory_id'] : 0;
    if ($inventoryId <= 0) {
        inventory_api_respond([
            'success' => false,
            'message' => 'A valid inventory item is required.',
        ], 422);
    }

    $pdo->beginTransaction();
    $result = inventory_delete_item($pdo, $inventoryId, inventory_default_actor_id());

    if (!$result['success']) {
        $pdo->rollBack();
        inventory_api_respond($result, 422);
    }

    $pdo->commit();
    inventory_api_respond($result);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    inventory_api_handle_exception($exception);
}
