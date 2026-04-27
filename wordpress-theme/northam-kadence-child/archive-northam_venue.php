<?php
/**
 * Archive Template for Venues
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main northam-archive-page">
    <div class="content-container entry-content">

        <!-- Archive Header -->
        <header class="northam-archive-header">
            <h1 class="northam-archive-title"><?php post_type_archive_title(); ?></h1>
            <?php if ( get_the_archive_description() ) : ?>
                <div class="northam-archive-description">
                    <?php the_archive_description(); ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Taxonomy Filter -->
        <?php
        $venue_types = get_terms( array(
            'taxonomy' => 'venue_type',
            'hide_empty' => true,
        ) );

        if ( ! empty( $venue_types ) && ! is_wp_error( $venue_types ) ) :
        ?>
        <div class="northam-filter-bar">
            <div class="northam-filter-label"><?php esc_html_e( 'Filter by type:', 'northam' ); ?></div>
            <div class="northam-filter-buttons">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'northam_venue' ) ); ?>"
                   class="northam-filter-btn <?php echo ! is_tax( 'venue_type' ) ? 'active' : ''; ?>">
                    <?php esc_html_e( 'All Venues', 'northam' ); ?>
                </a>
                <?php foreach ( $venue_types as $type ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $type ) ); ?>"
                       class="northam-filter-btn <?php echo is_tax( 'venue_type', $type->slug ) ? 'active' : ''; ?>">
                        <?php echo esc_html( $type->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Venue Grid -->
        <?php if ( have_posts() ) : ?>
            <div class="northam-directory-grid">
                <?php while ( have_posts() ) : the_post();
                    $address = get_post_meta( get_the_ID(), '_northam_address', true );
                    $phone = get_post_meta( get_the_ID(), '_northam_phone', true );
                    $email = get_post_meta( get_the_ID(), '_northam_email', true );
                    $website = get_post_meta( get_the_ID(), '_northam_website', true );
                    $capacity = get_post_meta( get_the_ID(), '_northam_capacity', true );
                    $facilities = get_post_meta( get_the_ID(), '_northam_facilities', true );
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'northam-card northam-listing-card northam-hover-lift' ); ?>>

                        <!-- Featured Image -->
                        <div class="northam-listing-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'northam-card' ); ?>
                                </a>
                            <?php else : ?>
                                <svg class="northam-listing-placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="northam-listing-content">

                            <!-- Venue Type Badge -->
                            <?php
                            $types = get_the_terms( get_the_ID(), 'venue_type' );
                            if ( $types && ! is_wp_error( $types ) ) :
                            ?>
                                <span class="northam-listing-category"><?php echo esc_html( $types[0]->name ); ?></span>
                            <?php endif; ?>

                            <!-- Title -->
                            <h2 class="northam-listing-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <!-- Excerpt -->
                            <?php if ( has_excerpt() ) : ?>
                                <p class="northam-listing-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <?php endif; ?>

                            <!-- Quick Info -->
                            <div class="northam-listing-meta">
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
                            </div>

                            <!-- Footer with Link -->
                            <div class="northam-listing-footer">
                                <a href="<?php the_permalink(); ?>" class="northam-listing-link">
                                    <?php esc_html_e( 'View Details', 'northam' ); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '← Previous', 'northam' ),
                'next_text' => __( 'Next →', 'northam' ),
            ) );
            ?>

        <?php else : ?>
            <div class="northam-no-results">
                <h2><?php esc_html_e( 'No venues found', 'northam' ); ?></h2>
                <p><?php esc_html_e( 'Please check back later for venue listings.', 'northam' ); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
