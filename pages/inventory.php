<?php
$page_title = 'Inventory';
$current_page = 'inventory';
$role = $_GET['role'] ?? 'user';
$canEditInventory = $role === 'user';
$extra_js = 'inventory.js';

require_once '../includes/inventory_helpers.php';

$inventoryPageData = inventory_page_payload($pdo);
$inventoryCount = count($inventoryPageData['items']);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content inventory-page">
        <div class="page-header inventory-page-header">
            <div>
                <h1 class="page-heading">Inventory</h1>
                <p class="page-desc">Live inventory records</p>
            </div>
            <div class="header-actions inventory-header-actions">
              <span class="inventory-total-pill"><?= $inventoryCount ?> item<?= $inventoryCount === 1 ? '' : 's' ?></span>
              
              <button class="btn btn-secondary" id="inventoryExportBtn" type="button">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                  </svg>
                  Export
              </button>
              
                <button class="btn btn-success btn-deploy" id="inventoryDeployBtn" type="button" <?= $canEditInventory ? '' : 'disabled' ?>>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>
                    </svg>
                    Deploy Item
                </button>

              <button class="btn btn-primary" id="inventoryAddBtn" type="button" <?= $canEditInventory ? '' : 'disabled' ?>>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                  Add Item
              </button>
            </div>
        </div>

        <div class="inventory-table-controls">
            <div class="inventory-toolbar-row inventory-toolbar-top">
                <div class="inventory-search-wrap">
                    <label class="label sr-only" for="inventorySearchInput">Search inventory</label>
                    <div class="inventory-search-field">
                        <span class="inventory-search-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="search" class="input inventory-search-input" id="inventorySearchInput" placeholder="Search inventory records">
                    </div>
                </div>
            </div>

            <div class="inventory-toolbar-row inventory-filter-row">
                <div class="inventory-inline-control">
                    <label class="label sr-only" for="inventoryEntriesSelect">Entries</label>
                    <select class="select" id="inventoryEntriesSelect">
                        <option value="10">10 entries</option>
                        <option value="20">20 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>

                <label class="label sr-only" for="categoryFilter">Category</label>
                <select class="select inventory-filter-select inventory-filter-pill" id="categoryFilter" data-filter-column="category_id">
                    <option value="">All Category</option>
                    <?php foreach ($inventoryPageData['filters']['category_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="label sr-only" for="subCategoryFilter">Sub Category</label>
                <select class="select inventory-filter-select inventory-filter-pill" id="subCategoryFilter" data-filter-column="sub_category_id">
                    <option value="">All Sub Category</option>
                    <?php foreach ($inventoryPageData['filters']['sub_category_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="label sr-only" for="inventoryStatusFilter">Inventory Status</label>
                <select class="select inventory-filter-select inventory-filter-pill" id="inventoryStatusFilter" data-filter-column="inventory_status_id">
                    <option value="">All Inventory Status</option>
                    <?php foreach ($inventoryPageData['filters']['inventory_status_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="inventory-filter-row-end">
                    <div class="inventory-column-filter">
                        <button class="btn btn-secondary" id="inventoryColumnToggle" type="button" aria-expanded="false" aria-controls="inventoryColumnPanel">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/>
                            </svg>
                            Columns
                        </button>
                        <div class="inventory-column-panel" id="inventoryColumnPanel">
                            <div class="inventory-column-panel-head">
                                <strong>Visible Columns</strong>
                                <span>Uncheck to hide</span>
                            </div>
                            <div class="inventory-column-options" id="inventoryColumnOptions"></div>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="inventoryResetBtn" type="button">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10"/><path d="M3.51 15A9 9 0 0 0 18.36 18.36L23 14"/>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="table-wrap inventory-table-wrap">
            <div class="inventory-table-scroll">
                <table class="data-table inventory-table" id="inventoryTable">
                    <thead id="inventoryTableHead"></thead>
                    <tbody id="inventoryTableBody"></tbody>
                </table>
            </div>
            <div class="pagination inventory-pagination">
                <span id="inventoryTableSummary">Showing 0 to 0 of 0 items</span>
                <div class="page-btns" id="inventoryPagination"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="inventoryItemModal">
    <div class="modal modal-wide inventory-item-modal">
        <div class="modal-header inventory-item-modal-header">
            <div>
                <span class="inventory-modal-kicker">Inventory Form</span>
                <h3 class="modal-title" id="inventoryItemModalTitle">Add Item</h3>
                <p class="modal-subtext inventory-item-modal-subtext" id="inventoryItemModalSubtext">Cleanly fill out the inventory details before saving this record.</p>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('inventoryItemModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form id="inventoryItemForm" class="inventory-item-form">
            <input type="hidden" name="inventory_id" id="inventoryItemId">
            <div class="inventory-item-form-shell">
                <div class="form-row inventory-form-grid" id="inventoryItemFields"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" onclick="closeModal('inventoryItemModal')">Cancel</button>
                <button class="btn btn-primary" type="submit" id="inventoryItemSubmitBtn">Save Item</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="inventoryDeployModal">
    <div class="modal modal-wide inventory-deploy-modal">
        <div class="modal-header inventory-deploy-modal-header">
            <div>
                <span class="inventory-modal-kicker">Deployment</span>
                <h3 class="modal-title">Deploy Item</h3>
                <p class="modal-subtext">Search and select one inventory item, then complete the deployment details.</p>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('inventoryDeployModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form id="inventoryDeployForm" class="inventory-deploy-form">
            <input type="hidden" id="deployInventoryId" name="inventory_id" required>

            <div class="inventory-deploy-search-shell" id="deploySearchShell">
                <label class="label" for="deployInventorySearch">Search Inventory Item</label>
                <div class="inventory-search-field inventory-deploy-search-field">
                    <span class="inventory-search-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input
                        type="search"
                        class="input inventory-search-input"
                        id="deployInventorySearch"
                        autocomplete="off"
                        placeholder="Search by inventory no., model, serial number, company, or brand"
                    >
                </div>
                <div class="field-note">The list below updates as you type. Click a row to choose the item to deploy.</div>

                <div class="inventory-deploy-picker" id="deployInventoryPicker">
                    <div class="inventory-deploy-picker-scroll">
                        <table class="data-table inventory-deploy-results">
                            <thead>
                                <tr>
                                    <th>INVENTORY NO.</th>
                                    <th>COMPANY</th>
                                    <th>CATEGORY</th>
                                    <th>SUB CATEGORY</th>
                                    <th>BRAND</th>
                                    <th>MODEL</th>
                                    <th>SERIAL NUMBER</th>
                                    <th>DEVICE AGE</th>
                                    <th>AGE STATUS</th>
                                    <th>INVENTORY STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="deployInventoryResults"></tbody>
                        </table>
                    </div>
                </div>

                <div class="inventory-deploy-selected" id="deploySelectedItem">No inventory item selected yet.</div>
            </div>

            <div class="inventory-item-form-shell inventory-deploy-form-shell">
                <div class="form-row inventory-deploy-grid">
                    <div class="form-col">
                        <label class="label" for="deployCustodianId">Deploy To</label>
                        <select class="select" id="deployCustodianId" name="custodian_id" required></select>
                    </div>

                    <div class="form-col">
                        <label class="label" for="deployDepartmentId">Department</label>
                        <select class="select" id="deployDepartmentId" name="department_id" required></select>
                    </div>

                    <div class="form-col">
                        <label class="label" for="deployDeploymentStatusId">Deployment Status</label>
                        <select class="select" id="deployDeploymentStatusId" name="deployment_status_id" required></select>
                    </div>

                    <div class="form-col">
                        <label class="label" for="deployDate">Date Deployed</label>
                        <input class="input" id="deployDate" name="deployed_date" type="date" readonly>
                        <div class="field-note">Auto-set when this deployment is saved.</div>
                    </div>

                    <div class="form-col">
                        <label class="label" for="deployProcessedBy">Deployed By</label>
                        <input class="input" id="deployProcessedBy" type="text" readonly>
                        <div class="field-note">Currently using the default placeholder user until session data is connected.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" onclick="closeModal('inventoryDeployModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Deploy Item</button>
            </div>
        </form>
    </div>
</div>

<script>
window.inventoryPageData = <?= json_encode([
    'columns' => $inventoryPageData['columns'],
    'items' => $inventoryPageData['items'],
    'filters' => $inventoryPageData['filters'],
    'lookups' => $inventoryPageData['lookups'],
    'formColumns' => $inventoryPageData['form_columns'],
    'nextInventoryNo' => $inventoryPageData['next_inventory_no'],
    'currentUserName' => inventory_default_actor_name($pdo),
    'config' => [
        'role' => $role,
        'canEdit' => $canEditInventory,
        'saveEndpoint' => '../api/inventory/save.php',
        'deployEndpoint' => '../api/inventory/deploy.php',
        'deleteEndpoint' => '../api/inventory/delete.php',
    ],
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<?php include '../includes/footer.php'; ?>
