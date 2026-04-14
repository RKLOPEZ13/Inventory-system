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
        'custodian_id' => [
            'table' => 'custodians',
            'value' => 'custodian_id',
            'label' => 'custodian_name',
            'order' => 'custodian_name',
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
        'inventory_status_id' => [
            'table' => 'inventory_statuses',
            'value' => 'inventory_status_id',
            'label' => 'status_name',
            'order' => 'sort_order, status_name',
        ],
        'deployment_status_id' => [
            'table' => 'deployment_statuses',
            'value' => 'deployment_status_id',
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
        'inventory_status_id',
    ];
}

function inventory_hidden_table_columns(): array
{
    return [
        'custodian_id',
        'department_id',
        'deployment_status_id',
        'deployed_date',
        'returned_date',
    ];
}

function inventory_hidden_form_columns(): array
{
    return [
        'custodian_id',
        'department_id',
        'deployment_status_id',
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

function inventory_items(PDO $pdo): array
{
    $statement = $pdo->query('SELECT * FROM inventory ORDER BY updated_at DESC, inventory_id DESC');
    return $statement->fetchAll();
}

function inventory_find_item(PDO $pdo, int $inventoryId): ?array
{
    $statement = $pdo->prepare('SELECT * FROM inventory WHERE inventory_id = :inventory_id LIMIT 1');
    $statement->execute(['inventory_id' => $inventoryId]);
    $item = $statement->fetch();

    return $item ?: null;
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

    return array_values(array_filter(
        inventory_schema_columns($pdo),
        static fn (array $column): bool => !isset($hiddenColumns[$column['name']])
    ));
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

function inventory_delete_item(PDO $pdo, int $inventoryId): array
{
    $item = inventory_find_item($pdo, $inventoryId);
    if (!$item) {
        return ['success' => false, 'message' => 'Inventory item not found.'];
    }

    $statement = $pdo->prepare('DELETE FROM inventory WHERE inventory_id = :inventory_id');
    $statement->execute(['inventory_id' => $inventoryId]);

    return [
        'success' => true,
        'message' => 'Item deleted successfully.',
        'inventory_id' => $inventoryId,
    ];
}

function inventory_deploy_item(PDO $pdo, array $source, int $userId = 1): array
{
    $inventoryId = isset($source['inventory_id']) ? (int) $source['inventory_id'] : 0;
    $item = inventory_find_item($pdo, $inventoryId);

    if (!$item) {
        return ['success' => false, 'message' => 'Inventory item not found.'];
    }

    $deploymentStatusId = isset($source['deployment_status_id']) && $source['deployment_status_id'] !== ''
        ? (int) $source['deployment_status_id']
        : null;
    $inventoryStatusId = isset($source['inventory_status_id']) && $source['inventory_status_id'] !== ''
        ? (int) $source['inventory_status_id']
        : (isset($item['inventory_status_id']) ? (int) $item['inventory_status_id'] : null);

    if ($deploymentStatusId === null || $inventoryStatusId === null) {
        return ['success' => false, 'message' => 'Deployment status is required.'];
    }

    $custodianId = isset($source['custodian_id']) && $source['custodian_id'] !== '' ? (int) $source['custodian_id'] : null;
    $departmentId = isset($source['department_id']) && $source['department_id'] !== '' ? (int) $source['department_id'] : null;
    $deployedDate = !empty($source['deployed_date']) ? $source['deployed_date'] : date('Y-m-d');
    $returnedDate = !empty($source['returned_date']) ? $source['returned_date'] : null;

    if ($custodianId === null || $departmentId === null) {
        return ['success' => false, 'message' => 'Deploy To and Department are required.'];
    }

    $updateStatement = $pdo->prepare(
        'UPDATE inventory
         SET custodian_id = :custodian_id,
             department_id = :department_id,
             deployment_status_id = :deployment_status_id,
             inventory_status_id = :inventory_status_id,
             deployed_date = :deployed_date,
             returned_date = :returned_date
         WHERE inventory_id = :inventory_id'
    );

    $updateStatement->execute([
        'custodian_id' => $custodianId,
        'department_id' => $departmentId,
        'deployment_status_id' => $deploymentStatusId,
        'inventory_status_id' => $inventoryStatusId,
        'deployed_date' => $deployedDate,
        'returned_date' => $returnedDate,
        'inventory_id' => $inventoryId,
    ]);

    $custodianName = null;
    if ($custodianId !== null) {
        $custodianStatement = $pdo->prepare('SELECT custodian_name FROM custodians WHERE custodian_id = :custodian_id');
        $custodianStatement->execute(['custodian_id' => $custodianId]);
        $custodianName = $custodianStatement->fetchColumn() ?: null;
    }

    $logStatement = $pdo->prepare(
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
            date_deployed,
            returned_date,
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
            :date_deployed,
            :returned_date,
            :deployed_by_user_id
        )'
    );

    $logStatement->execute([
        'inventory_no' => $item['inventory_no'],
        'company_id' => $item['company_id'],
        'category_id' => $item['category_id'],
        'sub_category_id' => $item['sub_category_id'],
        'brand_id' => $item['brand_id'],
        'model' => $item['model'],
        'serial_number' => $item['serial_number'],
        'deployed_to' => $custodianName,
        'department_id' => $departmentId,
        'deployment_status_id' => $deploymentStatusId,
        'date_deployed' => $deployedDate,
        'returned_date' => $returnedDate,
        'deployed_by_user_id' => $userId,
    ]);

    return [
        'success' => true,
        'message' => 'Item deployed successfully.',
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
        'form_columns' => inventory_form_columns($pdo),
        'next_inventory_no' => inventory_next_inventory_no($pdo),
    ];
}
