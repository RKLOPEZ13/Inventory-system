<?php

require_once __DIR__ . '/bootstrap.php';

inventory_api_require_post();

try {
    $pdo->beginTransaction();
    $result = inventory_deploy_item($pdo, $_POST, 1);

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

