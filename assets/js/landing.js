/* AssetTrack — Landing Page JS */

const VIEWER_PASSCODE = '123456';

function goToLogin() {
    window.location.href = 'pages/login.php';
}

function openViewerModal() {
    const modal = document.getElementById('viewerModal');
    modal.classList.add('active');
    setTimeout(() => {
        document.querySelector('.passcode-dot[data-index="0"]').focus();
    }, 100);
    resetPasscode();
}

function closeViewerModal(e) {
    if (e && e.target !== document.getElementById('viewerModal')) return;
    document.getElementById('viewerModal').classList.remove('active');
    resetPasscode();
}

function resetPasscode() {
    document.querySelectorAll('.passcode-dot').forEach(el => {
        el.value = '';
        el.classList.remove('filled', 'error');
    });
    document.getElementById('modalError').textContent = '';
}

function confirmPasscode() {
    const dots = document.querySelectorAll('.passcode-dot');
    const entered = Array.from(dots).map(d => d.value).join('');
    if (entered.length < 6) {
        showError('Please enter the 6-digit passcode.');
        return;
    }
    if (entered === VIEWER_PASSCODE) {
        window.location.href = 'pages/dashboard.php?role=viewer';
    } else {
        showError('Incorrect passcode. Please try again.');
        dots.forEach(d => { d.classList.add('error'); d.value = ''; });
        dots[0].focus();
    }
}

function showError(msg) {
    document.getElementById('modalError').textContent = msg;
}

// Passcode input behavior
document.addEventListener('DOMContentLoaded', () => {
    const dots = document.querySelectorAll('.passcode-dot');
    dots.forEach((dot, i) => {
        dot.addEventListener('input', function () {
            const val = this.value.replace(/\D/g, '');
            this.value = val.slice(-1);
            if (val) {
                this.classList.add('filled');
                this.classList.remove('error');
                if (i < dots.length - 1) dots[i + 1].focus();
                // Auto-submit when last digit filled
                if (i === dots.length - 1) {
                    setTimeout(confirmPasscode, 80);
                }
            } else {
                this.classList.remove('filled');
            }
        });

        dot.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                dots[i - 1].value = '';
                dots[i - 1].classList.remove('filled');
                dots[i - 1].focus();
            }
            if (e.key === 'Enter') confirmPasscode();
        });
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('viewerModal').classList.remove('active');
            resetPasscode();
        }
    });
});
