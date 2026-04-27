/**
 * Events Pagination with AJAX and Smooth Transitions
 *
 * Handles carousel-style week navigation without page reloads
 */
(function($) {
    'use strict';

    let currentWeek = 0;
    let isLoading = false;

    /**
     * Load events for a specific week
     */
    function loadWeekEvents(weekOffset, direction = 'none') {
        if (isLoading) return;

        isLoading = true;
        const $eventsSection = $('#events');
        const $eventsGrid = $('.northam-events-grid-small');
        const $heading = $eventsSection.find('h2');
        const $pagination = $('.northam-events-pagination');

        // Add loading class with slide direction
        if (direction !== 'none') {
            $eventsGrid.addClass('northam-events-loading-' + direction);
        }

        // AJAX request
        $.ajax({
            url: northamEvents.ajaxUrl,
            type: 'POST',
            data: {
                action: 'northam_load_weekly_events',
                nonce: northamEvents.nonce,
                business_id: northamEvents.businessId,
                week_offset: weekOffset
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    currentWeek = data.week_offset;

                    // Update heading
                    const calendarIcon = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>`;
                    $heading.html(calendarIcon + data.heading);

                    // Build events HTML
                    let eventsHtml = '';
                    if (data.events.length > 0) {
                        data.events.forEach(function(event) {
                            const thumbnail = event.thumbnail || '<div class="northam-event-placeholder"></div>';
                            const mapLink = event.map_url ?
                                `<a href="${event.map_url}" target="_blank" rel="noopener noreferrer" class="northam-map-link northam-map-link-small">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    View on Map
                                </a>` : '';

                            eventsHtml += `
                                <div class="northam-event-card-small">
                                    <div class="northam-event-card-image">
                                        ${thumbnail}
                                    </div>
                                    <div class="northam-event-card-content">
                                        <h3>${event.name}</h3>
                                        <p>${event.start_date} at ${event.start_time}</p>
                                        ${mapLink}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        eventsHtml = '<p class="northam-no-events">No events this week</p>';
                    }

                    // Animate out, update, animate in
                    setTimeout(function() {
                        $eventsGrid.html(eventsHtml);

                        // Update pagination buttons
                        updatePaginationButtons(data.week_offset);

                        // Remove loading class and trigger animation
                        setTimeout(function() {
                            $eventsGrid.removeClass('northam-events-loading-next northam-events-loading-prev');
                            isLoading = false;
                        }, 50);
                    }, 300);
                }
            },
            error: function() {
                console.error('Failed to load events');
                $eventsGrid.removeClass('northam-events-loading-next northam-events-loading-prev');
                isLoading = false;
            }
        });
    }

    /**
     * Update pagination button visibility and URLs
     */
    function updatePaginationButtons(weekOffset) {
        const $pagination = $('.northam-events-pagination');

        let buttonsHtml = '';

        // Previous week button (only show if week > 0)
        if (weekOffset > 0) {
            buttonsHtml += `
                <button class="northam-events-nav northam-events-prev" data-week="${weekOffset - 1}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    Previous Week
                </button>
            `;
        }

        // Next week button (always show)
        buttonsHtml += `
            <button class="northam-events-nav northam-events-next" data-week="${weekOffset + 1}">
                Next Week
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        `;

        $pagination.html(buttonsHtml);
    }

    /**
     * Initialize pagination on page load
     */
    function init() {
        const $eventsSection = $('#events');

        if ($eventsSection.length === 0) {
            return; // No events section on this page
        }

        // Get initial week from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        const weekParam = urlParams.get('week');
        if (weekParam) {
            currentWeek = parseInt(weekParam, 10);
        }

        // Event delegation for pagination buttons
        $(document).on('click', '.northam-events-nav', function(e) {
            e.preventDefault();

            const $button = $(this);
            const targetWeek = parseInt($button.data('week'), 10);
            const direction = $button.hasClass('northam-events-next') ? 'next' : 'prev';

            // Update URL without reload
            const newUrl = new URL(window.location);
            if (targetWeek === 0) {
                newUrl.searchParams.delete('week');
            } else {
                newUrl.searchParams.set('week', targetWeek);
            }
            newUrl.hash = '#events';
            window.history.pushState({}, '', newUrl);

            // Load new week
            loadWeekEvents(targetWeek, direction);

            // Smooth scroll to events section
            $('html, body').animate({
                scrollTop: $eventsSection.offset().top - 100
            }, 400);
        });
    }

    // Initialize when document is ready
    $(document).ready(init);

})(jQuery);
