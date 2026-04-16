<?php
$page_title = 'Deployment';
$current_page = 'deployment';
$role = $_GET['role'] ?? 'user';
$extra_js = 'deployment.js';

$deploymentItems = [
    ['inventory_no' => 'NCIA-2041', 'item_name' => 'Dell Latitude 5440', 'category' => 'Laptop',  'company' => 'NCIA Main',  'department' => 'Operations',    'serial_number' => 'DL5440-1201', 'model' => 'Latitude 5440',        'deploy_to' => 'Field Audit Team',  'priority' => 'High',   'ready_window' => 'Today',     'deployment_status' => 'Deployed'],
    ['inventory_no' => 'NCIA-2042', 'item_name' => 'Lenovo ThinkPad E14', 'category' => 'Laptop',  'company' => 'NCIA Main',  'department' => 'Finance',       'serial_number' => 'LE14-8821',    'model' => 'ThinkPad E14',        'deploy_to' => 'Payroll Team',     'priority' => 'Medium', 'ready_window' => 'This Week', 'deployment_status' => 'Temporary'],
    ['inventory_no' => 'NCIA-2043', 'item_name' => 'HP Pro Mini 400',     'category' => 'Desktop', 'company' => 'NCIA South', 'department' => 'Admissions',    'serial_number' => 'HPM400-4470',  'model' => 'Pro Mini 400',        'deploy_to' => 'Admissions Desk',  'priority' => 'High',   'ready_window' => 'Today',     'deployment_status' => 'Deployed'],
    ['inventory_no' => 'NCIA-2044', 'item_name' => 'Epson EcoTank L5290', 'category' => 'Printer', 'company' => 'NCIA Main',  'department' => 'Procurement',   'serial_number' => 'EP5290-1205',  'model' => 'EcoTank L5290',       'deploy_to' => 'Procurement',      'priority' => 'Low',    'ready_window' => 'Standby',   'deployment_status' => 'Borrowed'],
    ['inventory_no' => 'NCIA-2045', 'item_name' => 'Samsung Galaxy Tab S9','category' => 'Tablet', 'company' => 'NCIA North', 'department' => 'Extension',     'serial_number' => 'SGS9-7720',    'model' => 'Galaxy Tab S9',       'deploy_to' => 'Extension Team',   'priority' => 'High',   'ready_window' => 'Today',     'deployment_status' => 'Transfer'],
    ['inventory_no' => 'NCIA-2046', 'item_name' => 'Acer Veriton N2590',  'category' => 'Desktop', 'company' => 'NCIA Main',  'department' => 'Records',       'serial_number' => 'AVN2590-9012', 'model' => 'Veriton N2590',       'deploy_to' => 'Records Unit',     'priority' => 'Medium', 'ready_window' => 'This Week', 'deployment_status' => 'Returned'],
    ['inventory_no' => 'NCIA-2047', 'item_name' => 'MacBook Air M2',      'category' => 'Laptop',  'company' => 'NCIA Main',  'department' => 'Communications','serial_number' => 'MBA2-3201',    'model' => 'MacBook Air M2',      'deploy_to' => 'Public Affairs',   'priority' => 'Low',    'ready_window' => 'This Week', 'deployment_status' => 'Temporary'],
    ['inventory_no' => 'NCIA-2048', 'item_name' => 'Canon EOS Webcam Kit','category' => 'Camera',  'company' => 'NCIA Main',  'department' => 'Public Affairs','serial_number' => 'CEWK-1284',    'model' => 'EOS Webcam Kit',      'deploy_to' => 'Events Team',      'priority' => 'Medium', 'ready_window' => 'Standby',   'deployment_status' => 'Returned with issue/s'],
    ['inventory_no' => 'NCIA-2049', 'item_name' => 'Lenovo ThinkCentre Neo 50a', 'category' => 'Desktop', 'company' => 'NCIA East', 'department' => 'Training', 'serial_number' => 'LTC50A-7610', 'model' => 'ThinkCentre Neo 50a', 'deploy_to' => 'Training Room B',  'priority' => 'High',   'ready_window' => 'Today',     'deployment_status' => 'Deployed'],
    ['inventory_no' => 'NCIA-2050', 'item_name' => 'Honeywell ScanPal EDA52', 'category' => 'Scanner', 'company' => 'NCIA Main', 'department' => 'Warehouse', 'serial_number' => 'HSEDA52-4811', 'model' => 'ScanPal EDA52',      'deploy_to' => 'Supply Monitoring','priority' => 'Low',    'ready_window' => 'Standby',   'deployment_status' => 'Borrowed'],
    ['inventory_no' => 'NCIA-2051', 'item_name' => 'Dell OptiPlex 7010',  'category' => 'Desktop', 'company' => 'NCIA Main',  'department' => 'Legal',         'serial_number' => 'DO7010-2209',  'model' => 'OptiPlex 7010',       'deploy_to' => 'Legal Unit',       'priority' => 'Medium', 'ready_window' => 'This Week', 'deployment_status' => 'Returned'],
    ['inventory_no' => 'NCIA-2052', 'item_name' => 'ASUS ExpertBook B1',  'category' => 'Laptop',  'company' => 'NCIA Main',  'department' => 'MIS',           'serial_number' => 'AEB1-5540',    'model' => 'ExpertBook B1',       'deploy_to' => 'Intern Pool',      'priority' => 'High',   'ready_window' => 'Today',     'deployment_status' => 'Transfer'],
];

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content inventory-page">
        <div class="page-header inventory-page-header">
            <div>
                <h1 class="page-heading">Deployment</h1>
                <p class="page-desc">Preview available inventory items ready for deployment.</p>
            </div>
            <div class="header-actions inventory-header-actions">
                <span class="inventory-total-pill" id="deploymentTotalPill">0 preview items</span>
            </div>
        </div>

        <div class="history-summary-grid">
            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="deploymentAvailableCount">0</div>
                <div class="stat-card-label">Preview Items</div>
            </div>

            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"/><path d="M13 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="deploymentReadyTodayCount">0</div>
                <div class="stat-card-label">Ready Today</div>
            </div>

            <div class="stat-card history-stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon history-stat-icon history-stat-cyan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" id="deploymentHighPriorityCount">0</div>
                <div class="stat-card-label">High Priority</div>
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
                <div class="stat-card-value" id="deploymentCategoryCount">0</div>
                <div class="stat-card-label">Categories</div>
            </div>
        </div>

        <div class="inventory-table-controls">
            <div class="inventory-toolbar-row inventory-toolbar-top">
                <div class="inventory-search-wrap">
                    <label class="label sr-only" for="deploymentSearchInput">Search deployment items</label>
                    <div class="inventory-search-field">
                        <span class="inventory-search-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="search" class="input inventory-search-input" id="deploymentSearchInput" placeholder="Search available deployment items">
                    </div>
                </div>
            </div>

            <div class="inventory-toolbar-row inventory-filter-row">
                <div class="inventory-inline-control">
                    <label class="label sr-only" for="deploymentEntriesSelect">Entries</label>
                    <select class="select" id="deploymentEntriesSelect">
                        <option value="10">10 entries</option>
                        <option value="20">20 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>

                <label class="label sr-only" for="deploymentCategoryFilter">Category</label>
                <select class="select inventory-filter-pill" id="deploymentCategoryFilter">
                    <option value="">All Category</option>
                </select>

                <label class="label sr-only" for="deploymentCompanyFilter">Company</label>
                <select class="select inventory-filter-pill" id="deploymentCompanyFilter">
                    <option value="">All Company</option>
                </select>

                <label class="label sr-only" for="deploymentDepartmentFilter">Department</label>
                <select class="select inventory-filter-pill" id="deploymentDepartmentFilter">
                    <option value="">All Department</option>
                </select>

                <div class="inventory-filter-row-end">
                    <button class="btn btn-primary" id="deploymentResetBtn" type="button">
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
                <table class="data-table inventory-table" id="deploymentTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="inventory-column-name">Inventory No.</th>
                            <th class="inventory-column-name">Item Name</th>
                            <th class="inventory-column-name">Category</th>
                            <th class="inventory-column-name">Company</th>
                            <th class="inventory-column-name">Department</th>
                            <th class="inventory-column-name">Serial Number</th>
                            <th class="inventory-column-name">Model</th>
                            <th class="inventory-column-name">Deploy To</th>
                            <th class="inventory-column-name">Priority</th>
                            <th class="inventory-column-name">Deployment Status</th>
                            <th class="inventory-column-name">Action</th>
                        </tr>
                    </thead>
                    <tbody id="deploymentTableBody"></tbody>
                </table>
            </div>
            <div class="pagination inventory-pagination">
                <span id="deploymentTableSummary">Showing 0 to 0 of 0 items</span>
                <div class="page-btns" id="deploymentPagination"></div>
            </div>
        </div>
    </div>
</div>

<script>
window.deploymentPageData = <?= json_encode([
    'items' => $deploymentItems,
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<?php include '../includes/footer.php'; ?>
