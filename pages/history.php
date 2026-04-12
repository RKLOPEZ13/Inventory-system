<?php
$page_title = 'History Log';
$current_page = 'history';
include '../includes/header.php';
include '../includes/sidebar.php';
$role = $_GET['role'] ?? 'user';

$logs = [
    ['LOG-0048','IT-003','iPhone 15 Pro',        'Deployed', 'Juan Cruz',      'Sales',   '2026-04-07 09:14', 'Maria A. (Admin)', 'For field work'],
    ['LOG-0047','FN-001','Office Chair Ergo',     'Deployed', 'Ana Reyes',      'HR',      '2026-04-06 14:30', 'Maria A. (Admin)', 'New hire setup'],
    ['LOG-0046','IT-002','MacBook Air M3',        'Deployed', 'Carlo Diaz',     'Marketing','2026-04-06 10:05','Maria A. (Admin)', 'Replacement unit'],
    ['LOG-0045','IT-001','Laptop Dell XPS 15',   'Returned', 'Ben Garcia',     'IT',      '2026-04-05 16:45', 'Maria A. (Admin)', 'Project ended'],
    ['LOG-0044','AV-001','Projector Epson X',    'Deployed', 'Training Room',  'Ops',     '2026-04-05 08:00', 'Jose B. (Admin)', 'Weekly training'],
    ['LOG-0043','IT-005','iPad Pro 12.9',        'Deployed', 'Liza Ramos',     'Legal',   '2026-04-04 11:20', 'Jose B. (Admin)', 'Client meetings'],
    ['LOG-0042','IT-004','HP LaserJet Pro',      'Maintenance','Finance Dept', 'Finance', '2026-04-03 13:00', 'Jose B. (Admin)', 'Paper jam repair'],
    ['LOG-0041','VH-001','Toyota Innova 2023',   'Deployed', 'Exec Driver',    'Exec',    '2026-04-02 07:30', 'Maria A. (Admin)', 'Monthly assignment'],
    ['LOG-0040','IT-006','Samsung 27" Monitor',  'Returned', 'Ed Santos',      'IT',      '2026-04-01 17:00', 'Maria A. (Admin)', 'Upgraded to 32"'],
    ['LOG-0039','FN-003','Desk Lamp LED',        'Returned', 'Ben Garcia',     'IT',      '2026-03-31 16:00', 'Jose B. (Admin)', 'WFH setup dismantled'],
    ['LOG-0038','IT-007','Cisco IP Phone',       'Deployed', 'Cora Lim',       'Finance', '2026-03-30 10:10', 'Maria A. (Admin)', 'Seat transfer'],
    ['LOG-0037','IT-001','Laptop Dell XPS 15',   'Deployed', 'Ben Garcia',     'IT',      '2026-03-28 09:00', 'Jose B. (Admin)', 'Dev project'],
];

$action_colors = [
    'Deployed'    => 'deployed',
    'Returned'    => 'returned',
    'Maintenance' => 'warning',
    'Written Off' => 'danger',
    'Added'       => 'active',
];
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">History Log</h1>
                <p class="page-desc">Full audit trail of all asset transactions</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="showToast('Exporting log...','info')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export Log
                </button>
                <?php if ($role === 'user'): ?>
                <button class="btn btn-primary" onclick="openModal('logModal')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Log Transaction
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary cards -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
            <?php
            $summaries = [
                ['Total Logs',    count($logs), '#1e40af', 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2'],
                ['Deployments',   7,            '#0284c7', 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'],
                ['Returns',       3,            '#16a34a', 'M9 14l-4-4 4-4M5 10h11a4 4 0 0 1 0 8h-1'],
                ['Maintenance',   1,            '#d97706', 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z'],
            ];
            foreach ($summaries as $s): ?>
            <div class="stat-card" style="padding:16px">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:<?= $s[2] ?>18;color:<?= $s[2] ?>;width:36px;height:36px">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="<?= $s[3] ?>"/></svg>
                    </div>
                </div>
                <div class="stat-card-value" style="font-size:1.5rem"><?= $s[1] ?></div>
                <div class="stat-card-label"><?= $s[0] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Filters -->
        <div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap;align-items:center">
            <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem">
                <option>All Actions</option>
                <option>Deployed</option><option>Returned</option><option>Maintenance</option><option>Written Off</option>
            </select>
            <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem">
                <option>All Departments</option>
                <option>IT</option><option>HR</option><option>Finance</option><option>Sales</option>
                <option>Marketing</option><option>Legal</option><option>Ops</option><option>Exec</option>
            </select>
            <input type="date" class="input" style="width:auto;padding:8px 12px;font-size:.85rem" value="<?= date('Y-m-d') ?>">
            <span style="font-size:.8rem;color:var(--text-muted)">to</span>
            <input type="date" class="input" style="width:auto;padding:8px 12px;font-size:.85rem">
            <button class="btn btn-secondary btn-sm">Apply</button>
        </div>

        <!-- Log Table -->
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Asset ID</th>
                        <th>Item</th>
                        <th>Action</th>
                        <th>Assigned To</th>
                        <th>Department</th>
                        <th>Date & Time</th>
                        <th>Processed By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="historyBody">
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted)"><?= $log[0] ?></td>
                        <td style="font-family:monospace;font-size:.78rem;color:var(--primary)"><?= $log[1] ?></td>
                        <td style="font-weight:600"><?= htmlspecialchars($log[2]) ?></td>
                        <td><span class="badge badge-<?= $action_colors[$log[3]] ?? 'returned' ?>"><?= $log[3] ?></span></td>
                        <td><?= htmlspecialchars($log[4]) ?></td>
                        <td style="font-size:.82rem"><span style="background:var(--bg);padding:3px 8px;border-radius:4px;color:var(--text-secondary)"><?= $log[5] ?></span></td>
                        <td style="font-size:.8rem;color:var(--text-muted);white-space:nowrap"><?= $log[6] ?></td>
                        <td style="font-size:.8rem;color:var(--text-secondary)"><?= htmlspecialchars($log[7]) ?></td>
                        <td style="font-size:.82rem;color:var(--text-muted);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($log[8]) ?>"><?= htmlspecialchars($log[8]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span>Showing 1–<?= count($logs) ?> of <?= count($logs) ?> entries</span>
                <div class="page-btns">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Transaction Modal -->
<?php if ($role === 'user'): ?>
<div class="modal-overlay" id="logModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Log Transaction</h3>
            <button class="modal-close-btn" onclick="closeModal('logModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="form-row">
            <div class="form-col">
                <label class="label">Asset ID</label>
                <input type="text" class="input" placeholder="e.g. IT-001">
            </div>
            <div class="form-col">
                <label class="label">Action</label>
                <select class="select">
                    <option>Deployed</option><option>Returned</option><option>Maintenance</option><option>Written Off</option>
                </select>
            </div>
            <div class="form-col">
                <label class="label">Assigned To</label>
                <input type="text" class="input" placeholder="Employee name or room">
            </div>
            <div class="form-col">
                <label class="label">Department</label>
                <select class="select">
                    <option>IT</option><option>HR</option><option>Finance</option><option>Sales</option>
                    <option>Marketing</option><option>Legal</option><option>Ops</option><option>Exec</option>
                </select>
            </div>
            <div class="form-col form-full">
                <label class="label">Date &amp; Time</label>
                <input type="datetime-local" class="input" value="<?= date('Y-m-d\TH:i') ?>">
            </div>
            <div class="form-col form-full">
                <label class="label">Remarks</label>
                <textarea class="textarea" placeholder="Reason or additional notes..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('logModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveLog()">Save Log</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function saveLog() {
    closeModal('logModal');
    showToast('Transaction logged successfully', 'success');
}
</script>

<?php include '../includes/footer.php'; ?>
