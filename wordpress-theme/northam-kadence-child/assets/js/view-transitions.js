/**
 * View Transitions - Scroll Position Preservation
 *
 * Maintains scroll position during View Transitions API navigation
 * for filter changes and tab switches on events calendar
 */

(function() {
    'use strict';

    // Check if View Transitions API is supported
    if (!document.startViewTransition) {
        return;
    }

    // Store scroll position before navigation
    function saveScrollPosition() {
        const scrollPos = window.scrollY;
        sessionStorage.setItem('northam_scroll_position', scrollPos.toString());
    }

    // Restore scroll position after navigation
    function restoreScrollPosition() {
        const savedPosition = sessionStorage.getItem('northam_scroll_position');
        if (savedPosition !== null) {
            const position = parseInt(savedPosition, 10);
            // Restore immediately without smooth scroll during transition
            window.scrollTo(0, position);
            sessionStorage.removeItem('northam_scroll_position');
        }
    }

    // Save scroll position when clicking internal links (filters, tabs, etc.)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;

        // Check if it's an internal link (same origin)
        try {
            const url = new URL(href, window.location.href);
            if (url.origin === window.location.origin) {
                // Save scroll position for same-page navigations (filters, tabs)
                saveScrollPosition();
            }
        } catch (e) {
            // Invalid URL, ignore
        }
    });

    // Restore scroll position when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreScrollPosition);
    } else {
        restoreScrollPosition();
    }

    // Also restore after view transition completes (belt and suspenders)
    window.addEventListener('pageshow', function(e) {
        // Only restore if this is a navigation (not initial load)
        if (e.persisted || performance.navigation.type === 1) {
            restoreScrollPosition();
        }
    });

})();
