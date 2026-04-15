<?php
$page_title = 'Settings';
$current_page = 'settings';
$role = $_GET['role'] ?? 'user';

require_once '../includes/settings_helpers.php';

if ($role !== 'user') {
    echo '<script>window.location.href="dashboard.php?role=viewer";</script>';
    exit;
}

$settingsDropdowns = settings_dropdown_payload($pdo);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Settings</h1>
                <p class="page-desc">Configure the system, dropdowns, and access controls</p>
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

        <div class="tabs" style="max-width:600px">
            <button class="tab-btn active" onclick="switchTab('general', this)">General</button>
            <button class="tab-btn" onclick="switchTab('dropdowns', this)">Dropdowns</button>
            <button class="tab-btn" onclick="switchTab('access', this)">Access</button>
            <button class="tab-btn" onclick="switchTab('backup', this)">Backup</button>
        </div>

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
                            <input type="text" class="input" value="&#8369;" style="width:80px">
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

        <div id="tab-dropdowns" style="display:none">
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:6px">System Dropdowns</div>
                <p style="font-size:.84rem;color:var(--text-secondary);margin-bottom:20px">View each dropdown field used across the system and open it to manage its option list.</p>
                <?php foreach ($settingsDropdowns as $dropdown): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border-light)">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:34px;height:34px;border-radius:8px;background:var(--bg);display:flex;align-items:center;justify-content:center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:600"><?= htmlspecialchars($dropdown['name']) ?></div>
                            <div style="font-size:.75rem;color:var(--text-muted)">
                                <?= (int) $dropdown['count'] ?> options<?php if ($dropdown['preview'] !== ''): ?> - <?= htmlspecialchars($dropdown['preview']) ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
                        <button
                            class="btn btn-secondary btn-xs"
                            type="button"
                            data-dropdown-key="<?= htmlspecialchars($dropdown['key']) ?>"
                            data-dropdown-name="<?= htmlspecialchars($dropdown['name']) ?>"
                            data-dropdown-options='<?= htmlspecialchars(json_encode($dropdown['options'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                            onclick="openDropdownModal(this)"
                        >
                            Edit
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

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
                            <div style="font-size:.74rem;color:var(--text-muted)"><?= $b[2] ?> - <?= $b[3] ?></div>
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

<div class="modal-overlay" id="dropdownModal">
    <div class="modal" style="max-width:520px">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="dropdownModalTitle">Edit Dropdown</h3>
                <p id="dropdownModalCopy" style="margin-top:4px;font-size:.8rem;color:var(--text-secondary)">Add or remove options for the selected dropdown field.</p>
            </div>
            <button class="modal-close-btn" onclick="closeModal('dropdownModal')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div style="display:flex;flex-direction:column;gap:16px">
            <input type="hidden" id="dropdownModalKey">
            <div>
                <label class="label" style="margin-bottom:8px;display:block">Current Options</label>
                <div id="dropdownModalOptions" style="display:flex;flex-direction:column;gap:10px;max-height:280px;overflow-y:auto;padding-right:4px"></div>
            </div>
            <div class="form-col" style="gap:8px">
                <label class="label">Add New Option</label>
                <div style="display:flex;gap:10px;align-items:center">
                    <input type="text" class="input" id="dropdownModalNewOption" placeholder="Enter new option label">
                    <button class="btn btn-primary btn-sm" type="button" id="dropdownModalAddBtn">Add</button>
                </div>
                <p style="font-size:.76rem;color:var(--text-muted);margin:0">Delete only works for options that are not used by existing records.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('dropdownModal')">Close</button>
        </div>
    </div>
</div>

<style>
.toggle {
    width: 40px;
    height: 22px;
    background: var(--border);
    border-radius: 11px;
    position: relative;
    transition: background 200ms ease;
    flex-shrink: 0;
}

.toggle-on {
    background: var(--primary);
}

.toggle-thumb {
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    transition: transform 200ms ease;
}

.toggle-on .toggle-thumb {
    transform: translateX(18px);
}
</style>

<script>
const dropdownEndpoint = '../api/settings/dropdowns.php';
const dropdownState = {
    key: '',
    name: '',
    options: [],
};

function switchTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.style.display = 'none';
    });

    document.getElementById('tab-' + name).style.display = '';
    document.querySelectorAll('.tab-btn').forEach(tabButton => {
        tabButton.classList.remove('active');
    });
    btn.classList.add('active');
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function parseDropdownOptions(rawOptions) {
    try {
        const parsed = JSON.parse(rawOptions || '[]');

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed
            .map(option => ({
                option_id: String(option.option_id ?? ''),
                option_label: String(option.option_label ?? ''),
            }))
            .filter(option => option.option_id !== '' && option.option_label !== '');
    } catch (error) {
        return [];
    }
}

function renderDropdownModalOptions() {
    const modalOptions = document.getElementById('dropdownModalOptions');

    if (!modalOptions) {
        return;
    }

    if (!dropdownState.options.length) {
        modalOptions.innerHTML = `
            <div style="padding:14px;border:1px dashed var(--border);border-radius:var(--radius-sm);background:var(--bg);font-size:.82rem;color:var(--text-muted);text-align:center;">
                No options yet.
            </div>
        `;
        return;
    }

    modalOptions.innerHTML = dropdownState.options.map(option => `
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface);">
            <span style="font-size:.84rem;color:var(--text-primary)">${escapeHtml(option.option_label)}</span>
            <button
                class="btn btn-secondary btn-xs"
                type="button"
                data-option-id="${escapeHtml(option.option_id)}"
                data-option-label="${escapeHtml(option.option_label)}"
            >
                Delete
            </button>
        </div>
    `).join('');
}

function openDropdownModal(button) {
    const modalTitle = document.getElementById('dropdownModalTitle');
    const modalCopy = document.getElementById('dropdownModalCopy');
    const newOptionInput = document.getElementById('dropdownModalNewOption');
    const dropdownKeyInput = document.getElementById('dropdownModalKey');

    dropdownState.key = button.dataset.dropdownKey || '';
    dropdownState.name = button.dataset.dropdownName || 'Dropdown';
    dropdownState.options = parseDropdownOptions(button.dataset.dropdownOptions || '[]');

    if (dropdownKeyInput) {
        dropdownKeyInput.value = dropdownState.key;
    }

    if (newOptionInput) {
        newOptionInput.value = '';
    }

    modalTitle.textContent = dropdownState.name + ' Options';
    modalCopy.textContent = 'Add or remove options for the ' + dropdownState.name.toLowerCase() + ' dropdown field.';

    renderDropdownModalOptions();
    openModal('dropdownModal');
}

async function postDropdownAction(formData) {
    const response = await fetch(dropdownEndpoint, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    let result = null;

    try {
        result = await response.json();
    } catch (error) {
        result = null;
    }

    if (!response.ok || !result || !result.success) {
        throw new Error(result?.message || 'Unable to update dropdown.');
    }

    return result;
}

async function addDropdownOption() {
    const addButton = document.getElementById('dropdownModalAddBtn');
    const input = document.getElementById('dropdownModalNewOption');

    if (!addButton || !input) {
        return;
    }

    const optionLabel = input.value.trim();

    if (dropdownState.key === '') {
        showToast('Select a dropdown first.', 'error');
        return;
    }

    if (optionLabel === '') {
        showToast('Enter an option label.', 'info');
        input.focus();
        return;
    }

    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('dropdown_key', dropdownState.key);
    formData.append('option_label', optionLabel);

    addButton.disabled = true;

    try {
        const result = await postDropdownAction(formData);
        showToast(result.message || 'Dropdown updated.', 'success');
        window.setTimeout(() => window.location.reload(), 350);
    } catch (error) {
        addButton.disabled = false;
        showToast(error.message || 'Unable to add option.', 'error');
    }
}

function deleteDropdownOption(button) {
    if (!(button instanceof HTMLButtonElement)) {
        return;
    }

    const optionId = button.dataset.optionId || '';
    const optionLabel = button.dataset.optionLabel || 'this option';

    if (dropdownState.key === '' || optionId === '') {
        return;
    }

    confirmAction(`Delete ${optionLabel} from ${dropdownState.name}?`, async () => {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('dropdown_key', dropdownState.key);
        formData.append('option_id', optionId);

        button.disabled = true;

        try {
            const result = await postDropdownAction(formData);
            showToast(result.message || 'Dropdown updated.', 'success');
            window.setTimeout(() => window.location.reload(), 350);
        } catch (error) {
            button.disabled = false;
            showToast(error.message || 'Unable to delete option.', 'error');
        }
    });
}

document.getElementById('dropdownModalAddBtn')?.addEventListener('click', addDropdownOption);

document.getElementById('dropdownModalNewOption')?.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        event.preventDefault();
        addDropdownOption();
    }
});

document.getElementById('dropdownModalOptions')?.addEventListener('click', event => {
    const button = event.target.closest('button[data-option-id]');

    if (button) {
        deleteDropdownOption(button);
    }
});

function saveSettings() {
    showToast('Dropdown changes save immediately. The other tabs are still static.', 'info');
}
</script>

<?php include '../includes/footer.php'; ?>
