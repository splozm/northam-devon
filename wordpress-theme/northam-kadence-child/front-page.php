<?php
/**
 * Front Page Template
 *
 * Displays the designed homepage for Northam Devon.
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();
?>

<!-- Hero Section -->
<section class="northam-hero">
    <div class="northam-hero-overlay"></div>

    <!-- Wave decoration -->
    <div class="northam-hero-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 60L48 55C96 50 192 40 288 45C384 50 480 70 576 75C672 80 768 70 864 60C960 50 1056 40 1152 45C1248 50 1344 70 1392 80L1440 90V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0V60Z" fill="hsl(50, 30%, 97%)" fill-opacity="0.5"/>
            <path d="M0 80L48 75C96 70 192 60 288 65C384 70 480 90 576 95C672 100 768 90 864 80C960 70 1056 60 1152 65C1248 70 1344 90 1392 100L1440 110V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0V80Z" fill="hsl(50, 30%, 97%)"/>
        </svg>
    </div>

    <div class="northam-hero-content northam-animate-fade-in">
        <span class="northam-hero-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"/>
                <path d="M7 12L12 12M12 12L17 12M12 12V7M12 12V17"/>
            </svg>
            Welcome to
        </span>
        <h1 class="northam-hero-title">Northam, Devon</h1>
        <p class="northam-hero-subtitle">A charming coastal village where community spirit meets Devon's stunning shores</p>
        <div class="northam-hero-actions">
            <a href="<?php echo esc_url( home_url( '/events/' ) ); ?>" class="northam-btn northam-btn-accent northam-btn-lg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Explore Events
            </a>
            <a href="<?php echo esc_url( home_url( '/things-to-do/' ) ); ?>" class="northam-btn northam-btn-outline northam-btn-lg" style="border-color: rgba(255,255,255,0.5); color: white;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                </svg>
                Discover Northam
            </a>
        </div>
    </div>
</section>

<!-- Quick Stats Section -->
<section class="northam-section-sm" style="background-color: var(--northam-sand-warm); background-color: hsl(45, 55%, 80%, 0.5);">
    <div class="northam-container">
        <div class="northam-stats">
            <?php
            // Get dynamic counts
            $event_count = class_exists( 'EM_Events' ) ? wp_count_posts( 'event' )->publish : '12+';
            $business_count = wp_count_posts( 'northam_business' )->publish ?: '45+';
            $group_count = wp_count_posts( 'northam_group' )->publish ?: '8';
            $attraction_count = wp_count_posts( 'northam_attraction' )->publish ?: '20+';

            $stats = array(
                array( 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>', 'value' => $event_count ?: '12+', 'label' => 'Upcoming Events' ),
                array( 'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>', 'value' => $business_count ?: '45+', 'label' => 'Local Businesses' ),
                array( 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>', 'value' => $group_count ?: '8', 'label' => 'Community Groups' ),
                array( 'icon' => '<circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>', 'value' => $attraction_count ?: '20+', 'label' => 'Things to Do' ),
            );

            foreach ( $stats as $stat ) : ?>
                <div class="northam-stat">
                    <div class="northam-stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php echo $stat['icon']; ?>
                        </svg>
                    </div>
                    <div class="northam-stat-value"><?php echo esc_html( $stat['value'] ); ?></div>
                    <div class="northam-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="northam-section">
    <div class="northam-container">
        <div class="northam-section-header">
            <div>
                <span class="northam-section-eyebrow">What's Happening</span>
                <h2 class="northam-section-title">Upcoming Events</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/events/' ) ); ?>" class="northam-btn northam-btn-outline">
                View All Events and Classes
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <?php if ( class_exists( 'EM_Events' ) ) :
            $events = EM_Events::get( array(
                'limit'   => 3,
                'scope'   => 'future',
                'orderby' => 'event_start',
                'order'   => 'ASC',
            ) );

            if ( ! empty( $events ) ) : ?>
                <div class="northam-events-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <?php foreach ( $events as $event ) :
                        $categories = get_the_terms( $event->post_id, 'event-categories' );
                        $event_type = 'venue';
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            $cat_slug = $categories[0]->slug;
                            if ( strpos( $cat_slug, 'business' ) !== false ) {
                                $event_type = 'business';
                            } elseif ( strpos( $cat_slug, 'community' ) !== false ) {
                                $event_type = 'community';
                            }
                        }
                        $location = $event->get_location();
                        $map_url = get_post_meta( $event->post_id, '_northam_event_map_url', true );
                        $location_name = $location && $location->location_id ? $location->location_name : '';
                    ?>
                        <article class="northam-card northam-event-card" data-event-type="<?php echo esc_attr( $event_type ); ?>">
                            <div class="northam-card-body">
                                <span class="northam-badge northam-badge-<?php echo esc_attr( $event_type ); ?>">
                                    <?php echo esc_html( ucfirst( $event_type ) ); ?> Event
                                </span>
                                <h3 class="northam-listing-title" style="margin-top: 0.75rem;">
                                    <?php echo esc_html( $event->event_name ); ?>
                                </h3>
                                <div class="northam-event-meta">
                                    <div class="northam-event-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <?php echo esc_html( date_i18n( 'l, jS F', strtotime( $event->event_start_date ) ) ); ?>
                                    </div>
                                    <div class="northam-event-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <?php echo esc_html( date_i18n( 'g:i A', strtotime( $event->event_start_time ) ) ); ?>
                                    </div>
                                    <?php if ( $location_name ) : ?>
                                    <div class="northam-event-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <?php if ( $map_url ) : ?>
                                            <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-map-link">
                                                <?php echo esc_html( $location_name ); ?>
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html( $location_name ); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-muted"><?php esc_html_e( 'No upcoming events scheduled. Check back soon!', 'northam' ); ?></p>
            <?php endif;
        else : ?>
            <p class="text-muted"><?php esc_html_e( 'Events calendar coming soon.', 'northam' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Businesses Section -->
<section class="northam-section" style="background-color: var(--northam-muted); background-color: hsl(50, 20%, 90%, 0.3);">
    <div class="northam-container">
        <div class="northam-section-header">
            <div>
                <span class="northam-section-eyebrow">Local Directory</span>
                <h2 class="northam-section-title">Featured Businesses</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/business/' ) ); ?>" class="northam-btn northam-btn-outline">
                Browse Directory
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <?php
        $businesses = new WP_Query( array(
            'post_type'      => 'northam_business',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
        ) );

        if ( $businesses->have_posts() ) : ?>
            <div class="northam-directory-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php while ( $businesses->have_posts() ) : $businesses->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="northam-card northam-listing-card northam-listing-card-link northam-hover-lift">
                        <div class="northam-listing-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'northam-card' ); ?>
                            <?php else : ?>
                                <svg class="northam-listing-placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="northam-listing-content">
                            <?php
                            $categories = get_the_terms( get_the_ID(), 'business_category' );
                            if ( $categories && ! is_wp_error( $categories ) ) : ?>
                                <span class="northam-listing-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                            <?php endif; ?>
                            <h3 class="northam-listing-title"><?php the_title(); ?></h3>
                            <?php if ( has_excerpt() ) : ?>
                                <p class="northam-listing-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <?php endif; ?>
                            <div class="northam-listing-footer">
                                <span class="northam-listing-link">
                                    <?php esc_html_e( 'View Details', 'northam' ); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata();
        else : ?>
            <p class="text-muted"><?php esc_html_e( 'Businesses coming soon. Check back later!', 'northam' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Community CTA Section -->
<section class="northam-section">
    <div class="northam-container">
        <div class="northam-cta-banner">
            <div class="northam-cta-overlay"></div>
            <div class="northam-cta-content">
                <span class="northam-cta-badge">Get Involved</span>
                <h2 class="northam-cta-title">Join Our Community</h2>
                <p class="northam-cta-text">
                    From the WI to sports clubs, Northam has a vibrant community waiting to welcome you.
                    Discover local groups and get involved in village life.
                </p>
                <div class="northam-cta-actions">
                    <a href="<?php echo esc_url( home_url( '/community-group/' ) ); ?>" class="northam-btn northam-btn-accent northam-btn-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Explore Groups
                    </a>
                    <a href="https://www.facebook.com/groups/262287480518567/" class="northam-btn northam-btn-outline northam-btn-lg" style="border-color: rgba(255,255,255,0.5); color: white;" target="_blank" rel="noopener noreferrer">
                        Our History
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="northam-section-sm" style="background-color: var(--northam-sand-warm); background-color: hsl(45, 55%, 80%, 0.5);">
    <div class="northam-container" style="max-width: 600px; text-align: center;">
        <h2 style="font-size: var(--northam-text-2xl); margin-bottom: 1rem;">Stay in the Loop</h2>
        <p class="text-muted" style="margin-bottom: 1.5rem;">
            Get weekly updates on events, news, and community happenings in Northam.
        </p>
        <!-- Newsletter Signup Form -->
        <div data-fs-success style="color: var(--northam-primary); font-weight: 500; display: none;"></div>
        <div data-fs-error style="color: #dc3545; display: none;"></div>

        <form id="newsletter-form" style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 400px; margin: 0 auto;">
            <input type="hidden" name="form_type" value="newsletter" />
            <input type="hidden" name="page_url" id="newsletter-page-url" />
            <input type="hidden" name="page_title" id="newsletter-page-title" />
            <input type="text" name="_gotcha" style="display: none;" />

            <input type="email" name="email" placeholder="Your email address" class="northam-form-input" style="text-align: center;" required data-fs-field />
            <span data-fs-error="email" style="color: #dc3545; font-size: 0.875rem;"></span>

            <button type="submit" class="northam-btn northam-btn-primary" data-fs-submit-btn>Subscribe</button>
        </form>

        <script>
            document.getElementById('newsletter-page-url').value = window.location.href;
            document.getElementById('newsletter-page-title').value = document.title;
            window.formspree = window.formspree || function () { (formspree.q = formspree.q || []).push(arguments); };
            formspree('initForm', { formElement: '#newsletter-form', formId: 'xgorqwyn' });
        </script>
        <script src="https://unpkg.com/@formspree/ajax@1" defer></script>
    </div>
</section>

<?php
get_footer();
