<?php
$page_title = 'Users';
$current_page = 'users';
include '../includes/header.php';
include '../includes/sidebar.php';
$role = $_GET['role'] ?? 'user';

// Redirect viewers
if ($role !== 'user') {
    echo '<script>window.location.href="dashboard.php?role=viewer";</script>';
    exit;
}

$users = [
    ['USR-001', 'Maria Santos',  'maria@company.com',  'Administrator', 'IT',        'Active',   '2024-01-10', 'MA'],
    ['USR-002', 'Jose Buenaventura','jose@company.com', 'Administrator', 'IT',        'Active',   '2024-01-10', 'JB'],
    ['USR-003', 'Ana Reyes',     'ana@company.com',    'Staff',         'HR',        'Active',   '2024-03-15', 'AR'],
    ['USR-004', 'Juan Cruz',     'juan@company.com',   'Staff',         'Sales',     'Active',   '2024-02-20', 'JC'],
    ['USR-005', 'Carlo Diaz',    'carlo@company.com',  'Staff',         'Marketing', 'Active',   '2024-04-01', 'CD'],
    ['USR-006', 'Liza Ramos',    'liza@company.com',   'Staff',         'Legal',     'Active',   '2024-05-10', 'LR'],
    ['USR-007', 'Ben Garcia',    'ben@company.com',    'Staff',         'IT',        'Inactive', '2023-11-05', 'BG'],
    ['USR-008', 'Cora Lim',      'cora@company.com',   'Viewer',        'Finance',   'Active',   '2025-01-20', 'CL'],
];
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Users</h1>
                <p class="page-desc">Manage system access and user roles</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openModal('userModal')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add User
                </button>
            </div>
        </div>

        <!-- Role summary cards -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
            <?php
            $roles = [
                ['Total Users',     count($users),   '#1e40af'],
                ['Administrators',  2,               '#0f766e'],
                ['Staff',           5,               '#0284c7'],
                ['Viewers',         1,               '#64748b'],
            ];
            foreach ($roles as $r): ?>
            <div class="stat-card" style="padding:16px">
                <div class="stat-card-value" style="font-size:1.8rem;color:<?= $r[2] ?>"><?= $r[1] ?></div>
                <div class="stat-card-label"><?= $r[0] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Users Table -->
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Since</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userBody">
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0"><?= $u[8] ?></div>
                                <div>
                                    <div style="font-weight:600;font-size:.88rem"><?= htmlspecialchars($u[1]) ?></div>
                                    <div style="font-size:.74rem;color:var(--text-muted)"><?= $u[0] ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.85rem;color:var(--text-secondary)"><?= htmlspecialchars($u[2]) ?></td>
                        <td>
                            <?php
                            $rc = ['Administrator'=>'deployed','Staff'=>'active','Viewer'=>'returned'];
                            $cls = $rc[$u[3]] ?? 'returned';
                            ?>
                            <span class="badge badge-<?= $cls ?>"><?= $u[3] ?></span>
                        </td>
                        <td><span style="font-size:.78rem;background:var(--bg);padding:3px 8px;border-radius:4px;color:var(--text-secondary)"><?= $u[4] ?></span></td>
                        <td>
                            <span class="badge <?= $u[5] === 'Active' ? 'badge-active' : 'badge-returned' ?>"><?= $u[5] ?></span>
                        </td>
                        <td style="font-size:.82rem;color:var(--text-muted)"><?= $u[6] ?></td>
                        <td>
                            <div class="col-actions">
                                <button class="btn btn-secondary btn-xs" onclick="editUser('<?= $u[0] ?>')">Edit</button>
                                <button class="btn btn-xs" style="background:var(--danger-bg);color:var(--danger);border-color:var(--danger-bg)"
                                    onclick="confirmAction('Deactivate <?= htmlspecialchars($u[1]) ?>?', () => showToast('User deactivated','success'))">
                                    Deactivate
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add New User</h3>
            <button class="modal-close-btn" onclick="closeModal('userModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="form-row">
            <div class="form-col">
                <label class="label">First Name</label>
                <input type="text" class="input" placeholder="First name">
            </div>
            <div class="form-col">
                <label class="label">Last Name</label>
                <input type="text" class="input" placeholder="Last name">
            </div>
            <div class="form-col form-full">
                <label class="label">Email</label>
                <input type="email" class="input" placeholder="user@company.com">
            </div>
            <div class="form-col">
                <label class="label">Role</label>
                <select class="select">
                    <option>Staff</option><option>Administrator</option><option>Viewer</option>
                </select>
            </div>
            <div class="form-col">
                <label class="label">Department</label>
                <select class="select">
                    <option>IT</option><option>HR</option><option>Finance</option><option>Sales</option>
                    <option>Marketing</option><option>Legal</option><option>Ops</option><option>Exec</option>
                </select>
            </div>
            <div class="form-col form-full">
                <label class="label">Temporary Password</label>
                <input type="password" class="input" placeholder="Min. 8 characters">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('userModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveUser()">Create User</button>
        </div>
    </div>
</div>

<script>
function editUser(id) { showToast('Editing user: ' + id, 'info'); }
function saveUser() { closeModal('userModal'); showToast('User created successfully', 'success'); }
</script>

<?php include '../includes/footer.php'; ?>
