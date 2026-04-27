<?php
/**
 * Sample Data Importer for Northam
 *
 * Adds a one-time import button in the admin to populate sample content.
 * Access via: Tools → Northam Sample Data
 *
 * @package Northam
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin menu page
 */
function northam_sample_data_menu() {
    add_management_page(
        __( 'Northam Sample Data', 'northam' ),
        __( 'Northam Sample Data', 'northam' ),
        'manage_options',
        'northam-sample-data',
        'northam_sample_data_page'
    );
}
add_action( 'admin_menu', 'northam_sample_data_menu' );

/**
 * Admin page callback
 */
function northam_sample_data_page() {
    // Check if delete was requested
    if ( isset( $_POST['northam_delete'] ) && check_admin_referer( 'northam_delete_data' ) ) {
        $deleted = northam_delete_all_content();
        echo '<div class="notice notice-warning"><p>' . sprintf( __( 'Deleted %d items successfully!', 'northam' ), $deleted ) . '</p></div>';
    }

    // Check if import was requested
    if ( isset( $_POST['northam_import'] ) && check_admin_referer( 'northam_import_data' ) ) {
        $imported = northam_import_sample_data();
        echo '<div class="notice notice-success"><p>' . sprintf( __( 'Imported %d items successfully!', 'northam' ), $imported ) . '</p></div>';
    }

    // Check what's already imported
    $business_count = wp_count_posts( 'northam_business' )->publish;
    $directory_count = wp_count_posts( 'northam_directory' )->publish;
    $total_count = $business_count + $directory_count;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Northam Sample Data', 'northam' ); ?></h1>

        <h2><?php esc_html_e( 'Current Content', 'northam' ); ?></h2>
        <ul>
            <li><strong>Businesses (Full Pages):</strong> <?php echo intval( $business_count ); ?></li>
            <li><strong>Directory Listings (Simple Cards):</strong> <?php echo intval( $directory_count ); ?></li>
        </ul>

        <?php if ( $total_count > 0 ) : ?>
        <h2 style="color: #d63638;"><?php esc_html_e( 'Delete All Content', 'northam' ); ?></h2>
        <p><?php esc_html_e( 'This will permanently delete ALL businesses and directory listings. This cannot be undone!', 'northam' ); ?></p>
        <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL Northam content? This cannot be undone!');">
            <?php wp_nonce_field( 'northam_delete_data' ); ?>
            <p>
                <input type="submit" name="northam_delete" class="button button-secondary" style="background: #d63638; border-color: #d63638; color: #fff;" value="<?php esc_attr_e( 'Delete All Content', 'northam' ); ?>">
            </p>
        </form>
        <hr>
        <?php endif; ?>

        <h2><?php esc_html_e( 'Import Sample Data', 'northam' ); ?></h2>
        <p><?php esc_html_e( 'This will import the following actual Northam content:', 'northam' ); ?></p>
        <ul>
            <li><strong>13 Businesses (Full Pages):</strong>
                <ul>
                    <li>Food & Drink (7): The Kingsley, Golden Lion, Northam Coffee House, Sea Horse Fish & Chips, Kingsburg Chinese, Memories Restaurant, Square Studio</li>
                    <li>Visual Services (6): Whitewash (dog groomers), Northam Hairdressers, Urban Wave, Lamb Chop (barbers), Ocean Wave (beauty), [one more visual service]</li>
                </ul>
            </li>
            <li><strong>5 Directory Listings (Simple Cards):</strong> Cost Cutter, Newsagent, A D J Williams (funeral), Richard Williams (funeral), Nova Surveyors</li>
        </ul>

        <form method="post">
            <?php wp_nonce_field( 'northam_import_data' ); ?>
            <p>
                <input type="submit" name="northam_import" class="button button-primary" value="<?php esc_attr_e( 'Import Sample Data', 'northam' ); ?>">
            </p>
        </form>

        <hr>
        <p><em><?php esc_html_e( 'Note: Running import multiple times will create duplicate entries. Use "Delete All Content" first to start fresh.', 'northam' ); ?></em></p>
    </div>
    <?php
}

/**
 * Delete all Northam content
 */
function northam_delete_all_content() {
    $count = 0;
    $post_types = array( 'northam_business', 'northam_venue', 'northam_directory', 'northam_attraction', 'northam_group' );

    foreach ( $post_types as $post_type ) {
        $posts = get_posts( array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ) );

        foreach ( $posts as $post_id ) {
            wp_delete_post( $post_id, true ); // true = force delete, skip trash
            $count++;
        }
    }

    // Also delete taxonomy terms
    $taxonomies = array( 'business_category', 'venue_type', 'directory_type', 'attraction_category', 'group_type' );
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'fields'     => 'ids',
        ) );

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term_id ) {
                wp_delete_term( $term_id, $taxonomy );
            }
        }
    }

    return $count;
}

/**
 * Import sample data
 */
function northam_import_sample_data() {
    $count = 0;

    // ==========================================================================
    // BUSINESS CATEGORIES
    // ==========================================================================
    $cat_food = wp_insert_term( 'Food & Drink', 'business_category', array( 'slug' => 'food-drink' ) );
    $cat_visual = wp_insert_term( 'Visual Services', 'business_category', array( 'slug' => 'visual-services' ) );

    $food_id = is_array( $cat_food ) ? $cat_food['term_id'] : $cat_food;
    $visual_id = is_array( $cat_visual ) ? $cat_visual['term_id'] : $cat_visual;

    // ==========================================================================
    // BUSINESSES (13) - ACTUAL Northam Businesses ONLY
    // ==========================================================================
    $businesses = array(
        // Food & Drink (7)
        array(
            'title'    => 'The Kingsley',
            'excerpt'  => 'Traditional pub serving great food and drinks in the heart of Northam.',
            'content'  => 'The Kingsley is a welcoming local pub offering a warm atmosphere, quality food, and a great selection of drinks. A popular spot for locals and visitors alike.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123456',
                '_northam_opening_hours' => 'Mon-Sun: 11:00 - 23:00',
            ),
        ),
        array(
            'title'    => 'Golden Lion',
            'excerpt'  => 'Historic village pub with friendly atmosphere and traditional hospitality.',
            'content'  => 'The Golden Lion offers classic pub charm with quality drinks and a welcoming environment for the whole community.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123457',
                '_northam_opening_hours' => 'Mon-Sun: 12:00 - 23:00',
            ),
        ),
        array(
            'title'    => 'Northam Coffee House',
            'excerpt'  => 'Cosy café serving specialty coffee, homemade cakes, and light meals.',
            'content'  => 'Northam Coffee House is the perfect spot for a morning coffee, afternoon tea, or light lunch. Enjoy our homemade cakes and friendly service.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123458',
                '_northam_opening_hours' => 'Mon-Sat: 8:00 - 17:00, Sun: 9:00 - 16:00',
            ),
        ),
        array(
            'title'    => 'Sea Horse Fish & Chips',
            'excerpt'  => 'Fresh, locally-caught fish and hand-cut chips. A Northam favourite.',
            'content'  => 'Sea Horse serves traditional fish and chips using the freshest local ingredients. Perfect for a takeaway or eat in.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123459',
                '_northam_opening_hours' => 'Tue-Sun: 11:30 - 20:00, Mon: Closed',
            ),
        ),
        array(
            'title'    => 'Kingsburg Chinese',
            'excerpt'  => 'Authentic Chinese cuisine with takeaway and dining options.',
            'content'  => 'Kingsburg Chinese offers a wide range of authentic Chinese dishes. Dine in or takeaway available.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123460',
                '_northam_opening_hours' => 'Mon-Sun: 17:00 - 22:30',
            ),
        ),
        array(
            'title'    => 'Memories Restaurant',
            'excerpt'  => 'Fine dining restaurant offering exceptional food and service.',
            'content'  => 'Memories Restaurant provides an elegant dining experience with a menu showcasing the best of local produce.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123461',
                '_northam_opening_hours' => 'Tue-Sat: 18:00 - 22:00, Sun: 12:00 - 15:00',
            ),
        ),
        array(
            'title'    => 'Square Studio',
            'excerpt'  => 'Contemporary dining and café in a relaxed setting.',
            'content'  => 'Square Studio offers modern cuisine in a stylish yet comfortable environment. Perfect for any occasion.',
            'category' => $food_id,
            'meta'     => array(
                '_northam_address'       => 'The Square, Northam, Devon',
                '_northam_phone'         => '01237 123462',
                '_northam_opening_hours' => 'Mon-Sat: 9:00 - 17:00',
            ),
        ),
        // Visual Services (6)
        array(
            'title'    => 'Whitewash',
            'excerpt'  => 'Professional dog grooming with before/after transformations.',
            'content'  => 'Whitewash offers expert dog grooming services. We treat every dog with care and attention, creating beautiful transformations. Check out our Instagram for before and after photos!',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123463',
                '_northam_instagram'     => 'whitewashnortham',
                '_northam_opening_hours' => 'Mon-Sat: 9:00 - 17:00',
            ),
        ),
        array(
            'title'    => 'Northam Hairdressers',
            'excerpt'  => 'Quality hairdressing for all the family. Styles, cuts, and colour.',
            'content'  => 'Northam Hairdressers provides professional styling, cutting, and colouring services. Our experienced team creates looks you\'ll love.',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123464',
                '_northam_instagram'     => 'northamhairdressers',
                '_northam_opening_hours' => 'Tue-Sat: 9:00 - 17:00',
            ),
        ),
        array(
            'title'    => 'Urban Wave',
            'excerpt'  => 'Modern hair salon specializing in contemporary styles and transformations.',
            'content'  => 'Urban Wave brings the latest hair trends to Northam. Our skilled stylists create stunning transformations. Follow us on Instagram to see our latest work!',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123465',
                '_northam_instagram'     => 'urbanwavenortham',
                '_northam_opening_hours' => 'Tue-Sat: 9:00 - 18:00',
            ),
        ),
        array(
            'title'    => 'Lamb Chop',
            'excerpt'  => 'Traditional barbers offering classic and modern cuts.',
            'content'  => 'Lamb Chop is Northam\'s premier barber shop. We offer traditional barbering with a modern twist. Walk-ins welcome or book ahead.',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123466',
                '_northam_instagram'     => 'lambchopbarbers',
                '_northam_opening_hours' => 'Mon-Sat: 8:30 - 17:30',
            ),
        ),
        array(
            'title'    => 'Ocean Wave',
            'excerpt'  => 'Beauty salon offering a full range of treatments with visible results.',
            'content'  => 'Ocean Wave provides professional beauty treatments including facials, nails, lashes, and more. See our amazing results on Instagram!',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123467',
                '_northam_instagram'     => 'oceanwavenortham',
                '_northam_opening_hours' => 'Tue-Sat: 10:00 - 18:00',
            ),
        ),
        array(
            'title'    => 'Northam Beauty Studio',
            'excerpt'  => 'Comprehensive beauty treatments in a relaxing environment.',
            'content'  => 'Northam Beauty Studio offers a complete range of beauty services. Our experienced therapists provide excellent results in a calm, professional setting.',
            'category' => $visual_id,
            'meta'     => array(
                '_northam_address'       => 'Northam, Devon',
                '_northam_phone'         => '01237 123468',
                '_northam_opening_hours' => 'Tue-Sat: 9:30 - 17:30',
            ),
        ),
    );

    foreach ( $businesses as $biz ) {
        $post_id = wp_insert_post( array(
            'post_title'   => $biz['title'],
            'post_excerpt' => $biz['excerpt'],
            'post_content' => $biz['content'],
            'post_status'  => 'publish',
            'post_type'    => 'northam_business',
        ) );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            wp_set_object_terms( $post_id, array( intval( $biz['category'] ) ), 'business_category' );
            foreach ( $biz['meta'] as $key => $value ) {
                update_post_meta( $post_id, $key, $value );
            }
            $count++;
        }
    }

    // ==========================================================================
    // DIRECTORY TYPES
    // ==========================================================================
    $type_retail = wp_insert_term( 'Retail', 'directory_type', array( 'slug' => 'retail' ) );
    $type_funeral = wp_insert_term( 'Funeral Services', 'directory_type', array( 'slug' => 'funeral-services' ) );
    $type_professional = wp_insert_term( 'Professional Services', 'directory_type', array( 'slug' => 'professional-services' ) );

    // ==========================================================================
    // DIRECTORY LISTINGS (5) - ACTUAL Northam Directory ONLY
    // ==========================================================================
    $directory = array(
        array( 'title' => 'Cost Cutter', 'type' => 'retail', 'phone' => '01237 456780', 'address' => 'The Square, Northam', 'hours' => 'Daily: 7am - 10pm' ),
        array( 'title' => 'Northam Newsagent', 'type' => 'retail', 'phone' => '01237 456781', 'address' => 'High Street, Northam', 'hours' => 'Daily: 6am - 8pm' ),
        array( 'title' => 'A D J Williams', 'type' => 'funeral-services', 'phone' => '01237 456782', 'address' => 'Northam, Devon', 'hours' => '24 hours' ),
        array( 'title' => 'Richard Williams Funeral Services', 'type' => 'funeral-services', 'phone' => '01237 456783', 'address' => 'Northam, Devon', 'hours' => '24 hours' ),
        array( 'title' => 'Nova Surveyors', 'type' => 'professional-services', 'phone' => '01237 456784', 'address' => 'Northam, Devon', 'hours' => 'Mon-Fri: 9am - 5pm' ),
    );

    foreach ( $directory as $listing ) {
        $post_id = wp_insert_post( array(
            'post_title'  => $listing['title'],
            'post_status' => 'publish',
            'post_type'   => 'northam_directory',
        ) );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            wp_set_object_terms( $post_id, $listing['type'], 'directory_type' );
            update_post_meta( $post_id, '_northam_phone', $listing['phone'] );
            update_post_meta( $post_id, '_northam_address', $listing['address'] );
            update_post_meta( $post_id, '_northam_opening_hours', $listing['hours'] );
            $count++;
        }
    }

    // Flush rewrite rules
    flush_rewrite_rules();

    return $count;
}
