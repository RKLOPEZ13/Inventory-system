<?php
$topbar_notifications = $topbar_notifications ?? [
    [
        'title' => 'Deployment approved',
        'body' => 'Laptop Dell XPS 15 was assigned to Maria Santos.',
        'time' => 'Today, 9:14 AM',
        'unread' => true,
    ],
    [
        'title' => 'Inventory review needed',
        'body' => 'Three assets are flagged for maintenance follow-up.',
        'time' => 'Today, 8:40 AM',
        'unread' => true,
    ],
    [
        'title' => 'Weekly report ready',
        'body' => 'Your April utilization summary is available for export.',
        'time' => 'Yesterday, 5:20 PM',
        'unread' => false,
    ],
];

$topbar_unread_count = count(array_filter(
    $topbar_notifications,
    static fn ($item) => !empty($item['unread'])
));
?>
<div class="topbar">
    <div class="topbar-left">
        <button class="icon-btn topbar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
    <div class="topbar-right">
        <div class="notification-menu" id="notificationMenu">
            <button class="icon-btn notification-trigger" id="notificationTrigger" type="button" aria-label="Open notifications" aria-expanded="false" aria-controls="notificationPanel">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <?php if ($topbar_unread_count > 0): ?>
                    <span class="notif-dot" id="notificationDot"></span>
                <?php endif; ?>
            </button>
            <div class="notification-panel" id="notificationPanel" role="dialog" aria-label="Notifications">
                <div class="notification-panel-head">
                    <div>
                        <div class="notification-title">Notifications</div>
                        <div class="notification-subtitle" id="notificationSubtitle">
                            <?= $topbar_unread_count > 0 ? $topbar_unread_count . ' unread updates' : 'You are all caught up' ?>
                        </div>
                    </div>
                </div>
                <div class="notification-list" id="notificationList">
                    <?php foreach ($topbar_notifications as $item): ?>
                        <div class="notification-item <?= !empty($item['unread']) ? 'is-unread' : '' ?>">
                            <div class="notification-item-dot"></div>
                            <div class="notification-item-body">
                                <div class="notification-item-title"><?= htmlspecialchars($item['title']) ?></div>
                                <div class="notification-item-text"><?= htmlspecialchars($item['body']) ?></div>
                                <div class="notification-item-time"><?= htmlspecialchars($item['time']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="notification-panel-footer">
                    <button class="notification-mark-read" id="markAllNotificationsBtn" type="button" onclick="markAllNotificationsRead()">
                        Mark all as read
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
