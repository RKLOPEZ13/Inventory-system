<?php

require_once __DIR__ . '/inventory_helpers.php';

function settings_dropdown_definitions(): array
{
    static $definitions = null;

    if ($definitions !== null) {
        return $definitions;
    }

    $lookupDefinitions = inventory_lookup_definitions();
    $labels = [
        'age_status_id' => 'Age Statuses',
        'brand_id' => 'Brands',
        'category_id' => 'Categories',
        'company_id' => 'Companies',
        'department_id' => 'Departments',
        'deployment_status_id' => 'Deployment Statuses',
        'inventory_status_id' => 'Inventory Statuses',
        'sub_category_id' => 'Sub Categories',
    ];
    $references = [
        'age_status_id' => [
            ['table' => 'inventory', 'column' => 'age_status_id'],
        ],
        'brand_id' => [
            ['table' => 'inventory', 'column' => 'brand_id'],
            ['table' => 'deployment_logs', 'column' => 'brand_id'],
            ['table' => 'inventory_update_logs', 'column' => 'brand_id'],
        ],
        'category_id' => [
            ['table' => 'inventory', 'column' => 'category_id'],
            ['table' => 'deployment_logs', 'column' => 'category_id'],
            ['table' => 'inventory_update_logs', 'column' => 'category_id'],
        ],
        'company_id' => [
            ['table' => 'inventory', 'column' => 'company_id'],
            ['table' => 'deployment_logs', 'column' => 'company_id'],
            ['table' => 'inventory_update_logs', 'column' => 'company_id'],
        ],
        'department_id' => [
            ['table' => 'inventory', 'column' => 'department_id'],
            ['table' => 'deployment_logs', 'column' => 'department_id'],
        ],
        'deployment_status_id' => [
            ['table' => 'inventory', 'column' => 'deployment_status_id'],
            ['table' => 'deployment_logs', 'column' => 'deployment_status_id'],
        ],
        'inventory_status_id' => [
            ['table' => 'inventory', 'column' => 'inventory_status_id'],
        ],
        'sub_category_id' => [
            ['table' => 'inventory', 'column' => 'sub_category_id'],
            ['table' => 'deployment_logs', 'column' => 'sub_category_id'],
            ['table' => 'inventory_update_logs', 'column' => 'sub_category_id'],
        ],
    ];

    $definitions = [];

    foreach ($labels as $key => $title) {
        if (!isset($lookupDefinitions[$key])) {
            continue;
        }

        $definition = $lookupDefinitions[$key];
        $definition['key'] = $key;
        $definition['title'] = $title;
        $definition['references'] = $references[$key] ?? [];
        $definition['code_column'] = in_array($key, ['company_id', 'department_id'], true)
            ? ($key === 'company_id' ? 'company_code' : 'department_code')
            : null;
        $definition['sort_column'] = in_array($key, ['deployment_status_id', 'inventory_status_id'], true)
            ? 'sort_order'
            : null;

        $definitions[$key] = $definition;
    }

    return $definitions;
}

function settings_dropdown_definition(string $key): ?array
{
    $definitions = settings_dropdown_definitions();
    return $definitions[$key] ?? null;
}

function settings_dropdown_options(PDO $pdo, string $key): array
{
    $definition = settings_dropdown_definition($key);
    if ($definition === null) {
        return [];
    }

    $sql = sprintf(
        'SELECT %s AS option_id, %s AS option_label FROM %s WHERE is_active = 1 ORDER BY %s',
        $definition['value'],
        $definition['label'],
        $definition['table'],
        $definition['order']
    );

    return $pdo->query($sql)->fetchAll();
}

function settings_dropdown_payload(PDO $pdo): array
{
    $payload = [];

    foreach (settings_dropdown_definitions() as $key => $definition) {
        $options = settings_dropdown_options($pdo, $key);
        $labels = array_map(
            static fn (array $option): string => (string) ($option['option_label'] ?? ''),
            $options
        );
        $preview = implode(', ', array_slice($labels, 0, 3));

        if (count($labels) > 3) {
            $preview .= ', ...';
        }

        $payload[] = [
            'key' => $key,
            'name' => $definition['title'],
            'count' => count($options),
            'preview' => $preview,
            'options' => $options,
        ];
    }

    return $payload;
}

function settings_normalize_dropdown_label(string $value): string
{
    $value = preg_replace('/\s+/', ' ', trim($value));
    return $value === null ? '' : $value;
}

function settings_dropdown_option_exists(PDO $pdo, array $definition, string $label): bool
{
    $statement = $pdo->prepare(
        sprintf(
            'SELECT COUNT(*) FROM %s WHERE %s = :label LIMIT 1',
            $definition['table'],
            $definition['label']
        )
    );
    $statement->execute(['label' => $label]);

    return (int) $statement->fetchColumn() > 0;
}

function settings_generate_unique_code(PDO $pdo, string $table, string $column, string $label, string $fallback = 'OPT'): string
{
    $base = strtoupper((string) preg_replace('/[^A-Za-z0-9]+/', '', $label));
    $base = substr($base !== '' ? $base : $fallback, 0, 20);

    $statement = $pdo->prepare(
        sprintf('SELECT COUNT(*) FROM %s WHERE %s = :code LIMIT 1', $table, $column)
    );

    $candidate = $base;
    $counter = 2;

    while (true) {
        $statement->execute(['code' => $candidate]);

        if ((int) $statement->fetchColumn() === 0) {
            return $candidate;
        }

        $suffix = (string) $counter;
        $candidate = substr($base, 0, max(1, 20 - strlen($suffix))) . $suffix;
        $counter += 1;
    }
}

function settings_next_sort_order(PDO $pdo, string $table, string $column): int
{
    $statement = $pdo->query(sprintf('SELECT COALESCE(MAX(%s), 0) FROM %s', $column, $table));
    return ((int) $statement->fetchColumn()) + 1;
}

function settings_find_dropdown_option(PDO $pdo, array $definition, int $optionId): ?array
{
    $statement = $pdo->prepare(
        sprintf(
            'SELECT %s AS option_id, %s AS option_label FROM %s WHERE %s = :option_id LIMIT 1',
            $definition['value'],
            $definition['label'],
            $definition['table'],
            $definition['value']
        )
    );
    $statement->execute(['option_id' => $optionId]);
    $option = $statement->fetch();

    return $option ?: null;
}

function settings_count_dropdown_usage(PDO $pdo, array $definition, int $optionId): int
{
    $total = 0;

    foreach ($definition['references'] as $reference) {
        $statement = $pdo->prepare(
            sprintf('SELECT COUNT(*) FROM %s WHERE %s = :option_id', $reference['table'], $reference['column'])
        );
        $statement->execute(['option_id' => $optionId]);
        $total += (int) $statement->fetchColumn();
    }

    return $total;
}

function settings_add_dropdown_option(PDO $pdo, string $key, string $label): array
{
    $definition = settings_dropdown_definition($key);
    if ($definition === null) {
        return ['success' => false, 'message' => 'Invalid dropdown.'];
    }

    $label = settings_normalize_dropdown_label($label);
    if ($label === '') {
        return ['success' => false, 'message' => 'Option label is required.'];
    }

    if (settings_dropdown_option_exists($pdo, $definition, $label)) {
        return ['success' => false, 'message' => 'That option already exists.'];
    }

    $columns = [$definition['label'], 'description', 'is_active'];
    $placeholders = [':label', ':description', ':is_active'];
    $params = [
        'label' => $label,
        'description' => $definition['title'] . ' option',
        'is_active' => 1,
    ];

    if (!empty($definition['code_column'])) {
        $columns[] = $definition['code_column'];
        $placeholders[] = ':code';
        $params['code'] = settings_generate_unique_code(
            $pdo,
            $definition['table'],
            $definition['code_column'],
            $label,
            $key === 'company_id' ? 'COMPANY' : 'DEPT'
        );
    }

    if (!empty($definition['sort_column'])) {
        $columns[] = $definition['sort_column'];
        $placeholders[] = ':sort_order';
        $params['sort_order'] = settings_next_sort_order($pdo, $definition['table'], $definition['sort_column']);
    }

    $statement = $pdo->prepare(
        sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $definition['table'],
            implode(', ', $columns),
            implode(', ', $placeholders)
        )
    );
    $statement->execute($params);

    return [
        'success' => true,
        'message' => $definition['title'] . ' updated successfully.',
        'option_id' => (int) $pdo->lastInsertId(),
        'option_label' => $label,
    ];
}

function settings_delete_dropdown_option(PDO $pdo, string $key, int $optionId): array
{
    $definition = settings_dropdown_definition($key);
    if ($definition === null) {
        return ['success' => false, 'message' => 'Invalid dropdown.'];
    }

    if ($optionId <= 0) {
        return ['success' => false, 'message' => 'A valid option is required.'];
    }

    $option = settings_find_dropdown_option($pdo, $definition, $optionId);
    if ($option === null) {
        return ['success' => false, 'message' => 'Option not found.'];
    }

    $usageCount = settings_count_dropdown_usage($pdo, $definition, $optionId);
    if ($usageCount > 0) {
        return [
            'success' => false,
            'message' => 'This option is already in use and cannot be deleted.',
        ];
    }

    $statement = $pdo->prepare(
        sprintf('DELETE FROM %s WHERE %s = :option_id', $definition['table'], $definition['value'])
    );
    $statement->execute(['option_id' => $optionId]);

    return [
        'success' => true,
        'message' => $definition['title'] . ' updated successfully.',
        'option_id' => $optionId,
    ];
}
