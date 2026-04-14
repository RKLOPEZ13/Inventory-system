<?php
$page_title = 'History Log';
$current_page = 'history';
$role = $_GET['role'] ?? 'user';
$extra_js = 'history.js';

require_once '../includes/history_helpers.php';

$historyPageData = history_page_payload($pdo);
$historySummary = $historyPageData['summary'];

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content history-page">
        <div class="page-header history-page-header">
            <div>
                <h1 class="page-heading">History Log</h1>
                <p class="page-desc">Live deployment transactions pulled from the real deployment log table.</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" id="historyExportBtn" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export Log
                </button>
            </div>
        </div>

        <div class="history-summary-grid">
            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <path d="M9 3h6v4H9z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value"><?= (int) $historySummary['total_logs'] ?></div>
                <div class="stat-card-label">Total Logs</div>
            </div>

            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value"><?= (int) $historySummary['deployed_logs'] ?></div>
                <div class="stat-card-label">Deployed</div>
            </div>

            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-cyan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 14l-4-4 4-4"/><path d="M5 10h11a4 4 0 0 1 0 8h-1"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value"><?= (int) $historySummary['returned_logs'] ?></div>
                <div class="stat-card-label">Returned</div>
            </div>

            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-violet">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h6"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value"><?= (int) $historySummary['unique_items'] ?></div>
                <div class="stat-card-label">Unique Items</div>
            </div>
        </div>

        <div class="inventory-table-controls history-table-controls">
            <div class="inventory-toolbar-row inventory-toolbar-top">
                <div class="inventory-search-wrap">
                    <label class="label sr-only" for="historySearchInput">Search deployment logs</label>
                    <div class="inventory-search-field">
                        <span class="inventory-search-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="search" class="input inventory-search-input" id="historySearchInput" placeholder="Search inventory no., deployed to, deployed by, serial number, or model">
                    </div>
                </div>
            </div>

            <div class="inventory-toolbar-row inventory-filter-row">
                <div class="inventory-inline-control">
                    <label class="label sr-only" for="historyEntriesSelect">Entries</label>
                    <select class="select" id="historyEntriesSelect">
                        <option value="10">10 entries</option>
                        <option value="20">20 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>

                <label class="label sr-only" for="historyCompanyFilter">Company</label>
                <select class="select history-filter-select inventory-filter-pill" id="historyCompanyFilter" data-filter-column="company_id">
                    <option value="">All Company</option>
                    <?php foreach ($historyPageData['filters']['company_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="label sr-only" for="historyCategoryFilter">Category</label>
                <select class="select history-filter-select inventory-filter-pill" id="historyCategoryFilter" data-filter-column="category_id">
                    <option value="">All Category</option>
                    <?php foreach ($historyPageData['filters']['category_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="label sr-only" for="historyDepartmentFilter">Department</label>
                <select class="select history-filter-select inventory-filter-pill" id="historyDepartmentFilter" data-filter-column="department_id">
                    <option value="">All Department</option>
                    <?php foreach ($historyPageData['filters']['department_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="label sr-only" for="historyDeploymentStatusFilter">Deployment Status</label>
                <select class="select history-filter-select inventory-filter-pill" id="historyDeploymentStatusFilter" data-filter-column="deployment_status_id">
                    <option value="">All Deployment Status</option>
                    <?php foreach ($historyPageData['filters']['deployment_status_id'] as $option): ?>
                        <option value="<?= htmlspecialchars((string) $option['option_value']) ?>"><?= htmlspecialchars($option['option_label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="inventory-filter-row-end">
                    <button class="btn btn-primary" id="historyResetBtn" type="button">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10"/><path d="M3.51 15A9 9 0 0 0 18.36 18.36L23 14"/>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="table-wrap history-table-wrap">
            <div class="inventory-table-scroll">
                <table class="data-table history-table" id="historyTable">
                    <thead id="historyTableHead"></thead>
                    <tbody id="historyTableBody"></tbody>
                </table>
            </div>
            <div class="pagination inventory-pagination">
                <span id="historyTableSummary">Showing 0 to 0 of 0 logs</span>
                <div class="page-btns" id="historyPagination"></div>
            </div>
        </div>
    </div>
</div>

<script>
window.historyPageData = <?= json_encode([
    'logs' => $historyPageData['logs'],
    'filters' => $historyPageData['filters'],
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<?php include '../includes/footer.php'; ?>
