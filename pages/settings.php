<?php
$page_title = 'Settings';
$current_page = 'settings';
include '../includes/header.php';
include '../includes/sidebar.php';
$role = $_GET['role'] ?? 'user';

if ($role !== 'user') {
    echo '<script>window.location.href="dashboard.php?role=viewer";</script>';
    exit;
}
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Settings</h1>
                <p class="page-desc">Configure the system, categories, and access controls</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary btn-sm" onclick="saveSettings()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs" style="max-width:600px">
            <button class="tab-btn active" onclick="switchTab('general', this)">General</button>
            <button class="tab-btn" onclick="switchTab('categories', this)">Categories</button>
            <button class="tab-btn" onclick="switchTab('access', this)">Access</button>
            <button class="tab-btn" onclick="switchTab('backup', this)">Backup</button>
        </div>

        <!-- General Tab -->
        <div id="tab-general">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div class="card">
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:20px">System Information</div>
                    <div class="form-row" style="grid-template-columns:1fr">
                        <div class="form-col">
                            <label class="label">Organization Name</label>
                            <input type="text" class="input" value="ACME Corporation">
                        </div>
                        <div class="form-col">
                            <label class="label">System Name</label>
                            <input type="text" class="input" value="AssetTrack">
                        </div>
                        <div class="form-col">
                            <label class="label">Currency Symbol</label>
                            <input type="text" class="input" value="₱" style="width:80px">
                        </div>
                        <div class="form-col">
                            <label class="label">Date Format</label>
                            <select class="select">
                                <option selected>YYYY-MM-DD</option>
                                <option>MM/DD/YYYY</option>
                                <option>DD/MM/YYYY</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="label">Timezone</label>
                            <select class="select">
                                <option selected>Asia/Manila (UTC+8)</option>
                                <option>UTC</option>
                                <option>America/New_York</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:20px">Notifications</div>
                    <div style="display:flex;flex-direction:column;gap:16px">
                        <?php
                        $notifs = [
                            ['Email on new deployment', true],
                            ['Email on return', false],
                            ['Alert for overdue items', true],
                            ['Weekly summary report', true],
                            ['Low stock alerts', false],
                        ];
                        foreach ($notifs as [$label, $checked]): ?>
                        <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer">
                            <span style="font-size:.88rem;color:var(--text-secondary)"><?= $label ?></span>
                            <div class="toggle <?= $checked ? 'toggle-on' : '' ?>" onclick="this.classList.toggle('toggle-on')">
                                <div class="toggle-thumb"></div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Tab -->
        <div id="tab-categories" style="display:none">
            <div class="card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700">Asset Categories</div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('catModal')">Add Category</button>
                </div>
                <?php
                $categories = [
                    ['Computer',  'blue',   245, true],
                    ['Mobile',    'teal',   62,  true],
                    ['Furniture', 'purple', 98,  true],
                    ['AV Equip',  'orange', 48,  true],
                    ['Vehicle',   'slate',  23,  true],
                    ['Printer',   'blue',   31,  true],
                    ['Tablet',    'teal',   18,  true],
                    ['Telecom',   'slate',  11,  false],
                ];
                foreach ($categories as $cat): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border-light)">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:34px;height:34px;border-radius:8px;background:var(--bg);display:flex;align-items:center;justify-content:center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:600"><?= $cat[0] ?></div>
                            <div style="font-size:.75rem;color:var(--text-muted)"><?= $cat[2] ?> assets</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px">
                        <span class="badge <?= $cat[3] ? 'badge-active' : 'badge-returned' ?>"><?= $cat[3] ? 'Active' : 'Hidden' ?></span>
                        <button class="btn btn-secondary btn-xs">Edit</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Access Tab -->
        <div id="tab-access" style="display:none">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div class="card">
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:20px">Viewer Passcode</div>
                    <p style="font-size:.85rem;color:var(--text-secondary);margin-bottom:16px">The passcode used by read-only viewers to access the landing page.</p>
                    <div class="form-col" style="gap:8px;margin-bottom:16px">
                        <label class="label">Current Passcode</label>
                        <input type="password" class="input" value="123456" placeholder="6-digit code">
                    </div>
                    <div class="form-col" style="gap:8px;margin-bottom:16px">
                        <label class="label">New Passcode</label>
                        <input type="password" class="input" placeholder="Enter new 6-digit code">
                    </div>
                    <div class="form-col" style="gap:8px;margin-bottom:20px">
                        <label class="label">Confirm Passcode</label>
                        <input type="password" class="input" placeholder="Repeat new code">
                    </div>
                    <button class="btn btn-primary" onclick="showToast('Passcode updated','success')">Update Passcode</button>
                </div>

                <div class="card">
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:20px">Session Settings</div>
                    <div style="display:flex;flex-direction:column;gap:16px">
                        <div class="form-col" style="gap:8px">
                            <label class="label">Session Timeout (minutes)</label>
                            <input type="number" class="input" value="60" min="15" max="480">
                        </div>
                        <div class="form-col" style="gap:8px">
                            <label class="label">Max Login Attempts</label>
                            <input type="number" class="input" value="5" min="3" max="10">
                        </div>
                        <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;margin-top:8px">
                            <span style="font-size:.88rem;color:var(--text-secondary)">Require 2FA for admins</span>
                            <div class="toggle" onclick="this.classList.toggle('toggle-on')">
                                <div class="toggle-thumb"></div>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer">
                            <span style="font-size:.88rem;color:var(--text-secondary)">Allow viewer CSV export</span>
                            <div class="toggle toggle-on" onclick="this.classList.toggle('toggle-on')">
                                <div class="toggle-thumb"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Tab -->
        <div id="tab-backup" style="display:none">
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:6px">Database Backup</div>
                <p style="font-size:.85rem;color:var(--text-secondary);margin-bottom:24px">Download a full backup of the inventory database or restore from a previous backup.</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px">
                    <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:20px;text-align:center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin-bottom:10px">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <div style="font-weight:600;margin-bottom:4px">Export Backup</div>
                        <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:14px">Download full database as SQL</p>
                        <button class="btn btn-primary btn-sm" onclick="showToast('Backup download started','success')">Download Now</button>
                    </div>
                    <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:20px;text-align:center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="1.5" style="margin-bottom:10px">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <div style="font-weight:600;margin-bottom:4px">Restore Backup</div>
                        <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:14px">Upload a .sql backup file</p>
                        <button class="btn btn-secondary btn-sm" onclick="showToast('Select a .sql file to restore','info')">Choose File</button>
                    </div>
                </div>

                <div style="font-family:var(--font-main);font-size:.88rem;font-weight:700;margin-bottom:12px">Backup History</div>
                <?php
                $backups = [
                    ['backup_2026-04-07_09-00.sql', '2026-04-07 09:00', '2.4 MB', 'Auto'],
                    ['backup_2026-04-06_09-00.sql', '2026-04-06 09:00', '2.3 MB', 'Auto'],
                    ['backup_2026-04-05_manual.sql', '2026-04-05 15:32', '2.3 MB', 'Manual'],
                    ['backup_2026-04-01_09-00.sql', '2026-04-01 09:00', '2.2 MB', 'Auto'],
                ];
                foreach ($backups as $b): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid var(--border-light)">
                    <div style="display:flex;align-items:center;gap:10px">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <div>
                            <div style="font-size:.83rem;font-weight:500"><?= $b[0] ?></div>
                            <div style="font-size:.74rem;color:var(--text-muted)"><?= $b[2] ?> · <?= $b[3] ?></div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-size:.78rem;color:var(--text-muted)"><?= $b[1] ?></span>
                        <button class="btn btn-secondary btn-xs" onclick="showToast('Downloading backup...','info')">Download</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal-overlay" id="catModal">
    <div class="modal" style="max-width:400px">
        <div class="modal-header">
            <h3 class="modal-title">Add Category</h3>
            <button class="modal-close-btn" onclick="closeModal('catModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px">
            <div class="form-col" style="gap:6px">
                <label class="label">Category Name</label>
                <input type="text" class="input" placeholder="e.g. Medical Equipment">
            </div>
            <div class="form-col" style="gap:6px">
                <label class="label">Description</label>
                <textarea class="textarea" style="min-height:70px" placeholder="Optional description"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('catModal')">Cancel</button>
            <button class="btn btn-primary" onclick="closeModal('catModal');showToast('Category added','success')">Add</button>
        </div>
    </div>
</div>

<style>
/* Toggle Switch */
.toggle {
    width: 40px; height: 22px;
    background: var(--border);
    border-radius: 11px;
    position: relative;
    transition: background 200ms ease;
    flex-shrink: 0;
}
.toggle-on { background: var(--primary); }
.toggle-thumb {
    width: 16px; height: 16px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 3px; left: 3px;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
    transition: transform 200ms ease;
}
.toggle-on .toggle-thumb { transform: translateX(18px); }
</style>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(t => t.style.display = 'none');
    document.getElementById('tab-' + name).style.display = '';
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
function saveSettings() { showToast('Settings saved successfully', 'success'); }
</script>

<?php include '../includes/footer.php'; ?>
