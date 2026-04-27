<?php
/**
 * Archive Template for Things To Do / Attractions
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
        $attraction_categories = get_terms( array(
            'taxonomy' => 'attraction_category',
            'hide_empty' => true,
        ) );

        if ( ! empty( $attraction_categories ) && ! is_wp_error( $attraction_categories ) ) :
        ?>
        <div class="northam-filter-bar">
            <div class="northam-filter-label"><?php esc_html_e( 'Filter by category:', 'northam' ); ?></div>
            <div class="northam-filter-buttons">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'northam_attraction' ) ); ?>"
                   class="northam-filter-btn <?php echo ! is_tax( 'attraction_category' ) ? 'active' : ''; ?>">
                    <?php esc_html_e( 'All Attractions', 'northam' ); ?>
                </a>
                <?php foreach ( $attraction_categories as $category ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $category ) ); ?>"
                       class="northam-filter-btn <?php echo is_tax( 'attraction_category', $category->slug ) ? 'active' : ''; ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Attractions Grid -->
        <?php if ( have_posts() ) : ?>
            <div class="northam-directory-grid">
                <?php while ( have_posts() ) : the_post();
                    $website = get_post_meta( get_the_ID(), '_northam_website', true );
                    $highlights = get_post_meta( get_the_ID(), '_northam_highlights', true );
                    $map_url = get_post_meta( get_the_ID(), '_northam_map_url', true );
                    $badge_text = get_post_meta( get_the_ID(), '_northam_badge_text', true );
                    $badge_type = get_post_meta( get_the_ID(), '_northam_badge_type', true );
                    $duration = get_post_meta( get_the_ID(), '_northam_duration', true );
                    $distance = get_post_meta( get_the_ID(), '_northam_distance', true );
                    if ( empty( $badge_type ) ) $badge_type = 'default';
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'northam-card northam-listing-card northam-attraction-card northam-hover-lift' ); ?>>

                        <!-- Featured Image with Badges -->
                        <div class="northam-listing-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'northam-card' ); ?>
                            <?php else : ?>
                                <svg class="northam-listing-placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            <?php endif; ?>

                            <!-- Category Badge (top-left corner) -->
                            <?php
                            $categories = get_the_terms( get_the_ID(), 'attraction_category' );
                            if ( $categories && ! is_wp_error( $categories ) ) :
                            ?>
                                <span class="northam-attraction-category-badge"><?php echo esc_html( $categories[0]->name ); ?></span>
                            <?php endif; ?>

                            <!-- Seasonal Badge (top-right corner) -->
                            <?php if ( ! empty( $badge_text ) ) : ?>
                                <span class="northam-seasonal-badge badge-<?php echo esc_attr( $badge_type ); ?>">
                                    <?php echo esc_html( $badge_text ); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="northam-listing-content">

                            <!-- Title -->
                            <h2 class="northam-listing-title northam-attraction-title">
                                <?php the_title(); ?>
                            </h2>

                            <!-- Excerpt -->
                            <?php if ( has_excerpt() ) : ?>
                                <p class="northam-listing-description northam-attraction-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <?php endif; ?>

                            <!-- Highlights (without icons) -->
                            <?php if ( $highlights ) :
                                $highlights_array = array_filter( array_map( 'trim', explode( "\n", $highlights ) ) );
                                if ( ! empty( $highlights_array ) ) :
                            ?>
                                <div class="northam-highlights northam-attraction-highlights">
                                    <?php foreach ( array_slice( $highlights_array, 0, 3 ) as $highlight ) : ?>
                                        <span class="northam-highlight-pill">
                                            <?php echo esc_html( $highlight ); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php
                                endif;
                            endif;
                            ?>

                            <!-- Metadata Footer with Duration & Distance -->
                            <?php if ( $duration || $distance ) : ?>
                                <div class="northam-attraction-meta-footer">
                                    <?php if ( $duration ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span><?php echo esc_html( $duration ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $distance ) : ?>
                                        <div class="northam-meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <span><?php echo esc_html( $distance ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Footer with Learn More Button -->
                            <?php if ( $website ) : ?>
                                <div class="northam-listing-footer">
                                    <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer" class="northam-learn-more-btn">
                                        <?php esc_html_e( 'Learn More', 'northam' ); ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>
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
                <h2><?php esc_html_e( 'No attractions found', 'northam' ); ?></h2>
                <p><?php esc_html_e( 'Please check back later for things to do in the area.', 'northam' ); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
