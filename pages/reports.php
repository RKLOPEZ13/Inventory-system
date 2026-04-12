<?php
$page_title = 'Reports';
$current_page = 'reports';
include '../includes/header.php';
include '../includes/sidebar.php';
$role = $_GET['role'] ?? 'user';
?>

<div class="main-content">
    <?php include '../includes/topbar.php'; ?>

    <div class="page-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Reports</h1>
                <p class="page-desc">Deployment analytics and asset utilization insights</p>
            </div>
            <div class="header-actions">
                <select class="select" style="width:auto;padding:8px 32px 8px 12px;font-size:.85rem">
                    <option>April 2026</option><option>March 2026</option><option>Q1 2026</option><option>2025</option>
                </select>
                <button class="btn btn-secondary btn-sm">Apply</button>
                <button class="btn btn-secondary btn-sm" onclick="showToast('Generating PDF report...','info')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- KPI Row -->
        <div class="stat-cards" style="margin-bottom:20px">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--surface-tint);color:var(--primary)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 8%</span>
                </div>
                <div class="stat-card-value">95.2%</div>
                <div class="stat-card-label">Utilization Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--success-bg);color:var(--success)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 15</span>
                </div>
                <div class="stat-card-value">48</div>
                <div class="stat-card-label">Deployments This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--warning-bg);color:var(--warning)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14l-4-4 4-4M5 10h11a4 4 0 0 1 0 8h-1"/></svg>
                    </div>
                    <span class="stat-card-trend trend-down">↓ 3</span>
                </div>
                <div class="stat-card-value">12</div>
                <div class="stat-card-label">Returns This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon" style="background:var(--info-bg);color:var(--info)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="stat-card-trend trend-up">↑ 12%</span>
                </div>
                <div class="stat-card-value">₱2.4M</div>
                <div class="stat-card-label">Total Asset Value</div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

            <!-- Monthly Deployments Trend -->
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:4px">Monthly Deployment Trend</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:20px">Jan – Apr 2026</div>
                <?php
                $months = ['Jan'=>28,'Feb'=>34,'Mar'=>41,'Apr'=>48];
                $max = max($months);
                ?>
                <div style="display:flex;align-items:flex-end;gap:14px;height:160px">
                    <?php foreach ($months as $m => $v):
                        $h = round(($v / $max) * 140);
                    ?>
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
                        <span style="font-size:.75rem;font-weight:700;color:var(--primary)"><?= $v ?></span>
                        <div style="width:100%;height:<?= $h ?>px;background:linear-gradient(180deg,var(--primary),var(--primary-light));border-radius:6px 6px 0 0;min-height:4px"></div>
                        <span style="font-size:.72rem;color:var(--text-muted)"><?= $m ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:4px">Assets by Category</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:20px">Distribution of all assets</div>
                <?php
                $cats = [
                    ['Computer',  245, '#1e40af', 47],
                    ['Furniture', 98,  '#0f766e', 19],
                    ['Mobile',    62,  '#0284c7', 12],
                    ['AV Equip',  48,  '#7c3aed', 9],
                    ['Vehicle',   23,  '#d97706', 4],
                    ['Other',     48,  '#94a3b8', 9],
                ];
                foreach ($cats as $c): ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px">
                    <div style="width:10px;height:10px;border-radius:2px;background:<?= $c[2] ?>;flex-shrink:0"></div>
                    <span style="font-size:.83rem;color:var(--text-secondary);flex:1"><?= $c[0] ?></span>
                    <div style="flex:3;height:8px;background:var(--border);border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:<?= $c[3] ?>%;background:<?= $c[2] ?>;border-radius:4px"></div>
                    </div>
                    <span style="font-size:.8rem;font-weight:700;color:var(--text-primary);min-width:32px;text-align:right"><?= $c[1] ?></span>
                    <span style="font-size:.75rem;color:var(--text-muted);min-width:28px;text-align:right"><?= $c[3] ?>%</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

            <!-- Department Usage -->
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:4px">Assets by Department</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:20px">Currently deployed</div>
                <?php
                $depts = [
                    ['IT',        85, '#1e40af'],
                    ['Finance',   60, '#0f766e'],
                    ['Operations',75, '#0284c7'],
                    ['HR',        42, '#7c3aed'],
                    ['Marketing', 55, '#d97706'],
                    ['Legal',     28, '#dc2626'],
                    ['Exec',      35, '#64748b'],
                ];
                $dmax = max(array_column($depts, 1));
                foreach ($depts as $d): $w = round(($d[1]/$dmax)*100); ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                    <span style="font-size:.8rem;color:var(--text-secondary);width:80px;flex-shrink:0"><?= $d[0] ?></span>
                    <div style="flex:1;height:10px;background:var(--border);border-radius:5px;overflow:hidden">
                        <div style="height:100%;width:<?= $w ?>%;background:<?= $d[2] ?>;border-radius:5px;transition:width 600ms ease"></div>
                    </div>
                    <span style="font-size:.82rem;font-weight:700;color:var(--text-primary);min-width:24px;text-align:right"><?= $d[1] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Condition Report -->
            <div class="card">
                <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:4px">Asset Condition Report</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:20px">Current physical condition of all assets</div>
                <?php
                $conditions = [
                    ['New',       52,  '#16a34a', 10],
                    ['Good',      348, '#0284c7', 66],
                    ['Fair',      95,  '#d97706', 18],
                    ['Poor',      21,  '#dc2626', 4],
                    ['Written Off',8,  '#94a3b8', 2],
                ];
                foreach ($conditions as $c): ?>
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                        <div style="display:flex;align-items:center;gap:7px">
                            <div style="width:8px;height:8px;border-radius:50%;background:<?= $c[2] ?>"></div>
                            <span style="font-size:.83rem;font-weight:500;color:var(--text-primary)"><?= $c[0] ?></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <span style="font-size:.8rem;color:var(--text-muted)"><?= $c[3] ?>%</span>
                            <span style="font-size:.85rem;font-weight:700;color:var(--text-primary)"><?= $c[1] ?></span>
                        </div>
                    </div>
                    <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:<?= $c[3] ?>%;background:<?= $c[2] ?>;border-radius:3px"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Deployed Items Table -->
        <div class="card" style="padding:0">
            <div style="padding:20px 24px 16px;display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-family:var(--font-main);font-size:.95rem;font-weight:700;margin-bottom:2px">Top Deployed Asset Types</div>
                    <div style="font-size:.78rem;color:var(--text-muted)">Most frequently assigned items</div>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="showToast('Exporting table...','info')">Export</button>
            </div>
            <div style="overflow-x:auto;border-top:1px solid var(--border)">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Times Deployed</th>
                            <th>Current Status</th>
                            <th>Asset Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $top = [
                            [1,'Laptop Dell XPS 15','Computer',24,'Deployed','₱85,000'],
                            [2,'iPhone 15 Pro','Mobile',18,'Deployed','₱65,000'],
                            [3,'MacBook Air M3','Computer',15,'Deployed','₱95,000'],
                            [4,'Office Chair Ergo','Furniture',31,'Deployed','₱12,000'],
                            [5,'HP LaserJet Pro','Printer',9,'Active','₱25,000'],
                        ];
                        foreach ($top as $t): ?>
                        <tr>
                            <td style="color:var(--text-muted);font-weight:700"><?= $t[0] ?></td>
                            <td style="font-weight:600"><?= $t[1] ?></td>
                            <td style="font-size:.83rem;color:var(--text-secondary)"><?= $t[2] ?></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div style="height:6px;width:80px;background:var(--border);border-radius:3px;overflow:hidden">
                                        <div style="height:100%;width:<?= round(($t[3]/31)*100) ?>%;background:var(--primary);border-radius:3px"></div>
                                    </div>
                                    <span style="font-weight:700;color:var(--primary)"><?= $t[3] ?>×</span>
                                </div>
                            </td>
                            <td><span class="badge badge-<?= $t[4]==='Deployed'?'deployed':'active' ?>"><?= $t[4] ?></span></td>
                            <td style="font-weight:600"><?= $t[5] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
