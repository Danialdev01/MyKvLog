/* MyKvLog shared shell behaviour: sidebar toggle + toast feedback. */

(function () {
    let sidebarOpen = window.innerWidth >= 769;

    window.toggleSidebar = function () {
        const sb = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const overlay = document.getElementById('sidebar-overlay');

        if (window.innerWidth < 769) {
            sb.classList.toggle('open');
            overlay.style.display = sb.classList.contains('open') ? 'block' : 'none';
        } else {
            sidebarOpen = !sidebarOpen;
            sb.classList.toggle('collapsed', !sidebarOpen);
            main.classList.toggle('expanded', !sidebarOpen);
        }
    };

    window.closeSidebar = function () {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').style.display = 'none';
    };

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 769) {
            document.getElementById('sidebar-overlay').style.display = 'none';
            document.getElementById('sidebar').classList.remove('open');
            if (sidebarOpen) {
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('main').classList.remove('expanded');
            }
        }
    });

    /* showToast('Log disimpan!', 'success' | 'error' | 'info') — non-blocking feedback. */
    window.showToast = function (message, type) {
        type = type || 'info';
        const container = document.getElementById('toast-container');
        if (!container) { alert(message); return; }

        const toast = document.createElement('div');
        toast.className = 'toast ' + type;
        toast.setAttribute('role', 'status');
        toast.textContent = (type === 'success' ? '✅ ' : type === 'error' ? '⚠️ ' : '') + message;
        container.appendChild(toast);

        requestAnimationFrame(function () { toast.classList.add('show'); });
        setTimeout(function () {
            toast.classList.remove('show');
            setTimeout(function () { toast.remove(); }, 300);
        }, 3500);
    };
})();
