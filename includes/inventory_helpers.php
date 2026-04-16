<?php

require_once __DIR__ . '/db.php';

function inventory_lookup_definitions(): array
{
    return [
        'company_id' => [
            'table' => 'companies',
            'value' => 'company_id',
            'label' => 'company_name',
            'order' => 'company_name',
        ],
        'category_id' => [
            'table' => 'categories',
            'value' => 'category_id',
            'label' => 'category_name',
            'order' => 'category_name',
        ],
        'sub_category_id' => [
            'table' => 'sub_categories',
            'value' => 'sub_category_id',
            'label' => 'sub_category_name',
            'order' => 'sub_category_name',
        ],
        'brand_id' => [
            'table' => 'brands',
            'value' => 'brand_id',
            'label' => 'brand_name',
            'order' => 'brand_name',
        ],
        'department_id' => [
            'table' => 'departments',
            'value' => 'department_id',
            'label' => 'department_name',
            'order' => 'department_name',
        ],
        'age_status_id' => [
            'table' => 'age_statuses',
            'value' => 'age_status_id',
            'label' => 'status_name',
            'order' => 'status_name',
        ],
        'deployment_status_id' => [
            'table' => 'deployment_statuses',
            'value' => 'deployment_status_id',
            'label' => 'status_name',
            'order' => 'sort_order, status_name',
        ],
        'inventory_status_id' => [
            'table' => 'inventory_statuses',
            'value' => 'inventory_status_id',
            'label' => 'status_name',
            'order' => 'sort_order, status_name',
        ],
    ];
}

function inventory_filter_columns(): array
{
    return [
        'category_id',
        'sub_category_id',
        'deployment_status_id',
    ];
}

function inventory_hidden_table_columns(): array
{
    return [
        'assigned_to',
        'department_id',
        'deployed_date',
        'returned_date',
    ];
}

function inventory_hidden_form_columns(): array
{
    return [
        'assigned_to',
        'department_id',
        'deployment_status_id',
        'inventory_status_id',
        'deployed_date',
        'returned_date',
    ];
}

function inventory_readonly_columns(): array
{
    return [
        'purchase_month',
        'purchase_year',
        'created_at',
        'updated_at',
    ];
}

function inventory_virtual_columns(): array
{
    return [
        [
            'name' => 'device_age_months',
            'data_type' => 'int',
            'column_type' => 'int(10) unsigned',
            'nullable' => true,
            'default' => null,
            'required' => false,
            'readonly' => false,
            'is_lookup' => false,
            'input_type' => 'number',
            'virtual' => true,
        ],
    ];
}

function inventory_inventory_no_prefix(): string
{
    return 'NCIA-';
}

function inventory_default_actor_id(): int
{
    return 1;
}

function inventory_default_actor_name(PDO $pdo): string
{
    static $name = null;

    if ($name !== null) {
        return $name;
    }

    $statement = $pdo->prepare(
        'SELECT CONCAT(first_name, " ", last_name) AS full_name
         FROM users
         WHERE user_id = :user_id
         LIMIT 1'
    );
    $statement->execute(['user_id' => inventory_default_actor_id()]);

    $resolved = trim((string) $statement->fetchColumn());
    $name = $resolved !== '' ? $resolved : 'System Admin';

    return $name;
}

function inventory_format_inventory_no(int $sequence): string
{
    return sprintf('%s%04d', inventory_inventory_no_prefix(), $sequence);
}

function inventory_current_max_inventory_sequence(PDO $pdo, bool $lock = false): int
{
    $prefix = inventory_inventory_no_prefix();
    $pattern = '^' . preg_quote($prefix, '/') . '[0-9]+$';
    $sequenceStart = strlen($prefix) + 1;

    $sql = sprintf(
        'SELECT inventory_no
         FROM inventory
         WHERE inventory_no REGEXP :pattern
         ORDER BY CAST(SUBSTRING(inventory_no, %d) AS UNSIGNED) DESC, inventory_id DESC
         LIMIT 1%s',
        $sequenceStart,
        $lock ? ' FOR UPDATE' : ''
    );

    $statement = $pdo->prepare($sql);
    $statement->execute(['pattern' => $pattern]);
    $inventoryNo = $statement->fetchColumn();

    if (!is_string($inventoryNo) || !preg_match('/(\d+)$/', $inventoryNo, $matches)) {
        return 0;
    }

    return max(0, (int) $matches[1]);
}

function inventory_next_inventory_no(PDO $pdo): string
{
    return inventory_format_inventory_no(inventory_current_max_inventory_sequence($pdo) + 1);
}

function inventory_schema_columns(PDO $pdo): array
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $sql = "
        SELECT
            COLUMN_NAME,
            DATA_TYPE,
            COLUMN_TYPE,
            IS_NULLABLE,
            COLUMN_DEFAULT,
            COLUMN_KEY,
            EXTRA
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'inventory'
          AND COLUMN_NAME <> 'inventory_id'
        ORDER BY ORDINAL_POSITION
    ";

    $lookupDefinitions = inventory_lookup_definitions();
    $readonlyColumns = inventory_readonly_columns();

    $statement = $pdo->query($sql);
    $columns = [];

    foreach ($statement->fetchAll() as $column) {
        $name = $column['COLUMN_NAME'];
        $dataType = strtolower((string) $column['DATA_TYPE']);

        $columns[] = [
            'name' => $name,
            'data_type' => $dataType,
            'column_type' => (string) $column['COLUMN_TYPE'],
            'nullable' => $column['IS_NULLABLE'] === 'YES',
            'default' => $column['COLUMN_DEFAULT'],
            'required' => $column['IS_NULLABLE'] === 'NO' && $column['COLUMN_DEFAULT'] === null && !in_array($name, $readonlyColumns, true),
            'readonly' => in_array($name, $readonlyColumns, true),
            'is_lookup' => array_key_exists($name, $lookupDefinitions),
            'input_type' => inventory_input_type($dataType, $name),
            'virtual' => false,
        ];
    }

    $columns = array_values(array_filter(
        $columns,
        static fn (array $column): bool => $column['name'] !== 'device_age_months'
    ));

    $virtualColumns = inventory_virtual_columns();
    $currentOsIndex = null;

    foreach ($columns as $index => $column) {
        if ($column['name'] === 'current_os') {
            $currentOsIndex = $index;
            break;
        }
    }

    if ($currentOsIndex === null) {
        $columns = array_merge($columns, $virtualColumns);
    } else {
        array_splice($columns, $currentOsIndex + 1, 0, $virtualColumns);
    }

    $cache = $columns;

    return $columns;
}

function inventory_input_type(string $dataType, string $name): string
{
    if (in_array($name, ['item_description', 'remarks'], true)) {
        return 'textarea';
    }

    if (str_contains($dataType, 'date') && $dataType !== 'datetime' && $dataType !== 'timestamp') {
        return 'date';
    }

    if ($dataType === 'text') {
        return 'textarea';
    }

    if (in_array($dataType, ['int', 'bigint', 'smallint', 'mediumint', 'tinyint', 'year'], true)) {
        return 'number';
    }

    return 'text';
}

function inventory_lookup_options(PDO $pdo, ?array $onlyColumns = null): array
{
    $definitions = inventory_lookup_definitions();
    $requested = $onlyColumns ? array_flip($onlyColumns) : null;
    $options = [];

    foreach ($definitions as $columnName => $definition) {
        if ($requested !== null && !isset($requested[$columnName])) {
            continue;
        }

        $sql = sprintf(
            'SELECT %s AS option_value, %s AS option_label FROM %s WHERE is_active = 1 ORDER BY %s',
            $definition['value'],
            $definition['label'],
            $definition['table'],
            $definition['order']
        );

        $options[$columnName] = $pdo->query($sql)->fetchAll();
    }

    return $options;
}

function inventory_lookup_id_by_label(PDO $pdo, string $columnName, string $label): ?int
{
    static $cache = [];

    $lookupDefinitions = inventory_lookup_definitions();
    if (!isset($lookupDefinitions[$columnName])) {
        return null;
    }

    if (!isset($cache[$columnName])) {
        $definition = $lookupDefinitions[$columnName];
        $sql = sprintf(
            'SELECT %s AS option_id, %s AS option_label FROM %s WHERE is_active = 1',
            $definition['value'],
            $definition['label'],
            $definition['table']
        );

        $cache[$columnName] = [];

        foreach ($pdo->query($sql)->fetchAll() as $row) {
            $cache[$columnName][strtoupper(trim((string) $row['option_label']))] = (int) $row['option_id'];
        }
    }

    $normalizedLabel = strtoupper(trim($label));
    return $cache[$columnName][$normalizedLabel] ?? null;
}

function inventory_required_status_ids(PDO $pdo): array
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [
        'deployment_available' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Available'),
        'deployment_unavailable' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Unavailable'),
        'deployment_deployed' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Deployed'),
        'deployment_borrowed' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Borrowed'),
        'deployment_temporary' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Temporary'),
        'deployment_transfer' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Transfer'),
        'deployment_returned' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Returned'),
        'deployment_returned_issue' => inventory_lookup_id_by_label($pdo, 'deployment_status_id', 'Returned with issue/s'),
        'inventory_available' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Available'),
        'inventory_not_available' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Not available'),
        'inventory_deleted' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Deleted'),
        'inventory_spare' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Spare'),
        'inventory_missing' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Missing'),
        'inventory_stolen' => inventory_lookup_id_by_label($pdo, 'inventory_status_id', 'Stolen'),
    ];

    return $cache;
}

function inventory_status_requires_unavailable_deployment(array $statusIds, ?int $inventoryStatusId): bool
{
    if ($inventoryStatusId === null || $inventoryStatusId <= 0) {
        return false;
    }

    $nonDeployableIds = array_filter([
        $statusIds['inventory_deleted'] ?? null,
        $statusIds['inventory_missing'] ?? null,
        $statusIds['inventory_stolen'] ?? null,
    ]);

    return in_array((int) $inventoryStatusId, array_map('intval', $nonDeployableIds), true);
}

function inventory_resolve_idle_deployment_status_id(array $statusIds, ?int $inventoryStatusId): ?int
{
    if (inventory_status_requires_unavailable_deployment($statusIds, $inventoryStatusId)) {
        return $statusIds['deployment_unavailable'] ?? $statusIds['deployment_available'] ?? null;
    }

    return $statusIds['deployment_available'] ?? null;
}

function inventory_items(PDO $pdo): array
{
    $statusIds = inventory_required_status_ids($pdo);
    $deletedStatusId = $statusIds['inventory_deleted'] ?? null;

    if ($deletedStatusId) {
        $statement = $pdo->prepare(
            'SELECT *
             FROM inventory
             WHERE inventory_status_id <> :deleted_status_id
             ORDER BY updated_at DESC, inventory_id DESC'
        );
        $statement->execute(['deleted_status_id' => $deletedStatusId]);
    } else {
        $statement = $pdo->query('SELECT * FROM inventory ORDER BY updated_at DESC, inventory_id DESC');
    }

    $items = $statement->fetchAll();

    foreach ($items as &$item) {
        $item = inventory_normalize_item_state($pdo, $item);
    }
    unset($item);

    return $items;
}

function inventory_find_item(PDO $pdo, int $inventoryId): ?array
{
    $statement = $pdo->prepare('SELECT * FROM inventory WHERE inventory_id = :inventory_id LIMIT 1');
    $statement->execute(['inventory_id' => $inventoryId]);
    $item = $statement->fetch();

    return $item ? inventory_normalize_item_state($pdo, $item) : null;
}

function inventory_normalize_item_state(PDO $pdo, array $item): array
{
    $statusIds = inventory_required_status_ids($pdo);

    if (empty($item['deployment_status_id']) && !empty($statusIds['deployment_available'])) {
        $item['deployment_status_id'] = $statusIds['deployment_available'];
    }

    if (empty($item['inventory_status_id'])) {
        $item['inventory_status_id'] = (int) ($item['deployment_status_id'] ?? 0) === (int) ($statusIds['deployment_available'] ?? 0)
            ? $statusIds['inventory_available']
            : $statusIds['inventory_not_available'];
    }

    if (inventory_status_requires_unavailable_deployment($statusIds, isset($item['inventory_status_id']) ? (int) $item['inventory_status_id'] : null)) {
        $item['deployment_status_id'] = inventory_resolve_idle_deployment_status_id(
            $statusIds,
            isset($item['inventory_status_id']) ? (int) $item['inventory_status_id'] : null
        );
    }

    return $item;
}

function inventory_lookup_label_maps(array $lookups): array
{
    $maps = [];

    foreach ($lookups as $columnName => $options) {
        $maps[$columnName] = [];

        foreach ($options as $option) {
            $maps[$columnName][(string) $option['option_value']] = (string) $option['option_label'];
        }
    }

    return $maps;
}

function inventory_summary_label(PDO $pdo, array $item, string $columnName, array $lookupMaps): string
{
    if ($columnName === 'age_status_id') {
        $ageStatusId = $item['age_status_id'] ?? null;

        if (($ageStatusId === null || $ageStatusId === '') && !empty($item['purchase_date'])) {
            $deviceAgeMonths = inventory_calculate_device_age_months((string) $item['purchase_date']);
            $ageStatusId = inventory_resolve_age_status_id($pdo, $deviceAgeMonths);
        }

        $resolved = $lookupMaps['age_status_id'][(string) $ageStatusId] ?? '';
        return $resolved !== '' ? $resolved : 'Unspecified';
    }

    $rawValue = $item[$columnName] ?? null;

    if ($rawValue === null || $rawValue === '') {
        return 'Unspecified';
    }

    $resolved = $lookupMaps[$columnName][(string) $rawValue] ?? '';
    return $resolved !== '' ? $resolved : (string) $rawValue;
}

function inventory_build_summary_metric(PDO $pdo, array $items, array $lookupMaps, string $columnName, string $title, string $distinctLabel): array
{
    if (!$items) {
        return [
            'title' => $title,
            'top_label' => 'No data',
            'top_count' => 0,
            'distinct_count' => 0,
            'distinct_label' => $distinctLabel,
        ];
    }

    $counts = [];

    foreach ($items as $item) {
        $label = inventory_summary_label($pdo, $item, $columnName, $lookupMaps);
        $counts[$label] = ($counts[$label] ?? 0) + 1;
    }

    uksort($counts, static fn (string $left, string $right): int => strcasecmp($left, $right));
    arsort($counts);

    $topLabel = (string) array_key_first($counts);
    $topCount = (int) current($counts);

    return [
        'title' => $title,
        'top_label' => $topLabel,
        'top_count' => $topCount,
        'distinct_count' => count($counts),
        'distinct_label' => $distinctLabel,
    ];
}

function inventory_summary_metrics(PDO $pdo, array $items, array $lookups): array
{
    $lookupMaps = inventory_lookup_label_maps($lookups);

    return [
        'deployment_status_id' => inventory_build_summary_metric($pdo, $items, $lookupMaps, 'deployment_status_id', 'Deployment Status', 'statuses'),
        'company_id' => inventory_build_summary_metric($pdo, $items, $lookupMaps, 'company_id', 'Company', 'companies'),
        'category_id' => inventory_build_summary_metric($pdo, $items, $lookupMaps, 'category_id', 'Category', 'categories'),
        'age_status_id' => inventory_build_summary_metric($pdo, $items, $lookupMaps, 'age_status_id', 'Age Status', 'age groups'),
    ];
}

function inventory_form_columns(PDO $pdo): array
{
    $hiddenColumns = array_flip(inventory_hidden_form_columns());

    return array_values(array_filter(
        inventory_schema_columns($pdo),
        static fn (array $column): bool => !$column['readonly'] && !isset($hiddenColumns[$column['name']])
    ));
}

function inventory_page_columns(PDO $pdo): array
{
    $hiddenColumns = array_flip(inventory_hidden_table_columns());
    $visibleColumns = array_values(array_filter(
        inventory_schema_columns($pdo),
        static fn (array $column): bool => !isset($hiddenColumns[$column['name']])
    ));

    $preferredOrder = [
        'inventory_no',
        'inventory_status_id',
        'deployment_status_id',
    ];
    $orderIndex = array_flip($preferredOrder);

    usort($visibleColumns, static function (array $left, array $right) use ($orderIndex): int {
        $leftOrder = $orderIndex[$left['name']] ?? PHP_INT_MAX;
        $rightOrder = $orderIndex[$right['name']] ?? PHP_INT_MAX;

        if ($leftOrder !== $rightOrder) {
            return $leftOrder <=> $rightOrder;
        }

        return 0;
    });

    return $visibleColumns;
}

function inventory_calculate_device_age_months(?string $purchaseDate): ?int
{
    if (!$purchaseDate) {
        return null;
    }

    try {
        $purchase = new DateTimeImmutable($purchaseDate);
        $today = new DateTimeImmutable('today');
    } catch (Exception $exception) {
        return null;
    }

    if ($purchase > $today) {
        return 0;
    }

    $diff = $purchase->diff($today);
    return ($diff->y * 12) + $diff->m;
}

function inventory_resolve_age_status_id(PDO $pdo, ?int $deviceAgeMonths): ?int
{
    if ($deviceAgeMonths === null) {
        return null;
    }

    static $statusMap = null;

    if ($statusMap === null) {
        $statement = $pdo->query("SELECT age_status_id, UPPER(status_name) AS status_key FROM age_statuses WHERE is_active = 1");
        $statusMap = [];

        foreach ($statement->fetchAll() as $row) {
            $statusMap[$row['status_key']] = (int) $row['age_status_id'];
        }
    }

    $targetStatus = $deviceAgeMonths >= 60 ? 'OLD' : 'NEW';
    return $statusMap[$targetStatus] ?? null;
}

function inventory_prepare_payload(PDO $pdo, array $source): array
{
    $payload = [];
    $errors = [];

    foreach (inventory_form_columns($pdo) as $column) {
        $name = $column['name'];

        if (!empty($column['virtual'])) {
            continue;
        }

        if ($name === 'inventory_no') {
            continue;
        }

        $rawValue = $source[$name] ?? null;
        $value = is_string($rawValue) ? trim($rawValue) : $rawValue;

        if ($value === '') {
            $value = null;
        }

        if ($column['required'] && $value === null) {
            $errors[] = $name . ' is required.';
            continue;
        }

        if ($value === null) {
            $payload[$name] = null;
            continue;
        }

        if ($column['input_type'] === 'number') {
            $payload[$name] = (int) $value;
            continue;
        }

        $payload[$name] = $value;
    }

    $calculatedDeviceAgeMonths = inventory_calculate_device_age_months($payload['purchase_date'] ?? null);
    $payload['age_status_id'] = inventory_resolve_age_status_id($pdo, $calculatedDeviceAgeMonths);

    return [$payload, $errors];
}

function inventory_apply_insert_defaults(PDO $pdo, array $payload): array
{
    $statusIds = inventory_required_status_ids($pdo);

    if (!array_key_exists('deployment_status_id', $payload) || $payload['deployment_status_id'] === null) {
        $payload['deployment_status_id'] = $statusIds['deployment_available'];
    }

    if (!array_key_exists('inventory_status_id', $payload) || $payload['inventory_status_id'] === null) {
        $payload['inventory_status_id'] = $statusIds['inventory_available'];
    }

    return $payload;
}

function inventory_insert_item(PDO $pdo, array $source): array
{
    return inventory_insert_items($pdo, $source, 1);
}

function inventory_insert_items(PDO $pdo, array $source, int $requestedCount): array
{
    [$payload, $errors] = inventory_prepare_payload($pdo, $source);

    if ($errors) {
        return ['success' => false, 'message' => implode(' ', $errors)];
    }

    $payload = inventory_apply_insert_defaults($pdo, $payload);
    $count = max(1, $requestedCount);

    $columns = array_merge(['inventory_no'], array_keys($payload));
    $placeholders = array_map(static fn (string $name): string => ':' . $name, $columns);

    $sql = sprintf(
        'INSERT INTO inventory (%s) VALUES (%s)',
        implode(', ', $columns),
        implode(', ', $placeholders)
    );

    $statement = $pdo->prepare($sql);
    $createdIds = [];
    $firstInventoryNo = null;
    $lastInventoryNo = null;

    try {
        $pdo->beginTransaction();
        $nextSequence = inventory_current_max_inventory_sequence($pdo, true) + 1;

        for ($offset = 0; $offset < $count; $offset += 1) {
            $inventoryNo = inventory_format_inventory_no($nextSequence + $offset);
            $insertPayload = array_merge(['inventory_no' => $inventoryNo], $payload);

            $statement->execute($insertPayload);
            $createdIds[] = (int) $pdo->lastInsertId();

            $firstInventoryNo ??= $inventoryNo;
            $lastInventoryNo = $inventoryNo;
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $exception;
    }

    $message = $count === 1
        ? 'Item added successfully.'
        : sprintf('%d items added successfully.', $count);

    return [
        'success' => true,
        'message' => $message,
        'inventory_id' => $createdIds[0] ?? null,
        'inventory_ids' => $createdIds,
        'inventory_no' => $firstInventoryNo,
        'inventory_no_last' => $lastInventoryNo,
    ];
}

function inventory_update_item(PDO $pdo, int $inventoryId, array $source): array
{
    if (!inventory_find_item($pdo, $inventoryId)) {
        return ['success' => false, 'message' => 'Inventory item not found.'];
    }

    [$payload, $errors] = inventory_prepare_payload($pdo, $source);

    if ($errors) {
        return ['success' => false, 'message' => implode(' ', $errors)];
    }

    $assignments = array_map(
        static fn (string $name): string => $name . ' = :' . $name,
        array_keys($payload)
    );

    $payload['inventory_id'] = $inventoryId;

    $sql = sprintf(
        'UPDATE inventory SET %s WHERE inventory_id = :inventory_id',
        implode(', ', $assignments)
    );

    $statement = $pdo->prepare($sql);
    $statement->execute($payload);

    return [
        'success' => true,
        'message' => 'Item updated successfully.',
        'inventory_id' => $inventoryId,
    ];
}

function inventory_append_log(PDO $pdo, array $snapshot, int $userId, ?string $issueDescription = null): void
{
    $statement = $pdo->prepare(
        'INSERT INTO deployment_logs (
            inventory_no,
            company_id,
            category_id,
            sub_category_id,
            brand_id,
            model,
            serial_number,
            deployed_to,
            department_id,
            deployment_status_id,
            inventory_status_id,
            date_deployed,
            returned_date,
            issue_description,
            deployed_by_user_id
        ) VALUES (
            :inventory_no,
            :company_id,
            :category_id,
            :sub_category_id,
            :brand_id,
            :model,
            :serial_number,
            :deployed_to,
            :department_id,
            :deployment_status_id,
            :inventory_status_id,
            :date_deployed,
            :returned_date,
            :issue_description,
            :deployed_by_user_id
        )'
    );

    $statement->execute([
        'inventory_no' => $snapshot['inventory_no'],
        'company_id' => $snapshot['company_id'] ?: null,
        'category_id' => $snapshot['category_id'] ?: null,
        'sub_category_id' => $snapshot['sub_category_id'] ?: null,
        'brand_id' => $snapshot['brand_id'] ?: null,
        'model' => $snapshot['model'] ?: null,
        'serial_number' => $snapshot['serial_number'] ?: null,
        'deployed_to' => $snapshot['assigned_to'] ?: null,
        'department_id' => $snapshot['department_id'] ?: null,
        'deployment_status_id' => $snapshot['deployment_status_id'] ?: null,
        'inventory_status_id' => $snapshot['inventory_status_id'] ?: null,
        'date_deployed' => $snapshot['deployed_date'] ?: null,
        'returned_date' => $snapshot['returned_date'] ?: null,
        'issue_description' => $issueDescription !== null && trim($issueDescription) !== '' ? trim($issueDescription) : null,
        'deployed_by_user_id' => $userId,
    ]);
}

function inventory_apply_current_state(PDO $pdo, int $inventoryId, array $state): void
{
    $statement = $pdo->prepare(
        'UPDATE inventory
         SET assigned_to = :assigned_to,
             department_id = :department_id,
             deployment_status_id = :deployment_status_id,
             inventory_status_id = :inventory_status_id,
             deployed_date = :deployed_date,
             returned_date = :returned_date
         WHERE inventory_id = :inventory_id'
    );

    $statement->execute([
        'assigned_to' => $state['assigned_to'] ?: null,
        'department_id' => $state['department_id'] ?: null,
        'deployment_status_id' => $state['deployment_status_id'] ?: null,
        'inventory_status_id' => $state['inventory_status_id'] ?: null,
        'deployed_date' => $state['deployed_date'] ?: null,
        'returned_date' => $state['returned_date'] ?: null,
        'inventory_id' => $inventoryId,
    ]);
}

function inventory_action_requires_active_deployment(string $actionType): bool
{
    return in_array($actionType, ['return', 'return_issue', 'transfer'], true);
}

function inventory_active_deployment_labels(): array
{
    return ['DEPLOYED', 'BORROWED', 'TEMPORARY', 'TRANSFER'];
}

function inventory_deployable_inventory_labels(): array
{
    return ['AVAILABLE', 'SPARE'];
}

function inventory_resolve_action_target(string $actionType): ?array
{
    return match ($actionType) {
        'deploy_full' => [
            'deployment_status_key' => 'deployment_deployed',
            'message' => 'Item deployed successfully.',
        ],
        'deploy_borrow' => [
            'deployment_status_key' => 'deployment_borrowed',
            'message' => 'Item borrowed successfully.',
        ],
        'deploy_temporary' => [
            'deployment_status_key' => 'deployment_temporary',
            'message' => 'Temporary deployment recorded successfully.',
        ],
        'transfer' => [
            'deployment_status_key' => 'deployment_transfer',
            'message' => 'Item transferred successfully.',
        ],
        'return' => [
            'deployment_status_key' => 'deployment_returned',
            'message' => 'Item returned successfully.',
        ],
        'return_issue' => [
            'deployment_status_key' => 'deployment_returned_issue',
            'message' => 'Item returned with issues successfully.',
        ],
        default => null,
    };
}

function inventory_current_status_labels(PDO $pdo, array $item): array
{
    static $maps = [];

    if (!$maps) {
        foreach (inventory_lookup_options($pdo, ['deployment_status_id', 'inventory_status_id']) as $columnName => $options) {
            $maps[$columnName] = [];
            foreach ($options as $option) {
                $maps[$columnName][(string) $option['option_value']] = strtoupper(trim((string) $option['option_label']));
            }
        }
    }

    return [
        'deployment' => $maps['deployment_status_id'][(string) ($item['deployment_status_id'] ?? '')] ?? '',
        'inventory' => $maps['inventory_status_id'][(string) ($item['inventory_status_id'] ?? '')] ?? '',
    ];
}

function inventory_deploy_item(PDO $pdo, array $source, int $userId = 1): array
{
    $inventoryId = isset($source['inventory_id']) ? (int) $source['inventory_id'] : 0;
    $actionType = trim((string) ($source['action_type'] ?? ''));
    $item = inventory_find_item($pdo, $inventoryId);

    if (!$item) {
        return ['success' => false, 'message' => 'Inventory item not found.'];
    }

    $target = inventory_resolve_action_target($actionType);
    if ($target === null) {
        return ['success' => false, 'message' => 'A valid deployment action is required.'];
    }

    $statusIds = inventory_required_status_ids($pdo);
    $labels = inventory_current_status_labels($pdo, $item);
    $today = date('Y-m-d');
    $issueDescription = trim((string) ($source['issue_description'] ?? ''));

    if (inventory_action_requires_active_deployment($actionType)) {
        if (!in_array($labels['deployment'], inventory_active_deployment_labels(), true)) {
            return ['success' => false, 'message' => 'Only deployed items can be returned or transferred.'];
        }
    } elseif (!in_array($labels['deployment'], ['AVAILABLE', 'RETURNED', 'RETURNED WITH ISSUE/S'], true)) {
        return ['success' => false, 'message' => 'This item is not currently available for deployment.'];
    }

    if (!inventory_action_requires_active_deployment($actionType) && !in_array($labels['inventory'], inventory_deployable_inventory_labels(), true)) {
        return ['success' => false, 'message' => 'Only available or spare items can be deployed.'];
    }

    if ($labels['inventory'] === 'DELETED') {
        return ['success' => false, 'message' => 'Deleted items cannot be deployed or returned.'];
    }

    if (in_array($actionType, ['deploy_full', 'deploy_borrow', 'deploy_temporary', 'transfer'], true)) {
        $deployedTo = trim((string) ($source['deployed_to'] ?? ''));
        $departmentId = isset($source['department_id']) && $source['department_id'] !== ''
            ? (int) $source['department_id']
            : 0;

        if ($deployedTo === '') {
            return ['success' => false, 'message' => 'Deployed to is required.'];
        }

        if ($departmentId <= 0) {
            return ['success' => false, 'message' => 'Department is required.'];
        }

        $nextState = $item;
        $nextState['assigned_to'] = $deployedTo;
        $nextState['department_id'] = $departmentId;
        $nextState['deployment_status_id'] = $statusIds[$target['deployment_status_key']];
        $nextState['inventory_status_id'] = $statusIds['inventory_not_available'];
        $nextState['deployed_date'] = $today;
        $nextState['returned_date'] = null;

        inventory_apply_current_state($pdo, $inventoryId, $nextState);
        inventory_append_log($pdo, $nextState, $userId);

        return [
            'success' => true,
            'message' => $target['message'],
            'inventory_id' => $inventoryId,
        ];
    }

    $selectedInventoryStatusId = $statusIds['inventory_available'] ?? 0;

    if ($actionType === 'return_issue' && $issueDescription === '') {
        return ['success' => false, 'message' => 'Issue description is required for returned with issues.'];
    }

    $logSnapshot = $item;
    $logSnapshot['deployment_status_id'] = $statusIds[$target['deployment_status_key']];
    $logSnapshot['inventory_status_id'] = $selectedInventoryStatusId;
    $logSnapshot['returned_date'] = $today;

    inventory_append_log($pdo, $logSnapshot, $userId, $issueDescription);

    $returnedState = $item;
    $returnedState['assigned_to'] = null;
    $returnedState['department_id'] = null;
    $returnedState['inventory_status_id'] = $selectedInventoryStatusId;
    $returnedState['deployment_status_id'] = $statusIds[$target['deployment_status_key']];
    $returnedState['deployed_date'] = null;
    $returnedState['returned_date'] = null;

    inventory_apply_current_state($pdo, $inventoryId, $returnedState);

    return [
        'success' => true,
        'message' => $target['message'],
        'inventory_id' => $inventoryId,
    ];
}

function inventory_delete_item(PDO $pdo, int $inventoryId, int $userId = 1): array
{
    $item = inventory_find_item($pdo, $inventoryId);
    if (!$item) {
        return ['success' => false, 'message' => 'Inventory item not found.'];
    }

    $statusIds = inventory_required_status_ids($pdo);
    if ((int) ($item['inventory_status_id'] ?? 0) === (int) ($statusIds['inventory_deleted'] ?? 0)) {
        return ['success' => false, 'message' => 'Item is already marked as deleted.'];
    }

    $logSnapshot = $item;
    $logSnapshot['inventory_status_id'] = $statusIds['inventory_deleted'];
    $logSnapshot['deployment_status_id'] = inventory_resolve_idle_deployment_status_id($statusIds, $statusIds['inventory_deleted']);

    inventory_append_log($pdo, $logSnapshot, $userId, 'Item marked as deleted.');

    $deletedState = $item;
    $deletedState['assigned_to'] = null;
    $deletedState['department_id'] = null;
    $deletedState['inventory_status_id'] = $statusIds['inventory_deleted'];
    $deletedState['deployment_status_id'] = inventory_resolve_idle_deployment_status_id($statusIds, $statusIds['inventory_deleted']);
    $deletedState['deployed_date'] = null;
    $deletedState['returned_date'] = null;

    inventory_apply_current_state($pdo, $inventoryId, $deletedState);

    return [
        'success' => true,
        'message' => 'Item marked as deleted successfully.',
        'inventory_id' => $inventoryId,
    ];
}

function inventory_page_payload(PDO $pdo): array
{
    $columns = inventory_page_columns($pdo);
    $items = inventory_items($pdo);
    $lookups = inventory_lookup_options($pdo);

    return [
        'columns' => $columns,
        'items' => $items,
        'filters' => inventory_lookup_options($pdo, inventory_filter_columns()),
        'lookups' => $lookups,
        'summaries' => inventory_summary_metrics($pdo, $items, $lookups),
        'form_columns' => inventory_form_columns($pdo),
        'next_inventory_no' => inventory_next_inventory_no($pdo),
    ];
}
