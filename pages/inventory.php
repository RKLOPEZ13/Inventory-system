<?php
$page_title = 'Inventory';
$current_page = 'inventory';
include '../includes/header.php';
include '../includes/sidebar.php';
$role = $_GET['role'] ?? 'user';

$items = [
    ['IT-001','Laptop Dell XPS 15','Computer','IT','Available','Good','2024-01-15','₱85,000'],
    ['IT-002','MacBook Air M3','Computer','Marketing','Deployed','Good','2024-02-20','₱95,000'],
    ['IT-003','iPhone 15 Pro','Mobile','Sales','Deployed','Good','2024-03-01','₱65,000'],
    ['IT-004','HP LaserJet Pro','Printer','Finance','Active','Fair','2023-11-10','₱25,000'],
    ['FN-001','Office Chair Ergo','Furniture','HR','Deployed','Good','2023-09-05','₱12,000'],
    ['FN-002','Standing Desk Pro','Furniture','IT','Available','New','2026-01-10','₱28,000'],
    ['AV-001','Projector Epson X','AV Equip','Ops','Active','Good','2023-07-20','₱45,000'],
    ['IT-005','iPad Pro 12.9','Tablet','Legal','Deployed','Good','2024-04-15','₱55,000'],
    ['IT-006','Samsung 27" Monitor','Monitor','IT','Available','Good','2024-05-01','₱18,000'],
    ['VH-001','Toyota Innova 2023','Vehicle','Exec','Deployed','Good','2023-12-01','₱1,250,000'],
    ['IT-007','Cisco IP Phone','Telecom','Finance','Active','Fair','2022-06-10','₱8,500'],
    ['FN-003','Desk Lamp LED','Furniture','IT','Returned','Good','2023-01-20','₱2,500'],
];
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Inventory</h1>
                <p class="page-desc">All company assets — <?= count($items) ?> items</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="exportCSV()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export
                </button>
                <?php if ($role === 'user'): ?>
                <button class="btn btn-primary" onclick="openModal('itemModal')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add Item
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filters -->
        <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
            <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem" onchange="filterTable()">
                <option value="">All Categories</option>
                <option>Computer</option><option>Mobile</option><option>Printer</option>
                <option>Furniture</option><option>AV Equip</option><option>Tablet</option>
                <option>Vehicle</option><option>Telecom</option><option>Monitor</option>
            </select>
            <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem" onchange="filterTable()">
                <option value="">All Status</option>
                <option>Available</option><option>Deployed</option><option>Active</option><option>Returned</option>
            </select>
            <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem" onchange="filterTable()">
                <option value="">All Departments</option>
                <option>IT</option><option>HR</option><option>Finance</option><option>Sales</option>
                <option>Marketing</option><option>Legal</option><option>Ops</option><option>Exec</option>
            </select>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <table class="data-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" style="accent-color:var(--primary)"></th>
                        <th>Asset ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th>Acquired</th>
                        <th>Value</th>
                        <?php if ($role === 'user'): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="inventoryBody">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><input type="checkbox" style="accent-color:var(--primary)"></td>
                        <td style="font-family:monospace;font-size:.8rem;color:var(--text-muted)"><?= $item[0] ?></td>
                        <td style="font-weight:600"><?= htmlspecialchars($item[1]) ?></td>
                        <td style="color:var(--text-secondary);font-size:.85rem"><?= $item[2] ?></td>
                        <td><span style="font-size:.78rem;background:var(--bg);padding:3px 8px;border-radius:4px;color:var(--text-secondary)"><?= $item[3] ?></span></td>
                        <td><?php
                            $sc = ['Available'=>'active','Deployed'=>'deployed','Active'=>'deployed','Returned'=>'returned'];
                            $cls = $sc[$item[4]] ?? 'returned';
                        ?><span class="badge badge-<?= $cls ?>"><?= $item[4] ?></span></td>
                        <td style="font-size:.83rem;color:var(--text-secondary)"><?= $item[5] ?></td>
                        <td style="font-size:.83rem;color:var(--text-muted)"><?= $item[6] ?></td>
                        <td style="font-weight:600;font-size:.88rem"><?= $item[7] ?></td>
                        <?php if ($role === 'user'): ?>
                        <td>
                            <div class="col-actions">
                                <button class="btn btn-secondary btn-xs" onclick="editItem('<?= $item[0] ?>')">Edit</button>
                                <button class="btn btn-xs" style="background:var(--info-bg);color:var(--info);border-color:var(--info-bg)" onclick="deployItem('<?= $item[0] ?>')">Deploy</button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span>Showing 1–<?= count($items) ?> of <?= count($items) ?> items</span>
                <div class="page-btns">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<?php if ($role === 'user'): ?>
<div class="modal-overlay" id="itemModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add New Asset</h3>
            <button class="modal-close-btn" onclick="closeModal('itemModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="form-row">
            <div class="form-col">
                <label class="label">Asset ID</label>
                <input type="text" class="input" placeholder="IT-008" readonly style="background:var(--bg)">
            </div>
            <div class="form-col">
                <label class="label">Category</label>
                <select class="select">
                    <option>Computer</option><option>Mobile</option><option>Printer</option>
                    <option>Furniture</option><option>AV Equip</option><option>Vehicle</option>
                </select>
            </div>
            <div class="form-col form-full">
                <label class="label">Item Name</label>
                <input type="text" class="input" placeholder="e.g. Dell Latitude 5540">
            </div>
            <div class="form-col">
                <label class="label">Department</label>
                <select class="select">
                    <option>IT</option><option>HR</option><option>Finance</option><option>Sales</option>
                    <option>Marketing</option><option>Legal</option><option>Ops</option><option>Exec</option>
                </select>
            </div>
            <div class="form-col">
                <label class="label">Condition</label>
                <select class="select">
                    <option>New</option><option>Good</option><option>Fair</option><option>Poor</option>
                </select>
            </div>
            <div class="form-col">
                <label class="label">Acquisition Date</label>
                <input type="date" class="input" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-col">
                <label class="label">Value (₱)</label>
                <input type="number" class="input" placeholder="0.00">
            </div>
            <div class="form-col form-full">
                <label class="label">Serial / Model Number</label>
                <input type="text" class="input" placeholder="SN-XXXXXXXX">
            </div>
            <div class="form-col form-full">
                <label class="label">Notes</label>
                <textarea class="textarea" placeholder="Additional information..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('itemModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveItem()">Add Asset</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function filterTable() {
    const q = document.getElementById('searchInput')?.value.toLowerCase() || '';
    document.querySelectorAll('#inventoryBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editItem(id) { showToast('Edit item: ' + id, 'info'); }
function deployItem(id) { showToast('Deploy initiated for: ' + id, 'success'); }
function saveItem() { closeModal('itemModal'); showToast('Asset added successfully', 'success'); }
function exportCSV() { showToast('Exporting inventory...', 'info'); }
function showToast(msg, type='info') {
    const t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.textContent = msg;
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(() => t.remove(), 3500);
}
</script>

<?php include '../includes/footer.php'; ?>
