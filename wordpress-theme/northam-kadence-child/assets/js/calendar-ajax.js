/**
 * Calendar AJAX Navigation
 *
 * Smooth page transitions for events calendar filters, tabs, and week navigation
 * Uses vanilla Fetch API - no dependencies
 */

(function() {
    'use strict';

    // Configuration
    const TRANSITION_DURATION = 250; // ms
    const CONTENT_SELECTOR = '.northam-archive-content';
    const TABS_SELECTOR = '.northam-view-tabs';

    // Elements to intercept clicks on
    const LINK_SELECTORS = [
        '.northam-filter-btn',           // Category filters
        '.northam-view-tab',             // What's On / Classes tabs
        '.northam-calendar-nav-btn',     // Previous/Next week arrows
        '.northam-seasonal-btn',         // Christmas seasonal section
        '.northam-category-select',      // Mobile category dropdown
        '.northam-venue-select'          // Venue dropdown
    ].join(', ');

    /**
     * Initialize AJAX navigation
     */
    function init() {
        // Intercept link clicks
        document.addEventListener('click', handleLinkClick);

        // Intercept select changes
        document.addEventListener('change', handleSelectChange);

        // Handle browser back/forward
        window.addEventListener('popstate', handlePopState);
    }

    /**
     * Handle clicks on filter/nav links
     */
    function handleLinkClick(e) {
        const link = e.target.closest('a');
        if (!link) return;

        // Check if this is a link we should intercept
        const shouldIntercept = link.matches(LINK_SELECTORS);
        if (!shouldIntercept) return;

        // Get the URL
        const url = link.getAttribute('href');
        if (!url || url === '#') return;

        // Prevent default navigation
        e.preventDefault();

        // Load new content
        loadContent(url, true);
    }

    /**
     * Handle select dropdown changes
     */
    function handleSelectChange(e) {
        const select = e.target;
        if (!select.matches(LINK_SELECTORS)) return;

        const url = select.value;
        if (!url) return;

        // Load new content
        loadContent(url, true);
    }

    /**
     * Handle browser back/forward buttons
     */
    function handlePopState(e) {
        // Load content without pushing to history (already there)
        loadContent(window.location.href, false);
    }

    /**
     * Load content via AJAX and swap it in smoothly
     */
    async function loadContent(url, pushState = true) {
        const contentContainer = document.querySelector(CONTENT_SELECTOR);
        if (!contentContainer) {
            // Fallback: if container not found, do normal navigation
            window.location.href = url;
            return;
        }

        try {
            // Add loading state
            contentContainer.style.opacity = '0.5';
            contentContainer.style.pointerEvents = 'none';

            // Fetch new page
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');

            const html = await response.text();

            // Parse the HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extract new content
            const newContent = doc.querySelector(CONTENT_SELECTOR);
            if (!newContent) throw new Error('Content not found in response');

            // Extract new tabs (to update active state)
            const newTabs = doc.querySelector(TABS_SELECTOR);

            // Smooth transition
            await transitionContent(contentContainer, newContent);

            // Update tabs if they exist (updates active state)
            if (newTabs) {
                const currentTabs = document.querySelector(TABS_SELECTOR);
                if (currentTabs) {
                    currentTabs.innerHTML = newTabs.innerHTML;
                }
            }

            // Update URL if needed
            if (pushState) {
                window.history.pushState({}, '', url);
            }

        } catch (error) {
            console.error('Error loading content:', error);
            // Fallback to normal navigation on error
            window.location.href = url;
        }
    }

    /**
     * Smoothly transition between old and new content
     */
    function transitionContent(oldContainer, newContent) {
        return new Promise((resolve) => {
            // Fade out
            oldContainer.style.transition = `opacity ${TRANSITION_DURATION}ms ease-out`;
            oldContainer.style.opacity = '0';

            setTimeout(() => {
                // Replace content
                oldContainer.innerHTML = newContent.innerHTML;

                // Reset pointer events
                oldContainer.style.pointerEvents = '';

                // Fade in
                // Use requestAnimationFrame to ensure the opacity=0 is applied before fade in
                requestAnimationFrame(() => {
                    oldContainer.style.opacity = '1';

                    setTimeout(() => {
                        // Clean up inline styles
                        oldContainer.style.transition = '';
                        resolve();
                    }, TRANSITION_DURATION);
                });
            }, TRANSITION_DURATION);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
