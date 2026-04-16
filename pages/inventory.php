<?php
$page_title = 'Inventory';
$current_page = 'inventory';
$role = $_GET['role'] ?? 'user';
$canEditInventory = $role === 'user';
$extra_js = 'inventory.js';

require_once '../includes/inventory_helpers.php';

$inventoryPageData = inventory_page_payload($pdo);
$inventoryCount = count($inventoryPageData['items']);
$inventorySummaries = $inventoryPageData['summaries'];

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
              
              <button class="btn btn-primary" id="inventoryAddBtn" type="button" <?= $canEditInventory ? '' : 'disabled' ?>>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                  Add Item
              </button>
            </div>
        </div>

        <div class="inventory-summary-grid" aria-label="Inventory summaries">
            <div class="stat-card inventory-summary-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20"/><path d="M2 12h20"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="inventorySummaryDeploymentStatusCount"><?= (int) $inventorySummaries['deployment_status_id']['top_count'] ?></div>
                <div class="stat-card-label"><?= htmlspecialchars($inventorySummaries['deployment_status_id']['title']) ?></div>
                <div class="inventory-summary-note" id="inventorySummaryDeploymentStatusNote">
                    Top: <?= htmlspecialchars($inventorySummaries['deployment_status_id']['top_label']) ?> | <?= (int) $inventorySummaries['deployment_status_id']['distinct_count'] ?> <?= htmlspecialchars($inventorySummaries['deployment_status_id']['distinct_label']) ?>
                </div>
            </div>

            <div class="stat-card inventory-summary-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 10h.01"/><path d="M9 14h.01"/><path d="M15 10h.01"/><path d="M15 14h.01"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="inventorySummaryCompanyCount"><?= (int) $inventorySummaries['company_id']['top_count'] ?></div>
                <div class="stat-card-label"><?= htmlspecialchars($inventorySummaries['company_id']['title']) ?></div>
                <div class="inventory-summary-note" id="inventorySummaryCompanyNote">
                    Top: <?= htmlspecialchars($inventorySummaries['company_id']['top_label']) ?> | <?= (int) $inventorySummaries['company_id']['distinct_count'] ?> <?= htmlspecialchars($inventorySummaries['company_id']['distinct_label']) ?>
                </div>
            </div>

            <div class="stat-card inventory-summary-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-cyan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="inventorySummaryCategoryCount"><?= (int) $inventorySummaries['category_id']['top_count'] ?></div>
                <div class="stat-card-label"><?= htmlspecialchars($inventorySummaries['category_id']['title']) ?></div>
                <div class="inventory-summary-note" id="inventorySummaryCategoryNote">
                    Top: <?= htmlspecialchars($inventorySummaries['category_id']['top_label']) ?> | <?= (int) $inventorySummaries['category_id']['distinct_count'] ?> <?= htmlspecialchars($inventorySummaries['category_id']['distinct_label']) ?>
                </div>
            </div>

            <div class="stat-card inventory-summary-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-violet">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="inventorySummaryAgeStatusCount"><?= (int) $inventorySummaries['age_status_id']['top_count'] ?></div>
                <div class="stat-card-label"><?= htmlspecialchars($inventorySummaries['age_status_id']['title']) ?></div>
                <div class="inventory-summary-note" id="inventorySummaryAgeStatusNote">
                    Top: <?= htmlspecialchars($inventorySummaries['age_status_id']['top_label']) ?> | <?= (int) $inventorySummaries['age_status_id']['distinct_count'] ?> <?= htmlspecialchars($inventorySummaries['age_status_id']['distinct_label']) ?>
                </div>
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

                <label class="label sr-only" for="deploymentStatusFilter">Deployment Status</label>
                <select class="select inventory-filter-select inventory-filter-pill" id="deploymentStatusFilter" data-filter-column="deployment_status_id">
                    <option value="">All Deployment Status</option>
                    <?php foreach ($inventoryPageData['filters']['deployment_status_id'] as $option): ?>
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

<div class="modal-overlay" id="inventoryActionModal">
    <div class="modal inventory-deploy-modal inventory-workflow-modal">
        <div class="modal-header inventory-deploy-modal-header">
            <div>
                <span class="inventory-modal-kicker">Deployment Action</span>
                <h3 class="modal-title" id="inventoryActionModalTitle">Deploy Item</h3>
                <p class="modal-subtext inventory-item-modal-subtext" id="inventoryActionModalSubtext">Choose the action type, then fill out the required details.</p>
            </div>
        </div>

        <form id="inventoryActionForm" class="inventory-deploy-form">
            <input type="hidden" name="inventory_id" id="inventoryActionInventoryId">
            <input type="hidden" name="action_type" id="inventoryActionType">

            <div class="inventory-deploy-selected" id="inventoryActionItemLabel">No item selected.</div>

            <div class="form-col inventory-action-choice-shell">
                <label class="label">Action Type</label>
                <div class="inventory-action-choice-grid" id="inventoryActionChoiceButtons"></div>
            </div>

            <div class="inventory-action-panel" id="inventoryActionFields">
                <div class="form-row inventory-deploy-grid">
                    <div class="field-note form-full" id="inventoryActionDateNote">The action date is recorded automatically.</div>

                    <div class="form-col" id="inventoryActionDeployedToWrap">
                        <label class="label" for="inventoryActionDeployedTo">Deployed To</label>
                        <input class="input" id="inventoryActionDeployedTo" name="deployed_to" type="text">
                    </div>

                    <div class="form-col" id="inventoryActionDepartmentWrap">
                        <label class="label" for="inventoryActionDepartment">Department</label>
                        <select class="select" id="inventoryActionDepartment" name="department_id"></select>
                    </div>

                    <div class="form-col" id="inventoryActionInventoryStatusWrap">
                        <label class="label" for="inventoryActionInventoryStatus">Inventory Status</label>
                        <select class="select" id="inventoryActionInventoryStatus" name="inventory_status_id"></select>
                    </div>

                    <div class="form-col form-full" id="inventoryActionIssueWrap">
                        <label class="label" for="inventoryActionIssueDescription">Issue Description</label>
                        <textarea class="textarea" id="inventoryActionIssueDescription" name="issue_description" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" onclick="closeModal('inventoryActionModal')">Cancel</button>
                <button class="btn btn-primary" type="submit" id="inventoryActionSubmitBtn">Save Action</button>
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
    'summaries' => $inventoryPageData['summaries'],
    'formColumns' => $inventoryPageData['form_columns'],
    'nextInventoryNo' => $inventoryPageData['next_inventory_no'],
    'currentUserName' => inventory_default_actor_name($pdo),
    'config' => [
        'role' => $role,
        'canEdit' => $canEditInventory,
        'saveEndpoint' => '../api/inventory/save.php',
        'deleteEndpoint' => '../api/inventory/delete.php',
        'deployEndpoint' => '../api/inventory/deploy.php',
    ],
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<?php include '../includes/footer.php'; ?>
