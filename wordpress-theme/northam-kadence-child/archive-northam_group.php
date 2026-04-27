<?php
/**
 * Archive Template for Our Community (Community Groups + Community Venues)
 *
 * This template combines both Community Groups and Community Venues in a unified grid.
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();
?>

<!-- Hero Section -->
<section class="northam-hero northam-hero-community">
    <div class="northam-hero-overlay"></div>

    <div class="northam-hero-content northam-animate-fade-in">
        <span class="northam-hero-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Get Involved
        </span>
        <h1 class="northam-hero-title">Our Community</h1>
        <p class="northam-hero-subtitle">Join a club, meet neighbours, and become part of village life</p>
    </div>
</section>

<main id="main" class="site-main northam-archive-page">
    <div class="content-container entry-content">

        <?php
        // Query both Community Groups and Community Venues
        $community_query = new WP_Query( array(
            'post_type'      => array( 'northam_group', 'northam_venue' ),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );

        if ( $community_query->have_posts() ) : ?>
            <div class="northam-directory-grid">
                <?php while ( $community_query->have_posts() ) : $community_query->the_post();
                    $post_type = get_post_type();
                    $is_venue = ( $post_type === 'northam_venue' );

                    // Get metadata based on post type
                    if ( $is_venue ) {
                        // Venue meta
                        $address = get_post_meta( get_the_ID(), '_northam_address', true );
                        $phone = get_post_meta( get_the_ID(), '_northam_phone', true );
                        $email = get_post_meta( get_the_ID(), '_northam_email', true );
                        $website = get_post_meta( get_the_ID(), '_northam_website', true );
                        $capacity = get_post_meta( get_the_ID(), '_northam_capacity', true );
                        $facilities = get_post_meta( get_the_ID(), '_northam_facilities', true );

                        // Get venue types
                        $types = get_the_terms( get_the_ID(), 'venue_type' );
                        $type_label = $types && ! is_wp_error( $types ) ? $types[0]->name : 'Community Venue';
                        $badge_class = 'northam-badge-venue';
                    } else {
                        // Group meta
                        $contact_name = get_post_meta( get_the_ID(), '_northam_contact_name', true );
                        $contact_email = get_post_meta( get_the_ID(), '_northam_contact_email', true );
                        $contact_phone = get_post_meta( get_the_ID(), '_northam_contact_phone', true );
                        $meeting_time = get_post_meta( get_the_ID(), '_northam_meeting_time', true );
                        $meeting_location = get_post_meta( get_the_ID(), '_northam_meeting_location', true );
                        $website = get_post_meta( get_the_ID(), '_northam_website', true );
                        $facebook = get_post_meta( get_the_ID(), '_northam_facebook', true );

                        // Get group types
                        $types = get_the_terms( get_the_ID(), 'group_type' );
                        $type_label = $types && ! is_wp_error( $types ) ? $types[0]->name : 'Community Group';
                        $badge_class = 'northam-badge-group';
                    }
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'northam-card northam-listing-card northam-hover-lift northam-community-card' ); ?>>

                        <!-- Featured Image with Badge -->
                        <div class="northam-listing-image">
                            <?php
                            $card_link = $is_venue ? get_permalink() : ( $website ? $website : '' );
                            $card_target = $is_venue ? '' : ' target="_blank" rel="noopener noreferrer"';
                            ?>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php if ( $card_link ) : ?>
                                <a href="<?php echo esc_url( $card_link ); ?>"<?php echo $card_target; ?>>
                                    <?php the_post_thumbnail( 'northam-card' ); ?>
                                </a>
                                <?php else : ?>
                                    <?php the_post_thumbnail( 'northam-card' ); ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if ( $is_venue ) : ?>
                                    <svg class="northam-listing-placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    </svg>
                                <?php else : ?>
                                    <svg class="northam-listing-placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Type Badge on Image -->
                            <span class="northam-badge <?php echo esc_attr( $badge_class ); ?> northam-badge-overlay">
                                <?php echo esc_html( $type_label ); ?>
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="northam-listing-content">

                            <!-- Title -->
                            <h2 class="northam-listing-title">
                                <?php if ( $card_link ) : ?>
                                <a href="<?php echo esc_url( $card_link ); ?>"<?php echo $card_target; ?>><?php the_title(); ?></a>
                                <?php else : ?>
                                <?php the_title(); ?>
                                <?php endif; ?>
                            </h2>

                            <!-- Excerpt -->
                            <?php if ( has_excerpt() ) : ?>
                                <p class="northam-listing-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <?php endif; ?>

                            <!-- Meta Info (Different for Groups vs Venues) -->
                            <div class="northam-listing-meta">
                                <?php if ( $is_venue ) : ?>
                                    <!-- Venue-specific meta -->
                                    <?php if ( $capacity ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <?php echo esc_html( sprintf( __( 'Capacity: %s', 'northam' ), $capacity ) ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $address ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <?php echo esc_html( wp_trim_words( $address, 5 ) ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $phone ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $email ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php esc_html_e( 'Email', 'northam' ); ?></a>
                                        </div>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <!-- Group-specific meta -->
                                    <?php if ( $meeting_time ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <?php echo esc_html( $meeting_time ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $meeting_location ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <?php echo esc_html( wp_trim_words( $meeting_location, 5 ) ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $contact_phone ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                            <a href="tel:<?php echo esc_attr( $contact_phone ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $contact_email ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php esc_html_e( 'Email', 'northam' ); ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $facebook ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                            </svg>
                                            <a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'northam' ); ?></a>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Website (common to both) -->
                                <?php if ( $website ) : ?>
                                    <div class="northam-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="2" y1="12" x2="22" y2="12"></line>
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                        </svg>
                                        <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Website', 'northam' ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Footer with Link -->
                            <?php if ( $card_link ) : ?>
                            <div class="northam-listing-footer">
                                <a href="<?php echo esc_url( $card_link ); ?>"<?php echo $card_target; ?> class="northam-listing-link">
                                    <?php echo $is_venue ? esc_html__( 'View Details', 'northam' ) : esc_html__( 'Visit Website', 'northam' ); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <?php if ( $is_venue ) : ?>
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                        <?php else : ?>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                        <?php endif; ?>
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <div class="northam-no-results">
                <h2><?php esc_html_e( 'No community listings found', 'northam' ); ?></h2>
                <p><?php esc_html_e( 'Please check back later for community group and venue listings.', 'northam' ); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<!-- Call to Action Section -->
<section class="northam-cta-section" style="background-color: hsla(45, 55%, 80%, 0.5);">
    <div class="northam-container">
        <h2 class="northam-cta-title">Run a community group?</h2>
        <p class="northam-cta-description">Get your group listed here to reach more villagers.</p>

        <div data-fs-success style="color: var(--northam-primary); font-weight: 500; padding: 1rem; background: #d4edda; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>
        <div data-fs-error style="color: #dc3545; padding: 1rem; background: #f8d7da; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>

        <form id="add-group-form" style="display: flex; flex-direction: column; gap: 1rem; max-width: 500px; margin: 1.5rem auto 0; text-align: left;">
            <input type="hidden" name="form_type" value="add_group" />
            <input type="hidden" name="page_url" id="add-group-page-url" />
            <input type="text" name="_gotcha" style="display: none;" />

            <div>
                <label for="add-group-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Your Name</label>
                <input type="text" id="add-group-name" name="name" class="northam-form-input" required data-fs-field />
                <span data-fs-error="name" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-group-email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                <input type="email" id="add-group-email" name="email" class="northam-form-input" required data-fs-field />
                <span data-fs-error="email" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-group-group-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Group Name</label>
                <input type="text" id="add-group-group-name" name="group_name" class="northam-form-input" required data-fs-field />
                <span data-fs-error="group_name" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-group-message" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tell us about your group</label>
                <textarea id="add-group-message" name="message" class="northam-form-textarea" rows="3" placeholder="What does your group do, when/where do you meet..." required data-fs-field></textarea>
                <span data-fs-error="message" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <button type="submit" class="northam-btn northam-btn-accent" data-fs-submit-btn>Submit Your Group</button>
        </form>

        <script>
            document.getElementById('add-group-page-url').value = window.location.href;
            window.formspree = window.formspree || function () { (formspree.q = formspree.q || []).push(arguments); };
            formspree('initForm', { formElement: '#add-group-form', formId: 'xgorqwyn' });
        </script>
        <script src="https://unpkg.com/@formspree/ajax@1" defer></script>
    </div>
</section>

<?php
get_footer();
