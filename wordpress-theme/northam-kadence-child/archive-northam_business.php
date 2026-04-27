<?php
/**
 * Business Directory Archive Template
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();

// Get all business categories for filters
$categories = get_terms( array(
    'taxonomy' => 'business_category',
    'hide_empty' => true,
) );

// Get current filter from URL
$current_category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

// Build query args
$args = array(
    'post_type' => 'northam_business',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
);

if ( ! empty( $current_category ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'business_category',
            'field' => 'slug',
            'terms' => $current_category,
        ),
    );
}

if ( ! empty( $search_query ) ) {
    $args['s'] = $search_query;
}

$businesses = new WP_Query( $args );

// Category colors mapping
$category_colors = array(
    'food-drink' => 'bg-event-venue',
    'pubs' => 'bg-event-venue',
    'restaurants' => 'bg-event-venue',
    'cafes' => 'bg-event-venue',
    'beauty-grooming' => 'bg-event-business',
    'hair-beauty' => 'bg-event-business',
    'services' => 'bg-event-business',
);
?>

<!-- Hero Section -->
<section class="northam-archive-hero bg-coastal-deep">
    <div class="northam-archive-hero-overlay"></div>
    <div class="northam-container">
        <div class="northam-archive-hero-content">
            <span class="northam-hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Local Businesses
            </span>
            <h1>Business Directory</h1>
            <p>Discover the pubs, cafes, restaurants, and personal services that make Northam special</p>
        </div>
    </div>
</section>

<!-- Search & Filters -->
<section class="northam-filters-bar">
    <div class="northam-container">
        <form class="northam-filters-form" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'northam_business' ) ); ?>">
            <!-- Search -->
            <div class="northam-search-field">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="s" placeholder="Search businesses..." value="<?php echo esc_attr( $search_query ); ?>">
            </div>

            <!-- Category Filters -->
            <div class="northam-category-filters">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'northam_business' ) ); ?>" class="northam-filter-btn <?php echo empty( $current_category ) ? 'active' : ''; ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    All
                </a>
                <?php foreach ( $categories as $cat ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'category', $cat->slug, get_post_type_archive_link( 'northam_business' ) ) ); ?>" class="northam-filter-btn <?php echo $current_category === $cat->slug ? 'active' : ''; ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
</section>

<!-- Business Grid -->
<section class="northam-archive-content">
    <div class="northam-container">
        <div class="northam-archive-header">
            <p class="northam-results-count">
                Showing <strong><?php echo esc_html( $businesses->found_posts ); ?></strong> businesses
            </p>
        </div>

        <?php if ( $businesses->have_posts() ) : ?>
            <div class="northam-directory-grid">
                <?php while ( $businesses->have_posts() ) : $businesses->the_post();
                    $phone = get_post_meta( get_the_ID(), '_northam_phone', true );
                    $address = get_post_meta( get_the_ID(), '_northam_address', true );
                    $instagram = get_post_meta( get_the_ID(), '_northam_instagram', true );
                    $website = get_post_meta( get_the_ID(), '_northam_website', true );
                    $archive_only = get_post_meta( get_the_ID(), '_northam_archive_only', true );

                    $cats = get_the_terms( get_the_ID(), 'business_category' );
                    $cat = $cats && ! is_wp_error( $cats ) ? $cats[0] : null;
                    $cat_slug = $cat ? $cat->slug : 'general';
                    $badge_class = isset( $category_colors[ $cat_slug ] ) ? $category_colors[ $cat_slug ] : 'bg-primary';
                    $has_multiple_cats = $cats && ! is_wp_error( $cats ) && count( $cats ) > 1;

                    // Determine link behavior
                    if ( $archive_only && $website ) {
                        $card_link = esc_url( $website );
                        $link_target = ' target="_blank" rel="noopener noreferrer"';
                        $link_text = 'Visit Website';
                        $is_external = true;
                    } elseif ( $archive_only ) {
                        $card_link = '';
                        $link_target = '';
                        $link_text = '';
                        $is_external = false;
                    } else {
                        $card_link = get_permalink();
                        $link_target = '';
                        $link_text = 'View Details';
                        $is_external = false;
                    }
                ?>
                    <?php if ( $card_link ) : ?>
                    <a href="<?php echo $card_link; ?>"<?php echo $link_target; ?> class="northam-card northam-business-card northam-hover-lift">
                    <?php else : ?>
                    <div class="northam-card northam-business-card">
                    <?php endif; ?>
                        <div class="northam-business-card-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'northam-card' ); ?>
                            <?php else : ?>
                                <div class="northam-business-card-placeholder">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <?php if ( $instagram ) : ?>
                                <span class="northam-instagram-badge">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                    Feed
                                </span>
                            <?php endif; ?>
                            <?php if ( $is_external ) : ?>
                                <span class="northam-external-badge">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="northam-business-card-content">
                            <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
                                <div class="northam-categories-badges">
                                    <?php foreach ( $cats as $single_cat ) :
                                        $single_cat_slug = $single_cat->slug;
                                        $single_badge_class = isset( $category_colors[ $single_cat_slug ] ) ? $category_colors[ $single_cat_slug ] : 'bg-primary';
                                    ?>
                                        <span class="northam-badge <?php echo esc_attr( $single_badge_class ); ?>"><?php echo esc_html( $single_cat->name ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if ( has_excerpt() ) : ?>
                                <p class="northam-business-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
                            <?php endif; ?>
                            <div class="northam-business-card-meta">
                                <?php if ( $address ) : ?>
                                    <div class="northam-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span><?php echo esc_html( $address ); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $phone ) : ?>
                                    <div class="northam-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                        <span><?php echo esc_html( $phone ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( $link_text ) : ?>
                            <span class="northam-view-link">
                                <?php echo esc_html( $link_text ); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <?php if ( $is_external ) : ?>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    <?php else : ?>
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    <?php endif; ?>
                                </svg>
                            </span>
                            <?php endif; ?>
                        </div>
                    <?php if ( $card_link ) : ?>
                    </a>
                    <?php else : ?>
                    </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="northam-no-results">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <h3>No businesses found</h3>
                <p>Try adjusting your search or category filters.</p>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<!-- Add Business CTA -->
<section class="northam-cta-section bg-sand-warm">
    <div class="northam-container">
        <h2>Own a local business?</h2>
        <p>Get your business listed in our directory with an Instagram feed showcase. It's free for all Northam businesses.</p>

        <div data-fs-success style="color: var(--northam-primary); font-weight: 500; padding: 1rem; background: #d4edda; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>
        <div data-fs-error style="color: #dc3545; padding: 1rem; background: #f8d7da; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>

        <form id="add-business-form" style="display: flex; flex-direction: column; gap: 1rem; max-width: 500px; margin: 1.5rem auto 0; text-align: left;">
            <input type="hidden" name="form_type" value="add_business" />
            <input type="hidden" name="page_url" id="add-business-page-url" />
            <input type="text" name="_gotcha" style="display: none;" />

            <div>
                <label for="add-business-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Your Name</label>
                <input type="text" id="add-business-name" name="name" class="northam-form-input" required data-fs-field />
                <span data-fs-error="name" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-business-email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                <input type="email" id="add-business-email" name="email" class="northam-form-input" required data-fs-field />
                <span data-fs-error="email" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-business-business-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Business Name</label>
                <input type="text" id="add-business-business-name" name="business_name" class="northam-form-input" required data-fs-field />
                <span data-fs-error="business_name" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <div>
                <label for="add-business-message" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tell us about your business</label>
                <textarea id="add-business-message" name="message" class="northam-form-textarea" rows="3" placeholder="Brief description, location, services offered..." required data-fs-field></textarea>
                <span data-fs-error="message" style="color: #dc3545; font-size: 0.875rem;"></span>
            </div>

            <button type="submit" class="northam-btn bg-accent" data-fs-submit-btn>Submit Your Business</button>
        </form>

        <script>
            document.getElementById('add-business-page-url').value = window.location.href;
            window.formspree = window.formspree || function () { (formspree.q = formspree.q || []).push(arguments); };
            formspree('initForm', { formElement: '#add-business-form', formId: 'xgorqwyn' });
        </script>
        <script src="https://unpkg.com/@formspree/ajax@1" defer></script>
    </div>
</section>

<?php
get_footer();
