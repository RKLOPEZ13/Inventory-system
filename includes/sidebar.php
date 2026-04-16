<?php
// includes/sidebar.php
// Usage: include 'includes/sidebar.php';
// Set $current_page before including (e.g. 'dashboard', 'inventory', 'deployment', 'history', 'users', 'reports', 'settings')
$current_page = $current_page ?? 'dashboard';
$role = $_GET['role'] ?? 'user';

$nav = [
    'main' => [
        ['id' => 'dashboard', 'label' => 'Dashboard',  'icon' => 'grid',       'href' => 'dashboard.php'],
        ['id' => 'inventory', 'label' => 'Inventory',  'icon' => 'package',    'href' => 'inventory.php', 'badge' => '12'],
        ['id' => 'deployment','label' => 'Deployment', 'icon' => 'package',    'href' => 'deployment.php'],
        ['id' => 'history',   'label' => 'History Log','icon' => 'clock',      'href' => 'history.php'],
        ['id' => 'reports',   'label' => 'Reports',    'icon' => 'bar-chart',  'href' => 'reports.php'],
    ],
    'admin' => [
        ['id' => 'users',    'label' => 'Users',    'icon' => 'users',   'href' => 'users.php'],
        ['id' => 'settings', 'label' => 'Settings', 'icon' => 'settings','href' => 'settings.php'],
    ],
];

$icons = [
    'grid'      => '<path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>',
    'package'   => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
    'clock'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    'bar-chart' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'settings'  => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
];

function nav_icon($name, $icons) {
    return '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($icons[$name] ?? '') . '</svg>';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                <line x1="12" y1="22.08" x2="12" y2="12"/>
            </svg>
        </div>
        <span class="sidebar-brand">AssetTrack</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <?php foreach ($nav['main'] as $item): ?>
            <a href="<?= $item['href'] ?>?role=<?= htmlspecialchars($role) ?>"
               class="nav-item <?= $current_page === $item['id'] ? 'active' : '' ?>">
                <?= nav_icon($item['icon'], $icons) ?>
                <?= htmlspecialchars($item['label']) ?>
                <?php if (!empty($item['badge'])): ?>
                    <span class="nav-badge"><?= $item['badge'] ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>

        <?php if ($role === 'user'): ?>
        <div class="nav-section-label">Admin</div>
        <?php foreach ($nav['admin'] as $item): ?>
            <a href="<?= $item['href'] ?>?role=<?= htmlspecialchars($role) ?>"
               class="nav-item <?= $current_page === $item['id'] ? 'active' : '' ?>">
                <?= nav_icon($item['icon'], $icons) ?>
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endforeach; ?>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= $role === 'user' ? 'AD' : 'VW' ?></div>
            <div class="user-info">
                <div class="user-name"><?= $role === 'user' ? 'Admin User' : 'Viewer' ?></div>
                <div class="user-role"><?= $role === 'user' ? 'Administrator' : 'Read Only' ?></div>
            </div>
        </div>
        <button class="sidebar-logout-btn" type="button" onclick="logoutUser()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Logout
        </button>
    </div>
</aside>
