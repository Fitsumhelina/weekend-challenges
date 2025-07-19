// public/js/sidebar.js

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('menu-search');
    const menuItems = document.querySelectorAll('#side-menu li');
    const searchBox = document.getElementById('sidebar-search-box');
    const verticalMenu = document.querySelector('.vertical-menu');
    const verticalMenuBtn = document.querySelector('.vertical-menu-btn');

    function highlightText(text, searchTerm) {
        if (!searchTerm) return text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }

    function matchesSearch(item, searchTerm) {
        const text = item.textContent.toLowerCase();
        return text.includes(searchTerm.toLowerCase());
    }

    function handleSearch() {
        const searchTerm = searchInput.value.trim();

        menuItems.forEach(item => {
            const textElement = item.querySelector('span');
            const linkElement = item.querySelector('a');

            if (!textElement || !linkElement) return;

            const originalText = textElement.textContent;
            textElement.innerHTML = originalText;

            if (searchTerm) {
                const matches = matchesSearch(item, searchTerm);
                const childMatches = Array.from(item.querySelectorAll('li'))
                    .some(child => matchesSearch(child, searchTerm));

                if (matches || childMatches) {
                    item.style.display = '';
                    textElement.innerHTML = highlightText(originalText, searchTerm);
                } else {
                    item.style.display = 'none';
                }
            } else {
                item.style.display = '';
                textElement.innerHTML = originalText;
            }
        });
    }

    function handleSidebarState() {
        const isCollapsed = verticalMenu.classList.contains('collapsed');
        if (isCollapsed) {
            searchBox.style.display = 'none';
            searchInput.value = '';
            handleSearch();
        } else {
            searchBox.style.display = 'block';
        }
    }

    if (verticalMenuBtn) {
        verticalMenuBtn.addEventListener('click', () => setTimeout(handleSidebarState, 50));
    }

    window.addEventListener('resize', handleSidebarState);

    document.querySelector('.search-box').addEventListener('click', e => {
        if (e.target !== searchInput) searchInput.focus();
    });

    searchInput.addEventListener('click', e => e.stopPropagation());
    searchInput.addEventListener('input', handleSearch);
    searchInput.addEventListener('keyup', e => {
        if (e.key === 'Escape') {
            searchInput.value = '';
            handleSearch();
        }
    });

    if (typeof MetisMenu !== 'undefined') {
        new MetisMenu('#side-menu');
    }

    searchInput.value = '';
});
