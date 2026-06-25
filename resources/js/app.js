document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const toggle = document.getElementById('sidebar-toggle');

    if (! sidebar || ! toggle) {
        return;
    }

    const storageKey = 'yacht-dealers-sidebar-collapsed';
    const iconCollapse = toggle.querySelector('[data-icon="collapse"]');
    const iconExpand = toggle.querySelector('[data-icon="expand"]');

    const setCollapsed = (collapsed) => {
        sidebar.classList.toggle('sidebar-collapsed', collapsed);
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
        iconCollapse?.classList.toggle('hidden', collapsed);
        iconExpand?.classList.toggle('hidden', ! collapsed);
        localStorage.setItem(storageKey, collapsed ? '1' : '0');
    };

    if (localStorage.getItem(storageKey) === '1') {
        setCollapsed(true);
    }

    toggle.addEventListener('click', () => {
        setCollapsed(! sidebar.classList.contains('sidebar-collapsed'));
    });
});
