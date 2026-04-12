<?php
$page_title = 'Dashboard';
$current_page = 'dashboard';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Dashboard</h1>
                <p class="page-desc">Overview of all company assets and deployments</p>
            </div>
            <div class="header-actions">
                <span style="font-size:.8rem;color:var(--text-muted)">
                    <?= date('l, F j, Y') ?>
                </span>
                <?php if ($role === 'user'): ?>
                <button class="btn btn-primary" onclick="openModal('deployModal')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    New Deployment
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--surface-tint);color:var(--primary)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 8%</span>
                </div>
                <div class="stat-card-value">524</div>
                <div class="stat-card-label">Total Assets</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--info-bg);color:var(--info)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 3</span>
                </div>
                <div class="stat-card-value">312</div>
                <div class="stat-card-label">Deployed Items</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--success-bg);color:var(--success)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 12%</span>
                </div>
                <div class="stat-card-value">189</div>
                <div class="stat-card-label">Available</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--warning-bg);color:var(--warning)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <span class="stat-card-trend trend-down">↑ 2</span>
                </div>
                <div class="stat-card-value">23</div>
                <div class="stat-card-label">Needs Attention</div>
            </div>
        </div>

        <!-- Grid: Chart + Recent -->
        <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;margin-bottom:20px">
            <!-- Deployment Chart -->
            <div class="card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                    <div>
                        <div style="font-family:var(--font-main);font-size:1rem;font-weight:700;margin-bottom:4px">Deployment Overview</div>
                        <div style="font-size:.8rem;color:var(--text-muted)">Items deployed per department</div>
                    </div>
                    <select class="select" style="width:auto;padding:6px 32px 6px 10px;font-size:.8rem">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Quarter</option>
                    </select>
                </div>
                <!-- Simple bar chart via CSS -->
                <div style="display:flex;align-items:flex-end;gap:12px;height:180px;padding:0 8px">
                    <?php
                    $depts = ['IT' => 85, 'HR' => 42, 'Finance' => 60, 'Ops' => 75, 'Legal' => 28, 'Marketing' => 55, 'Exec' => 35];
                    $max = max($depts);
                    foreach ($depts as $dept => $val):
                        $h = round(($val / $max) * 160);
                    ?>
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
                        <span style="font-size:.72rem;color:var(--text-muted);font-weight:600"><?= $val ?></span>
                        <div style="width:100%;height:<?= $h ?>px;background:var(--primary);border-radius:6px 6px 0 0;opacity:.85;transition:opacity 200ms ease;min-height:4px" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.85"></div>
                        <span style="font-size:.7rem;color:var(--text-muted)"><?= $dept ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Status Donut-style -->
            <div class="card">
                <div style="font-family:var(--font-main);font-size:1rem;font-weight:700;margin-bottom:4px">Asset Status</div>
                <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Current distribution</div>
                <?php
                $statuses = [
                    ['label' => 'Deployed',    'count' => 312, 'color' => 'var(--primary)', 'pct' => 60],
                    ['label' => 'Available',   'count' => 189, 'color' => 'var(--success)', 'pct' => 36],
                    ['label' => 'Maintenance', 'count' => 15,  'color' => 'var(--warning)', 'pct' => 3],
                    ['label' => 'Written Off', 'count' => 8,   'color' => 'var(--text-muted)', 'pct' => 1],
                ];
                foreach ($statuses as $s):
                ?>
                <div style="margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.82rem;font-weight:500;color:var(--text-secondary)"><?= $s['label'] ?></span>
                        <span style="font-size:.82rem;font-weight:700;color:var(--text-primary)"><?= $s['count'] ?></span>
                    </div>
                    <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:<?= $s['pct'] ?>%;background:<?= $s['color'] ?>;border-radius:3px;transition:width 600ms ease"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Deployments Table -->
        <div class="card" style="padding:0">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px 16px">
                <div>
                    <div style="font-family:var(--font-main);font-size:1rem;font-weight:700;margin-bottom:2px">Recent Deployments</div>
                    <div style="font-size:.8rem;color:var(--text-muted)">Last 10 deployment transactions</div>
                </div>
                <a href="history.php?role=<?= htmlspecialchars($role) ?>" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="table-wrap" style="border-radius:0 0 var(--radius-lg) var(--radius-lg);border:none;border-top:1px solid var(--border)">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Category</th>
                            <th>Assigned To</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = [
                            ['Laptop Dell XPS 15', 'Computer', 'Maria Santos', 'IT', '2026-04-07', 'deployed'],
                            ['iPhone 15 Pro', 'Mobile', 'Juan Cruz', 'Sales', '2026-04-06', 'deployed'],
                            ['Office Chair Ergo', 'Furniture', 'Ana Reyes', 'HR', '2026-04-05', 'deployed'],
                            ['Projector Epson X', 'AV Equip', 'Training Room', 'Ops', '2026-04-04', 'active'],
                            ['MacBook Air M3', 'Computer', 'Carlo Diaz', 'Marketing', '2026-04-03', 'deployed'],
                            ['HP LaserJet Pro', 'Printer', 'Finance Dept', 'Finance', '2026-04-02', 'active'],
                            ['iPad Pro 12.9', 'Tablet', 'Liza Ramos', 'Legal', '2026-04-01', 'deployed'],
                            ['Desk Lamp LED', 'Furniture', 'Ben Garcia', 'IT', '2026-03-31', 'returned'],
                        ];
                        foreach ($rows as $r): ?>
                        <tr>
                            <td style="font-weight:600"><?= htmlspecialchars($r[0]) ?></td>
                            <td style="color:var(--text-secondary)"><?= htmlspecialchars($r[1]) ?></td>
                            <td><?= htmlspecialchars($r[2]) ?></td>
                            <td><span style="font-size:.78rem;background:var(--bg);padding:3px 8px;border-radius:4px;color:var(--text-secondary)"><?= htmlspecialchars($r[3]) ?></span></td>
                            <td style="color:var(--text-muted);font-size:.83rem"><?= $r[4] ?></td>
                            <td><span class="badge badge-<?= $r[5] ?>"><?= ucfirst($r[5]) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /.page-content -->
</div>

<?php include '../includes/footer.php'; ?>
