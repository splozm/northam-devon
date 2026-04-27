/**
 * Northam Custom Header - Mobile Menu Toggle
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.querySelector('.northam-mobile-toggle');
        const mobileNav = document.querySelector('.northam-mobile-nav');

        if (toggle && mobileNav) {
            toggle.addEventListener('click', function() {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !isExpanded);
                mobileNav.setAttribute('aria-hidden', isExpanded);
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !mobileNav.contains(e.target)) {
                    toggle.setAttribute('aria-expanded', 'false');
                    mobileNav.setAttribute('aria-hidden', 'true');
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    mobileNav.setAttribute('aria-hidden', 'true');
                }
            });
        }
    });
})();
