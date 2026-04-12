/* ============================================
   AssetTrack — App JS (Shared)
   ============================================ */

// ---- Sidebar Toggle (mobile) ----
const appLayout = document.querySelector('.app-layout');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
if (sidebarToggle) {
    sidebarToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        if (!sidebar || !appLayout) return;

        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('open');
            return;
        }

        appLayout.classList.toggle('sidebar-collapsed');
    });

    document.addEventListener('click', (e) => {
        if (!sidebar || window.innerWidth > 768) return;
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });

    window.addEventListener('resize', () => {
        if (!sidebar || !appLayout) return;
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
        }
    });
}

// ---- Notifications ----
const notificationMenu = document.getElementById('notificationMenu');
const notificationTrigger = document.getElementById('notificationTrigger');
const notificationPanel = document.getElementById('notificationPanel');
if (notificationMenu && notificationTrigger && notificationPanel) {
    notificationTrigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = notificationMenu.classList.toggle('open');
        notificationTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    notificationPanel.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    document.addEventListener('click', () => {
        notificationMenu.classList.remove('open');
        notificationTrigger.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            notificationMenu.classList.remove('open');
            notificationTrigger.setAttribute('aria-expanded', 'false');
        }
    });
}

// ---- Toast ----
function showToast(msg, type = 'info', duration = 3500) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const icons = {
        success: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        info:    '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span style="color:var(--${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'})">${icons[type] || ''}</span>${msg}`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideIn 200ms ease reverse forwards';
        setTimeout(() => toast.remove(), 200);
    }, duration);
}

// ---- Modal helpers ----
function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('active');
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('active');
}

// Close modal on overlay click
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
    }
});

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
});

// ---- Confirm Dialog ----
function confirmAction(msg, callback) {
    if (window.confirm(msg)) callback();
}

function markAllNotificationsRead() {
    const notificationList = document.getElementById('notificationList');
    const notificationSubtitle = document.getElementById('notificationSubtitle');
    const notificationDot = document.getElementById('notificationDot');
    const markAllBtn = document.getElementById('markAllNotificationsBtn');

    if (!notificationList) return;

    notificationList.querySelectorAll('.notification-item.is-unread').forEach(item => {
        item.classList.remove('is-unread');
    });

    if (notificationSubtitle) {
        notificationSubtitle.textContent = 'You are all caught up';
    }

    if (notificationDot) {
        notificationDot.remove();
    }

    if (markAllBtn) {
        markAllBtn.disabled = true;
        markAllBtn.textContent = 'All caught up';
    }

    showToast('All notifications marked as read.', 'info', 2200);
}

function logoutUser() {
    fetch('../api/auth.php?action=logout', {
        method: 'GET',
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(res => res.json())
        .then(data => {
            if (data?.success) {
                window.location.href = data.redirect || '../index.php';
                return;
            }
            showToast(data?.message || 'Logout failed.', 'error');
        })
        .catch(() => {
            showToast('Logout failed.', 'error');
        });
}

// ---- Format helpers ----
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatCurrency(amount) {
    return '₱' + Number(amount).toLocaleString('en-PH');
}

// ---- Search filter (generic table) ----
function filterTable(inputId, bodyId) {
    const q = document.getElementById(inputId)?.value.toLowerCase() || '';
    document.querySelectorAll(`#${bodyId} tr`).forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// ---- Active nav highlight ----
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(item => {
        const href = item.getAttribute('href') || '';
        if (href && path.includes(href.split('?')[0].replace('./', ''))) {
            item.classList.add('active');
        }
    });
});
