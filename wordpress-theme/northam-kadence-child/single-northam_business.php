<?php
/**
 * Single Business Template
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) :
    the_post();

    // Get business meta
    $phone = get_post_meta( get_the_ID(), '_northam_phone', true );
    $address = get_post_meta( get_the_ID(), '_northam_address', true );
    $website = get_post_meta( get_the_ID(), '_northam_website', true );
    $email = get_post_meta( get_the_ID(), '_northam_email', true );
    $opening_hours = get_post_meta( get_the_ID(), '_northam_opening_hours', true );
    $facebook = get_post_meta( get_the_ID(), '_northam_facebook', true );
    $instagram = get_post_meta( get_the_ID(), '_northam_instagram', true );
    $twitter = get_post_meta( get_the_ID(), '_northam_twitter', true );
    $map_url = get_post_meta( get_the_ID(), '_northam_map_url', true );

    // Accessibility
    $wheelchair = get_post_meta( get_the_ID(), '_northam_wheelchair', true );
    $parking = get_post_meta( get_the_ID(), '_northam_parking', true );
    $dog_friendly = get_post_meta( get_the_ID(), '_northam_dog_friendly', true );

    // Specialties (comma-separated)
    $specialties_raw = get_post_meta( get_the_ID(), '_northam_specialties', true );
    $specialties = $specialties_raw ? array_map( 'trim', explode( ',', $specialties_raw ) ) : array();

    // Get categories
    $categories = get_the_terms( get_the_ID(), 'business_category' );
    $category = $categories && ! is_wp_error( $categories ) ? $categories[0] : null;
    $category_slug = $category ? $category->slug : 'general';

    // Category colors
    $category_colors = array(
        'food-drink' => 'bg-event-venue',
        'pubs' => 'bg-event-venue',
        'restaurants' => 'bg-event-venue',
        'cafes' => 'bg-event-venue',
        'beauty-grooming' => 'bg-event-business',
        'hair-beauty' => 'bg-event-business',
        'services' => 'bg-event-business',
    );
    $badge_class = isset( $category_colors[ $category_slug ] ) ? $category_colors[ $category_slug ] : 'bg-primary';
    $has_multiple_categories = $categories && ! is_wp_error( $categories ) && count( $categories ) > 1;

    // Parse opening hours for today
    $days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
    $today = $days[ date( 'w' ) ];
    $hours_array = $opening_hours ? json_decode( $opening_hours, true ) : null;
    $today_hours = null;
    if ( is_array( $hours_array ) && isset( $hours_array[ strtolower( $today ) ] ) ) {
        $day_data = $hours_array[ strtolower( $today ) ];
        if ( is_array( $day_data ) && isset( $day_data['from'] ) ) {
            if ( $day_data['from'] === 'closed' ) {
                $today_hours = 'Closed';
            } else {
                $from_formatted = date( 'g:i A', strtotime( $day_data['from'] ) );
                $to_formatted = ! empty( $day_data['to'] ) ? date( 'g:i A', strtotime( $day_data['to'] ) ) : '';
                $today_hours = $to_formatted ? $from_formatted . ' - ' . $to_formatted : $from_formatted;
            }
        } elseif ( is_string( $day_data ) ) {
            // Legacy support for old format
            $today_hours = $day_data;
        }
    }
?>

<!-- Hero Section -->
<section class="northam-business-hero">
    <?php
    // Use featured image if set, otherwise use first gallery image
    $hero_image_url = '';
    if ( has_post_thumbnail() ) {
        $hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
    } else {
        $gallery_images = northam_get_gallery( get_the_ID() );
        if ( ! empty( $gallery_images ) ) {
            $hero_image_url = $gallery_images[0]['url'];
        }
    }
    ?>
    <?php if ( $hero_image_url ) : ?>
        <div class="northam-business-hero-image" style="background-image: url('<?php echo esc_url( $hero_image_url ); ?>')"></div>
    <?php else : ?>
        <div class="northam-business-hero-image northam-business-hero-placeholder"></div>
    <?php endif; ?>
    <div class="northam-business-hero-overlay"></div>

    <!-- Back Button -->
    <div class="northam-business-back">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'northam_business' ) ); ?>" class="northam-btn-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back to Directory
        </a>
    </div>

    <!-- Hero Content -->
    <div class="northam-business-hero-content">
        <div class="northam-container">
            <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                <div class="northam-categories-badges">
                    <?php foreach ( $categories as $single_cat ) :
                        $single_cat_slug = $single_cat->slug;
                        $single_badge_class = isset( $category_colors[ $single_cat_slug ] ) ? $category_colors[ $single_cat_slug ] : 'bg-primary';
                    ?>
                        <span class="northam-badge <?php echo esc_attr( $single_badge_class ); ?>">
                            <?php echo esc_html( $single_cat->name ); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="northam-business-title"><?php the_title(); ?></h1>

            <!-- Accessibility Indicators -->
            <div class="northam-accessibility-badges">
                <?php if ( $wheelchair ) : ?>
                    <span class="northam-accessibility-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 8v4l2 2"></path>
                        </svg>
                        Wheelchair Accessible
                    </span>
                <?php endif; ?>
                <?php if ( $parking ) : ?>
                    <span class="northam-accessibility-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <path d="M9 17V7h4a3 3 0 0 1 0 6H9"></path>
                        </svg>
                        Parking Available
                    </span>
                <?php endif; ?>
                <?php if ( $dog_friendly ) : ?>
                    <span class="northam-accessibility-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"></path>
                            <path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5"></path>
                            <path d="M8 14v.5"></path>
                            <path d="M16 14v.5"></path>
                            <path d="M11.25 16.25h1.5L12 17l-.75-.75Z"></path>
                            <path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"></path>
                        </svg>
                        Dog Friendly
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="northam-business-content">
    <div class="northam-container">
        <div class="northam-business-grid">
            <!-- Left Column - Main Content -->
            <div class="northam-business-main">
                <!-- About Section -->
                <div class="northam-business-about">
                    <h2>About <?php the_title(); ?></h2>
                    <div class="northam-business-description">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( ! empty( $specialties ) ) : ?>
                        <div class="northam-specialties">
                            <?php foreach ( $specialties as $specialty ) : ?>
                                <span class="northam-badge-secondary"><?php echo esc_html( $specialty ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Photo Gallery Section -->
                <?php
                $gallery_images = northam_get_gallery( get_the_ID() );

                // If no featured image is set, skip the first gallery image (it's used as hero)
                if ( ! has_post_thumbnail() && ! empty( $gallery_images ) ) {
                    array_shift( $gallery_images ); // Remove first image
                }

                if ( ! empty( $gallery_images ) ) :
                ?>
                    <div class="northam-gallery-section">
                        <h2>
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-accent">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            Photo Gallery
                        </h2>

                        <div class="northam-gallery-grid">
                            <?php foreach ( $gallery_images as $image ) : ?>
                                <div class="northam-gallery-item">
                                    <img src="<?php echo esc_url( $image['url'] ); ?>"
                                         alt="<?php echo esc_attr( $image['alt'] ?: get_the_title() ); ?>"
                                         loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Get events at this business if Events Manager is active
                $events = array();
                $week_offset = isset( $_GET['week'] ) ? absint( $_GET['week'] ) : 0;

                if ( class_exists( 'EM_Events' ) ) {
                    // Calculate start and end of the week to display
                    $current_time = current_time( 'timestamp' );
                    $week_start = strtotime( 'monday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 );
                    $week_end = strtotime( 'sunday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

                    // Get all future events using Events Manager API
                    $all_events = EM_Events::get( array(
                        'scope' => 'future',
                        'status' => 1,
                    ) );

                    // Filter events to only those linked to this business and in the current week
                    if ( ! empty( $all_events ) ) {
                        foreach ( $all_events as $em_event ) {
                            // Check if this event's post has our meta field
                            $venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );

                            if ( $venue_id == get_the_ID() ) {
                                // Check if event is within the current week
                                if ( $em_event->start >= $week_start && $em_event->start <= $week_end ) {
                                    $events[] = $em_event;
                                }
                            }
                        }
                    }
                }

                // Show events section if there are events OR for debugging
                if ( ! empty( $events ) ) :
                    $week_start_date = date_i18n( 'j M', $week_start );
                    $week_end_date = date_i18n( 'j M', $week_end );
                ?>
                <!-- Upcoming Events Section -->
                <div id="events" class="northam-events-section">
                    <div class="northam-events-header">
                        <h2>
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <?php
                            if ( $week_offset === 0 ) {
                                echo esc_html__( 'This Week\'s Events', 'northam' );
                            } else {
                                echo esc_html( sprintf( __( 'Events: %s - %s', 'northam' ), $week_start_date, $week_end_date ) );
                            }
                            ?>
                        </h2>
                        <div class="northam-events-pagination">
                            <?php if ( $week_offset > 0 ) : ?>
                                <button class="northam-events-nav northam-events-prev" data-week="<?php echo esc_attr( max( 0, $week_offset - 1 ) ); ?>">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    Previous Week
                                </button>
                            <?php endif; ?>
                            <button class="northam-events-nav northam-events-next" data-week="<?php echo esc_attr( $week_offset + 1 ); ?>">
                                Next Week
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="northam-events-grid-small">
                        <?php foreach ( $events as $event ) :
                            $map_url = get_post_meta( $event->post_id, '_northam_event_map_url', true );
                        ?>
                            <div class="northam-event-card-small">
                                <div class="northam-event-card-image">
                                    <?php if ( has_post_thumbnail( $event->post_id ) ) : ?>
                                        <?php echo get_the_post_thumbnail( $event->post_id, 'thumbnail' ); ?>
                                    <?php else : ?>
                                        <div class="northam-event-placeholder"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="northam-event-card-content">
                                    <h3><?php echo esc_html( $event->event_name ); ?></h3>
                                    <p>
                                        <?php echo esc_html( date_i18n( 'D, jS M', strtotime( $event->event_start_date ) ) ); ?>
                                        at <?php echo esc_html( date_i18n( 'g:i A', strtotime( $event->event_start_time ) ) ); ?>
                                    </p>
                                    <?php if ( $map_url ) : ?>
                                        <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-map-link northam-map-link-small">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            View on Map
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="northam-business-sidebar">
                <div class="northam-business-sidebar-card">
                    <!-- Today's Hours Highlight -->
                    <?php if ( $today_hours || $opening_hours ) : ?>
                        <div class="northam-hours-today">
                            <div class="northam-hours-today-label">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Today's Hours
                            </div>
                            <p class="northam-hours-today-value">
                                <?php
                                if ( $today_hours ) {
                                    if ( strtolower( $today_hours ) === 'closed' ) {
                                        echo 'Closed';
                                    } else {
                                        echo esc_html( $today_hours );
                                    }
                                } else {
                                    echo 'See below';
                                }
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Full Hours -->
                    <?php if ( $opening_hours ) : ?>
                        <div class="northam-hours-full">
                            <h3>Opening Hours</h3>
                            <?php if ( is_array( $hours_array ) ) : ?>
                                <ul class="northam-hours-list">
                                    <?php
                                    $day_order = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
                                    foreach ( $day_order as $day ) :
                                        $day_data = isset( $hours_array[ $day ] ) ? $hours_array[ $day ] : null;
                                        $is_today = strtolower( $today ) === $day;

                                        // Format hours for display
                                        $display_hours = 'Closed';
                                        if ( is_array( $day_data ) && isset( $day_data['from'] ) ) {
                                            if ( $day_data['from'] === 'closed' ) {
                                                $display_hours = 'Closed';
                                            } else {
                                                $from_formatted = date( 'g:i A', strtotime( $day_data['from'] ) );
                                                $to_formatted = ! empty( $day_data['to'] ) ? date( 'g:i A', strtotime( $day_data['to'] ) ) : '';
                                                $display_hours = $to_formatted ? $from_formatted . ' - ' . $to_formatted : $from_formatted;
                                            }
                                        } elseif ( is_string( $day_data ) && ! empty( $day_data ) ) {
                                            // Legacy support for old format
                                            $display_hours = $day_data;
                                        }
                                    ?>
                                        <li class="<?php echo $is_today ? 'is-today' : ''; ?>">
                                            <span class="day"><?php echo esc_html( ucfirst( $day ) ); ?></span>
                                            <span class="hours"><?php echo esc_html( $display_hours ); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p><?php echo esc_html( $opening_hours ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Details -->
                    <div class="northam-contact-details">
                        <?php if ( $phone ) : ?>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>" class="northam-contact-item northam-contact-clickable">
                                <div class="northam-contact-icon bg-primary-light">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <span><?php echo esc_html( $phone ); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if ( $address ) : ?>
                            <div class="northam-contact-item">
                                <div class="northam-contact-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <span><?php echo esc_html( $address ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( $website ) : ?>
                            <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer" class="northam-contact-item northam-contact-clickable">
                                <div class="northam-contact-icon bg-primary-light">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    </svg>
                                </div>
                                <span class="truncate"><?php echo esc_html( preg_replace( '#^https?://#', '', $website ) ); ?></span>
                            </a>
                        <?php endif; ?>

                        <!-- Social Links -->
                        <?php if ( $instagram || $facebook ) : ?>
                            <div class="northam-social-links">
                                <?php if ( $instagram ) :
                                    $instagram_url = strpos( $instagram, 'http' ) === 0 ? $instagram : 'https://instagram.com/' . $instagram;
                                ?>
                                    <a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-social-link northam-social-instagram" aria-label="Instagram">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if ( $facebook ) :
                                    $facebook_url = strpos( $facebook, 'http' ) === 0 ? $facebook : 'https://facebook.com/' . $facebook;
                                ?>
                                    <a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-social-link northam-social-facebook" aria-label="Facebook">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Map -->
                    <?php if ( $address || $map_url ) : ?>
                        <div class="northam-map-static">
                            <div class="northam-map-bg"></div>
                            <div class="northam-map-overlay">
                                <?php if ( $address ) : ?>
                                    <div class="northam-map-address">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span><?php echo esc_html( $address ); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $map_url ) : ?>
                                    <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-map-btn">
                                        View on Google Maps
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
endwhile;

get_footer();
