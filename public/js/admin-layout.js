// Admin Layout - Essential JavaScript only
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    // Mobile sidebar toggle (hamburger menu)
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
        });
    }

    // Close sidebar when clicking overlay
    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Close sidebar on nav link click (mobile only)
    if (sidebar) {
        document.addEventListener('click', function(e) {
            const navLink = e.target.closest('.sidebar .nav-link:not([data-bs-toggle])');
            if (navLink && window.innerWidth < 992) {
                sidebar.classList.remove('show');
                if (sidebarOverlay) sidebarOverlay.classList.remove('show');
            }
        });
    }

    // Profile dropdown mobile handling
    const profileDropdown = document.querySelector('.profile-dropdown');
    const profileDropdownToggle = document.getElementById('navbarDropdown');
    const profileDropdownBackdrop = document.querySelector('.profile-dropdown-backdrop');

    if (profileDropdown && profileDropdownToggle && window.innerWidth < 992) {
        profileDropdown.addEventListener('show.bs.dropdown', () => profileDropdown.classList.add('show'));
        profileDropdown.addEventListener('shown.bs.dropdown', () => document.body.style.overflow = 'hidden');
        profileDropdown.addEventListener('hide.bs.dropdown', () => {
            profileDropdown.classList.remove('show');
            document.body.style.overflow = '';
        });

        if (profileDropdownBackdrop) {
            profileDropdownBackdrop.addEventListener('click', () => {
                bootstrap.Dropdown.getInstance(profileDropdownToggle)?.hide();
            });
        }

        // Close on menu item click
        profileDropdown.addEventListener('click', (e) => {
            if (e.target.closest('.dropdown-item')) {
                setTimeout(() => bootstrap.Dropdown.getInstance(profileDropdownToggle)?.hide(), 100);
            }
        });
    }
});
