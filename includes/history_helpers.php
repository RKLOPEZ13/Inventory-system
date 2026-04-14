<?php

require_once __DIR__ . '/inventory_helpers.php';

function history_log_rows(PDO $pdo): array
{
    $sql = "
        SELECT
            dl.deployment_log_id,
            dl.inventory_no,
            dl.company_id,
            dl.category_id,
            dl.sub_category_id,
            dl.brand_id,
            dl.model,
            dl.serial_number,
            dl.deployed_to,
            dl.department_id,
            dl.deployment_status_id,
            dl.date_deployed,
            dl.returned_date,
            dl.deployed_by_user_id,
            dl.created_at,
            dl.updated_at,
            c.company_name,
            cat.category_name,
            sc.sub_category_name,
            b.brand_name,
            d.department_name,
            ds.status_name AS deployment_status_name,
            CONCAT(u.first_name, ' ', u.last_name) AS deployed_by_name
        FROM deployment_logs dl
        LEFT JOIN companies c ON c.company_id = dl.company_id
        LEFT JOIN categories cat ON cat.category_id = dl.category_id
        LEFT JOIN sub_categories sc ON sc.sub_category_id = dl.sub_category_id
        LEFT JOIN brands b ON b.brand_id = dl.brand_id
        LEFT JOIN departments d ON d.department_id = dl.department_id
        LEFT JOIN deployment_statuses ds ON ds.deployment_status_id = dl.deployment_status_id
        LEFT JOIN users u ON u.user_id = dl.deployed_by_user_id
        ORDER BY dl.created_at DESC, dl.deployment_log_id DESC
    ";

    $defaultActorName = inventory_default_actor_name($pdo);
    $rows = $pdo->query($sql)->fetchAll();

    foreach ($rows as &$row) {
        $resolvedName = trim((string) ($row['deployed_by_name'] ?? ''));
        $row['deployed_by_name'] = $resolvedName !== '' ? $resolvedName : $defaultActorName;
        $row['log_code'] = sprintf('DL-%05d', (int) $row['deployment_log_id']);
    }
    unset($row);

    return $rows;
}

function history_filter_columns(): array
{
    return [
        'company_id',
        'category_id',
        'department_id',
        'deployment_status_id',
    ];
}

function history_summary(PDO $pdo, array $rows): array
{
    $returnedCount = 0;
    $deployedCount = 0;
    $uniqueInventory = [];

    foreach ($rows as $row) {
        $status = strtoupper(trim((string) ($row['deployment_status_name'] ?? '')));

        if ($status === 'DEPLOYED') {
            $deployedCount += 1;
        }

        if (str_starts_with($status, 'RETURNED')) {
            $returnedCount += 1;
        }

        if (!empty($row['inventory_no'])) {
            $uniqueInventory[$row['inventory_no']] = true;
        }
    }

    return [
        'total_logs' => count($rows),
        'deployed_logs' => $deployedCount,
        'returned_logs' => $returnedCount,
        'unique_items' => count($uniqueInventory),
    ];
}

function history_page_payload(PDO $pdo): array
{
    $rows = history_log_rows($pdo);

    return [
        'logs' => $rows,
        'filters' => inventory_lookup_options($pdo, history_filter_columns()),
        'summary' => history_summary($pdo, $rows),
        'current_user_name' => inventory_default_actor_name($pdo),
    ];
}
