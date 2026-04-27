<?php
/**
 * Northam Child Theme Functions
 *
 * @package Northam
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme version
define( 'NORTHAM_VERSION', '1.0.0' );
define( 'NORTHAM_THEME_DIR', get_stylesheet_directory() );
define( 'NORTHAM_THEME_URI', get_stylesheet_directory_uri() );

/**
 * =============================================================================
 * CUSTOM LOGO WITH ICON
 * =============================================================================
 */

/**
 * Replace site title with custom logo (teal circle + MapPin icon + text)
 */
function northam_custom_logo_html( $html, $blog_id ) {
    $custom_logo = '
    <a href="' . esc_url( home_url( '/' ) ) . '" class="northam-logo-link" rel="home">
        <div class="northam-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>
        <div class="northam-logo-text">
            <span class="northam-logo-title">Northam</span>
            <span class="northam-logo-tagline">Devon</span>
        </div>
    </a>';
    return $custom_logo;
}
add_filter( 'get_custom_logo', 'northam_custom_logo_html', 10, 2 );

/**
 * Force custom logo to display even without uploaded image
 */
function northam_force_custom_logo( $html ) {
    if ( empty( $html ) ) {
        return northam_custom_logo_html( '', get_current_blog_id() );
    }
    return $html;
}
add_filter( 'get_custom_logo', 'northam_force_custom_logo', 20 );

/**
 * Add custom logo to Kadence's site branding area
 * This outputs the logo in the header if Kadence doesn't use get_custom_logo
 */
function northam_add_logo_to_branding() {
    ?>
    <style>
    /* Make custom logo show in Kadence header */
    .site-branding .custom-logo-link,
    .site-branding .northam-logo-link {
        display: flex !important;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'northam_add_logo_to_branding', 5 );

/**
 * Filter site title to include our custom logo HTML
 */
function northam_custom_site_branding( $title ) {
    // Only modify on frontend
    if ( is_admin() ) {
        return $title;
    }

    $custom_logo = '
    <a href="' . esc_url( home_url( '/' ) ) . '" class="northam-logo-link" rel="home">
        <div class="northam-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>
        <div class="northam-logo-text">
            <span class="northam-logo-title">Northam</span>
            <span class="northam-logo-tagline">Devon</span>
        </div>
    </a>';

    return $custom_logo;
}

/**
 * Output custom logo before site title in Kadence header
 */
function northam_output_custom_logo() {
    // Check if we're on the frontend
    if ( is_admin() ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find site branding area
        var siteBranding = document.querySelector('.site-branding');
        if (!siteBranding) return;

        // Check if our custom logo already exists
        if (siteBranding.querySelector('.northam-logo-link')) return;

        // Create custom logo
        var logoHTML = `
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="northam-logo-link" rel="home">
                <div class="northam-logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="northam-logo-text">
                    <span class="northam-logo-title">Northam</span>
                    <span class="northam-logo-tagline">Devon</span>
                </div>
            </a>
        `;

        // Insert logo at the beginning of site branding
        siteBranding.insertAdjacentHTML('afterbegin', logoHTML);

        // Hide the default site title
        var siteTitle = siteBranding.querySelector('.site-title');
        if (siteTitle) siteTitle.style.display = 'none';

        var siteDesc = siteBranding.querySelector('.site-description');
        if (siteDesc) siteDesc.style.display = 'none';
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'northam_output_custom_logo' );

/**
 * Add nav menu icons via CSS pseudo-elements
 */
function northam_nav_menu_icons_css() {
    ?>
    <style>
    /* Custom logo styles */
    .northam-logo-link {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        text-decoration: none !important;
    }
    .northam-logo-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        background-color: var(--northam-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .northam-logo-icon svg {
        color: #ffffff;
    }
    .northam-logo-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }
    .northam-logo-title {
        font-family: var(--northam-font-heading);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--northam-foreground);
    }
    .northam-logo-tagline {
        font-size: 0.75rem;
        color: var(--northam-muted-foreground);
        margin-top: -2px;
    }

    /* Hide default site title when custom logo is shown */
    .site-branding .site-title {
        display: none;
    }
    .site-branding .site-description {
        display: none;
    }

    /* Nav menu icons - add before each menu item */
    .header-navigation .menu > li > a,
    .primary-navigation .menu > li > a {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }

    /* Events - Calendar icon */
    .menu-item a[href*="events"]::before,
    .menu-item a[href*="Events"]::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a3a4a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    /* Directory - Store icon */
    .menu-item a[href*="directory"]::before,
    .menu-item a[href*="Directory"]::before,
    .menu-item a[href*="business"]::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a3a4a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z'%3E%3C/path%3E%3Cpolyline points='9 22 9 12 15 12 15 22'%3E%3C/polyline%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    /* Things to Do - Compass icon */
    .menu-item a[href*="things-to-do"]::before,
    .menu-item a[href*="things"]::before,
    .menu-item a[href*="Things"]::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a3a4a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolygon points='16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76'%3E%3C/polygon%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    /* Community - Users icon */
    .menu-item a[href*="community"]::before,
    .menu-item a[href*="Community"]::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a3a4a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='9' cy='7' r='4'%3E%3C/circle%3E%3Cpath d='M23 21v-2a4 4 0 0 0-3-3.87'%3E%3C/path%3E%3Cpath d='M16 3.13a4 4 0 0 1 0 7.75'%3E%3C/path%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    /* History - History/Clock icon (using Lucide History icon) */
    .menu-item a[href*="history"]::before,
    .menu-item a[href*="History"]::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a3a4a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8'%3E%3C/path%3E%3Cpath d='M3 3v5h5'%3E%3C/path%3E%3Cpath d='M12 7v5l4 2'%3E%3C/path%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    /* Active menu item icons - white color */
    .current-menu-item a[href*="events"]::before,
    .current-menu-item a[href*="Events"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
    }

    .current-menu-item a[href*="directory"]::before,
    .current-menu-item a[href*="Directory"]::before,
    .current-menu-item a[href*="business"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z'%3E%3C/path%3E%3Cpolyline points='9 22 9 12 15 12 15 22'%3E%3C/polyline%3E%3C/svg%3E");
    }

    .current-menu-item a[href*="things-to-do"]::before,
    .current-menu-item a[href*="things"]::before,
    .current-menu-item a[href*="Things"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolygon points='16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76'%3E%3C/polygon%3E%3C/svg%3E");
    }

    .current-menu-item a[href*="community"]::before,
    .current-menu-item a[href*="Community"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='9' cy='7' r='4'%3E%3C/circle%3E%3Cpath d='M23 21v-2a4 4 0 0 0-3-3.87'%3E%3C/path%3E%3Cpath d='M16 3.13a4 4 0 0 1 0 7.75'%3E%3C/path%3E%3C/svg%3E");
    }

    .current-menu-item a[href*="history"]::before,
    .current-menu-item a[href*="History"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8'%3E%3C/path%3E%3Cpath d='M3 3v5h5'%3E%3C/path%3E%3Cpath d='M12 7v5l4 2'%3E%3C/path%3E%3C/svg%3E");
    }
    </style>
    <?php
}
add_action( 'wp_head', 'northam_nav_menu_icons_css' );

/**
 * =============================================================================
 * THEME SETUP
 * =============================================================================
 */

/**
 * Enqueue parent and child theme styles
 *
 * Kadence uses dynamic CSS generation, so we hook into their system
 */
function northam_enqueue_styles() {
    // Child theme style - depends on Kadence's global styles
    wp_enqueue_style(
        'northam-style',
        get_stylesheet_uri(),
        array( 'kadence-global' ),
        NORTHAM_VERSION
    );

    // Google Fonts - Cormorant Garamond & Source Sans 3
    wp_enqueue_style(
        'northam-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Source+Sans+3:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap',
        array(),
        null
    );

    // Header JavaScript
    wp_enqueue_script(
        'northam-header',
        NORTHAM_THEME_URI . '/assets/js/header.js',
        array(),
        NORTHAM_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'northam_enqueue_styles', 20 );

/**
 * Enqueue admin styles for meta boxes
 */
function northam_admin_styles() {
    $screen = get_current_screen();
    $northam_post_types = array( 'northam_business', 'northam_venue', 'northam_attraction', 'northam_group' );

    if ( $screen && in_array( $screen->post_type, $northam_post_types, true ) ) {
        wp_enqueue_style(
            'northam-admin',
            NORTHAM_THEME_URI . '/assets/css/admin.css',
            array(),
            NORTHAM_VERSION
        );
    }
}
add_action( 'admin_enqueue_scripts', 'northam_admin_styles' );

/**
 * Theme setup
 */
function northam_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Custom image sizes for listings
    add_image_size( 'northam-card', 400, 250, true );
    add_image_size( 'northam-gallery', 800, 600, true );
    add_image_size( 'northam-hero', 1920, 800, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => __( 'Primary Navigation', 'northam' ),
        'footer'    => __( 'Footer Navigation', 'northam' ),
        'mobile'    => __( 'Mobile Navigation', 'northam' ),
    ) );
}
add_action( 'after_setup_theme', 'northam_theme_setup' );

/**
 * =============================================================================
 * CUSTOM POST TYPES
 * =============================================================================
 */

/**
 * Register all Custom Post Types
 */
function northam_register_post_types() {

    // -------------------------------------------------------------------------
    // BUSINESSES CPT
    // -------------------------------------------------------------------------
    register_post_type( 'northam_business', array(
        'labels' => array(
            'name'               => __( 'Businesses', 'northam' ),
            'singular_name'      => __( 'Business', 'northam' ),
            'add_new'            => __( 'Add New Business', 'northam' ),
            'add_new_item'       => __( 'Add New Business', 'northam' ),
            'edit_item'          => __( 'Edit Business', 'northam' ),
            'new_item'           => __( 'New Business', 'northam' ),
            'view_item'          => __( 'View Business', 'northam' ),
            'search_items'       => __( 'Search Businesses', 'northam' ),
            'not_found'          => __( 'No businesses found', 'northam' ),
            'not_found_in_trash' => __( 'No businesses found in Trash', 'northam' ),
            'menu_name'          => __( 'Businesses', 'northam' ),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'business', 'with_front' => false ),
        'menu_icon'           => 'dashicons-store',
        'menu_position'       => 20,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => false,
    ) );

    // -------------------------------------------------------------------------
    // COMMUNITY VENUES CPT
    // -------------------------------------------------------------------------
    register_post_type( 'northam_venue', array(
        'labels' => array(
            'name'               => __( 'Community Venues', 'northam' ),
            'singular_name'      => __( 'Community Venue', 'northam' ),
            'add_new'            => __( 'Add New Community Venue', 'northam' ),
            'add_new_item'       => __( 'Add New Community Venue', 'northam' ),
            'edit_item'          => __( 'Edit Community Venue', 'northam' ),
            'new_item'           => __( 'New Community Venue', 'northam' ),
            'view_item'          => __( 'View Community Venue', 'northam' ),
            'search_items'       => __( 'Search Community Venues', 'northam' ),
            'not_found'          => __( 'No community venues found', 'northam' ),
            'not_found_in_trash' => __( 'No community venues found in Trash', 'northam' ),
            'menu_name'          => __( 'Community Venues', 'northam' ),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'venue', 'with_front' => false ),
        'menu_icon'           => 'dashicons-building',
        'menu_position'       => 21,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => false,
        'capability_type'     => 'northam_venue',
        'map_meta_cap'        => true,
    ) );

    // -------------------------------------------------------------------------
    // THINGS TO DO / ATTRACTIONS CPT (Cards only - no single pages)
    // -------------------------------------------------------------------------
    register_post_type( 'northam_attraction', array(
        'labels' => array(
            'name'               => __( 'Things To Do', 'northam' ),
            'singular_name'      => __( 'Attraction', 'northam' ),
            'add_new'            => __( 'Add New Attraction', 'northam' ),
            'add_new_item'       => __( 'Add New Attraction', 'northam' ),
            'edit_item'          => __( 'Edit Attraction', 'northam' ),
            'new_item'           => __( 'New Attraction', 'northam' ),
            'view_item'          => __( 'View Attraction', 'northam' ),
            'search_items'       => __( 'Search Attractions', 'northam' ),
            'not_found'          => __( 'No attractions found', 'northam' ),
            'not_found_in_trash' => __( 'No attractions found in Trash', 'northam' ),
            'menu_name'          => __( 'Things To Do', 'northam' ),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'things-to-do', 'with_front' => false ),
        'menu_icon'           => 'dashicons-location-alt',
        'menu_position'       => 23,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => false,
    ) );

    // -------------------------------------------------------------------------
    // COMMUNITY GROUPS CPT
    // -------------------------------------------------------------------------
    register_post_type( 'northam_group', array(
        'labels' => array(
            'name'               => __( 'Community Groups', 'northam' ),
            'singular_name'      => __( 'Community Group', 'northam' ),
            'add_new'            => __( 'Add New Group', 'northam' ),
            'add_new_item'       => __( 'Add New Community Group', 'northam' ),
            'edit_item'          => __( 'Edit Group', 'northam' ),
            'new_item'           => __( 'New Group', 'northam' ),
            'view_item'          => __( 'View Group', 'northam' ),
            'search_items'       => __( 'Search Groups', 'northam' ),
            'not_found'          => __( 'No groups found', 'northam' ),
            'not_found_in_trash' => __( 'No groups found in Trash', 'northam' ),
            'menu_name'          => __( 'Community Groups', 'northam' ),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'community-group', 'with_front' => false ),
        'menu_icon'           => 'dashicons-groups',
        'menu_position'       => 24,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => false,
        'capability_type'     => 'northam_group',
        'map_meta_cap'        => true,
    ) );
}
add_action( 'init', 'northam_register_post_types' );

/**
 * =============================================================================
 * CUSTOM TAXONOMIES
 * =============================================================================
 */

/**
 * Register custom taxonomies
 */
function northam_register_taxonomies() {

    // Business Category
    register_taxonomy( 'business_category', 'northam_business', array(
        'labels' => array(
            'name'          => __( 'Business Categories', 'northam' ),
            'singular_name' => __( 'Business Category', 'northam' ),
            'search_items'  => __( 'Search Categories', 'northam' ),
            'all_items'     => __( 'All Categories', 'northam' ),
            'edit_item'     => __( 'Edit Category', 'northam' ),
            'add_new_item'  => __( 'Add New Category', 'northam' ),
            'menu_name'     => __( 'Categories', 'northam' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'business-category', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    // Venue Type
    register_taxonomy( 'venue_type', 'northam_venue', array(
        'labels' => array(
            'name'          => __( 'Venue Types', 'northam' ),
            'singular_name' => __( 'Venue Type', 'northam' ),
            'search_items'  => __( 'Search Types', 'northam' ),
            'all_items'     => __( 'All Types', 'northam' ),
            'edit_item'     => __( 'Edit Type', 'northam' ),
            'add_new_item'  => __( 'Add New Type', 'northam' ),
            'menu_name'     => __( 'Venue Types', 'northam' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'venue-type', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    // Attraction Category
    register_taxonomy( 'attraction_category', 'northam_attraction', array(
        'labels' => array(
            'name'          => __( 'Attraction Categories', 'northam' ),
            'singular_name' => __( 'Attraction Category', 'northam' ),
            'search_items'  => __( 'Search Categories', 'northam' ),
            'all_items'     => __( 'All Categories', 'northam' ),
            'edit_item'     => __( 'Edit Category', 'northam' ),
            'add_new_item'  => __( 'Add New Category', 'northam' ),
            'menu_name'     => __( 'Categories', 'northam' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'attraction-category', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    // Group Type
    register_taxonomy( 'group_type', 'northam_group', array(
        'labels' => array(
            'name'          => __( 'Group Types', 'northam' ),
            'singular_name' => __( 'Group Type', 'northam' ),
            'search_items'  => __( 'Search Types', 'northam' ),
            'all_items'     => __( 'All Types', 'northam' ),
            'edit_item'     => __( 'Edit Type', 'northam' ),
            'add_new_item'  => __( 'Add New Type', 'northam' ),
            'menu_name'     => __( 'Group Types', 'northam' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'group-type', 'with_front' => false ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'northam_register_taxonomies' );

/**
 * =============================================================================
 * CUSTOM USER ROLES
 * =============================================================================
 */

/**
 * Register custom user roles and capabilities
 */
function northam_register_roles() {
    // Only run once
    if ( get_option( 'northam_roles_version' ) === '1.0.0' ) {
        return;
    }

    // -------------------------------------------------------------------------
    // Business Manager Role
    // -------------------------------------------------------------------------
    add_role( 'business_manager', __( 'Business Manager', 'northam' ), array(
        'read'                       => true,
        'upload_files'               => true,
        // Business capabilities (own only)
        'edit_northam_businesses'    => true,
        'edit_published_northam_businesses' => true,
        'publish_northam_businesses' => true,
        'delete_northam_businesses'  => true,
        // Events Manager
        'edit_events'              => true,
        'edit_published_events'    => true,
        'publish_events'           => true,
        'delete_events'            => true,
        'edit_locations'           => true,
        'publish_locations'        => true,
        'edit_recurring_events'    => true,
        'publish_recurring_events' => true,
    ) );

    // -------------------------------------------------------------------------
    // Venue Manager Role
    // -------------------------------------------------------------------------
    add_role( 'venue_manager', __( 'Venue Manager', 'northam' ), array(
        'read'                     => true,
        'upload_files'             => true,
        // Venue capabilities (own only)
        'edit_northam_venues'      => true,
        'edit_published_northam_venues' => true,
        'publish_northam_venues'   => true,
        'delete_northam_venues'    => true,
        // Events Manager
        'edit_events'              => true,
        'edit_published_events'    => true,
        'publish_events'           => true,
        'delete_events'            => true,
        'edit_locations'           => true,
        'publish_locations'        => true,
        'edit_recurring_events'    => true,
        'publish_recurring_events' => true,
    ) );

    // -------------------------------------------------------------------------
    // Community Group Admin Role
    // -------------------------------------------------------------------------
    add_role( 'group_admin', __( 'Community Group Admin', 'northam' ), array(
        'read'                     => true,
        'upload_files'             => true,
        // Group capabilities (own only)
        'edit_northam_groups'      => true,
        'edit_published_northam_groups' => true,
        'publish_northam_groups'   => true,
        'delete_northam_groups'    => true,
        // Events Manager
        'edit_events'              => true,
        'edit_published_events'    => true,
        'publish_events'           => true,
        'delete_events'            => true,
        'edit_locations'           => true,
        'publish_locations'        => true,
        'edit_recurring_events'    => true,
        'publish_recurring_events' => true,
    ) );

    // -------------------------------------------------------------------------
    // History Writer Role
    // -------------------------------------------------------------------------
    add_role( 'history_writer', __( 'History Writer', 'northam' ), array(
        'read'                    => true,
        'upload_files'            => true,
        // Can write posts but not publish (requires approval)
        'edit_posts'              => true,
        'delete_posts'            => true,
        // Cannot publish - admin must approve
        'publish_posts'           => false,
    ) );

    // -------------------------------------------------------------------------
    // Add capabilities to Administrator
    // -------------------------------------------------------------------------
    $admin = get_role( 'administrator' );
    if ( $admin ) {
        // Business capabilities
        $admin->add_cap( 'edit_northam_business' );
        $admin->add_cap( 'read_northam_business' );
        $admin->add_cap( 'delete_northam_business' );
        $admin->add_cap( 'edit_northam_businesses' );
        $admin->add_cap( 'edit_others_northam_businesses' );
        $admin->add_cap( 'publish_northam_businesses' );
        $admin->add_cap( 'read_private_northam_businesses' );
        $admin->add_cap( 'delete_northam_businesses' );
        $admin->add_cap( 'delete_private_northam_businesses' );
        $admin->add_cap( 'delete_published_northam_businesses' );
        $admin->add_cap( 'delete_others_northam_businesses' );
        $admin->add_cap( 'edit_private_northam_businesses' );
        $admin->add_cap( 'edit_published_northam_businesses' );

        // Venue capabilities
        $admin->add_cap( 'edit_northam_venue' );
        $admin->add_cap( 'read_northam_venue' );
        $admin->add_cap( 'delete_northam_venue' );
        $admin->add_cap( 'edit_northam_venues' );
        $admin->add_cap( 'edit_others_northam_venues' );
        $admin->add_cap( 'publish_northam_venues' );
        $admin->add_cap( 'read_private_northam_venues' );
        $admin->add_cap( 'delete_northam_venues' );
        $admin->add_cap( 'delete_private_northam_venues' );
        $admin->add_cap( 'delete_published_northam_venues' );
        $admin->add_cap( 'delete_others_northam_venues' );
        $admin->add_cap( 'edit_private_northam_venues' );
        $admin->add_cap( 'edit_published_northam_venues' );

        // Group capabilities
        $admin->add_cap( 'edit_northam_group' );
        $admin->add_cap( 'read_northam_group' );
        $admin->add_cap( 'delete_northam_group' );
        $admin->add_cap( 'edit_northam_groups' );
        $admin->add_cap( 'edit_others_northam_groups' );
        $admin->add_cap( 'publish_northam_groups' );
        $admin->add_cap( 'read_private_northam_groups' );
        $admin->add_cap( 'delete_northam_groups' );
        $admin->add_cap( 'delete_private_northam_groups' );
        $admin->add_cap( 'delete_published_northam_groups' );
        $admin->add_cap( 'delete_others_northam_groups' );
        $admin->add_cap( 'edit_private_northam_groups' );
        $admin->add_cap( 'edit_published_northam_groups' );
    }

    update_option( 'northam_roles_version', '1.0.0' );
}
add_action( 'init', 'northam_register_roles' );

/**
 * Restrict users to editing only their own content
 */
function northam_restrict_user_content( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $current_user = wp_get_current_user();
    $restricted_roles = array( 'business_manager', 'venue_manager', 'group_admin' );

    // Check if user has a restricted role
    $has_restricted_role = array_intersect( $restricted_roles, $current_user->roles );

    if ( ! empty( $has_restricted_role ) ) {
        $post_type = $query->get( 'post_type' );
        $restricted_post_types = array( 'northam_business', 'northam_venue', 'northam_group', 'event' );

        if ( in_array( $post_type, $restricted_post_types, true ) ) {
            $query->set( 'author', $current_user->ID );
        }
    }
}
add_action( 'pre_get_posts', 'northam_restrict_user_content' );

/**
 * =============================================================================
 * EVENTS MANAGER INTEGRATION
 * =============================================================================
 */

// Venue/Business to Events Manager Location sync removed
// Events now use a direct relationship field (_northam_event_venue) instead of EM_Location

/**
 * Get related events for a venue
 */
function northam_get_venue_events( $venue_id, $limit = 5 ) {
    if ( ! class_exists( 'EM_Events' ) ) {
        return array();
    }

    // Get all future events using Events Manager API
    $all_events = EM_Events::get( array(
        'scope' => 'future',
        'status' => 1,
    ) );

    // Filter events to only those linked to this venue
    $events = array();
    if ( ! empty( $all_events ) ) {
        foreach ( $all_events as $em_event ) {
            // Check if this event's post has our meta field
            $event_venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );

            if ( $event_venue_id == $venue_id ) {
                $events[] = $em_event;

                // Limit results
                if ( count( $events ) >= $limit ) {
                    break;
                }
            }
        }
    }

    return $events;
}

// Business sync function removed - no longer needed
// Events now link directly to businesses/venues via _northam_event_venue meta field

/**
 * Get related events for a business
 */
function northam_get_business_events( $business_id, $limit = 5 ) {
    if ( ! class_exists( 'EM_Events' ) ) {
        return array();
    }

    // Get all future events using Events Manager API
    $all_events = EM_Events::get( array(
        'scope' => 'future',
        'status' => 1,
    ) );

    // Filter events to only those linked to this business
    $events = array();
    if ( ! empty( $all_events ) ) {
        foreach ( $all_events as $em_event ) {
            // Check if this event's post has our meta field
            $event_venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );

            if ( $event_venue_id == $business_id ) {
                $events[] = $em_event;

                // Limit results
                if ( count( $events ) >= $limit ) {
                    break;
                }
            }
        }
    }

    return $events;
}

/**
 * =============================================================================
 * ATTRACTION SINGLE PAGE REDIRECT
 * =============================================================================
 */

/**
 * Redirect single attraction pages to archive (since attractions are card-only)
 */
function northam_redirect_attraction_singles() {
    if ( is_singular( 'northam_attraction' ) ) {
        wp_safe_redirect( get_post_type_archive_link( 'northam_attraction' ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'northam_redirect_attraction_singles' );

/**
 * Redirect /events to /events-calendar
 */
function northam_redirect_events_to_calendar() {
    if ( preg_match( '#^/events/?$#', $_SERVER['REQUEST_URI'] ) ) {
        wp_redirect( '/events-calendar/', 301 );
        exit;
    }
}
add_action( 'template_redirect', 'northam_redirect_events_to_calendar' );

/**
 * Redirect archive-only business pages to external website or archive
 */
function northam_redirect_archive_only_businesses() {
    if ( is_singular( 'northam_business' ) ) {
        $archive_only = get_post_meta( get_the_ID(), '_northam_archive_only', true );
        if ( $archive_only ) {
            $website = get_post_meta( get_the_ID(), '_northam_website', true );
            if ( $website ) {
                wp_redirect( $website, 301 );
            } else {
                wp_safe_redirect( get_post_type_archive_link( 'northam_business' ), 301 );
            }
            exit;
        }
    }
}
add_action( 'template_redirect', 'northam_redirect_archive_only_businesses' );

/**
 * =============================================================================
 * HELPER FUNCTIONS
 * =============================================================================
 */

/**
 * Get opening hours as formatted string
 */
function northam_get_opening_hours( $post_id ) {
    $hours = get_post_meta( $post_id, '_northam_opening_hours', true );

    if ( empty( $hours ) ) {
        return '';
    }

    // Hours stored as JSON string
    $hours_array = json_decode( $hours, true );

    if ( ! is_array( $hours_array ) ) {
        return $hours; // Return as plain text if not JSON
    }

    $output = '<dl class="northam-opening-hours">';
    $days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );

    foreach ( $days as $day ) {
        if ( isset( $hours_array[ $day ] ) ) {
            $output .= sprintf(
                '<dt>%s</dt><dd>%s</dd>',
                esc_html( ucfirst( $day ) ),
                esc_html( $hours_array[ $day ] )
            );
        }
    }
    $output .= '</dl>';

    return $output;
}

/**
 * Get gallery images
 */
function northam_get_gallery( $post_id, $size = 'northam-gallery' ) {
    $gallery_ids = get_post_meta( $post_id, '_northam_gallery', true );

    if ( empty( $gallery_ids ) ) {
        return array();
    }

    // IDs stored as comma-separated string
    $ids = array_map( 'intval', explode( ',', $gallery_ids ) );
    $images = array();

    foreach ( $ids as $id ) {
        $img = wp_get_attachment_image_src( $id, $size );
        if ( $img ) {
            $images[] = array(
                'id'     => $id,
                'url'    => $img[0],
                'width'  => $img[1],
                'height' => $img[2],
                'alt'    => get_post_meta( $id, '_wp_attachment_image_alt', true ),
            );
        }
    }

    return array_slice( $images, 0, 10 ); // Max 10 images
}

/**
 * Get social media links
 */
function northam_get_social_links( $post_id ) {
    return array(
        'website'   => get_post_meta( $post_id, '_northam_website', true ),
        'facebook'  => get_post_meta( $post_id, '_northam_facebook', true ),
        'instagram' => get_post_meta( $post_id, '_northam_instagram', true ),
        'twitter'   => get_post_meta( $post_id, '_northam_twitter', true ),
    );
}


/**
 * =============================================================================
 * SHORTCODES
 * =============================================================================
 */

/**
 * Business listing shortcode
 * Usage: [northam_businesses category="pubs" limit="6"]
 */
function northam_businesses_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => '',
        'limit'    => 6,
        'columns'  => 3,
    ), $atts, 'northam_businesses' );

    $args = array(
        'post_type'      => 'northam_business',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business_category',
                'field'    => 'slug',
                'terms'    => $atts['category'],
            ),
        );
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p>' . __( 'No businesses found.', 'northam' ) . '</p>';
    }

    ob_start();
    ?>
    <div class="northam-directory-grid" style="--columns: <?php echo intval( $atts['columns'] ); ?>;">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <article class="northam-card northam-listing-card northam-hover-lift">
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
                    if ( $categories && ! is_wp_error( $categories ) ) :
                    ?>
                        <span class="northam-listing-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                    <?php endif; ?>
                    <h3 class="northam-listing-title"><?php the_title(); ?></h3>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="northam-listing-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
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
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'northam_businesses', 'northam_businesses_shortcode' );

/**
 * Upcoming events shortcode
 * Usage: [northam_events limit="3" category="venue-events"]
 */
function northam_events_shortcode( $atts ) {
    if ( ! class_exists( 'EM_Events' ) ) {
        return '<p>' . __( 'Events Manager plugin is required.', 'northam' ) . '</p>';
    }

    $atts = shortcode_atts( array(
        'limit'    => 3,
        'category' => '',
    ), $atts, 'northam_events' );

    $args = array(
        'limit'   => intval( $atts['limit'] ),
        'scope'   => 'future',
        'orderby' => 'event_start',
        'order'   => 'ASC',
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['category'] = $atts['category'];
    }

    $events = EM_Events::get( $args );

    if ( empty( $events ) ) {
        return '<p>' . __( 'No upcoming events.', 'northam' ) . '</p>';
    }

    ob_start();
    ?>
    <div class="northam-events-grid">
        <?php foreach ( $events as $event ) :
            // Determine event type for colour coding
            $categories = get_the_terms( $event->post_id, 'event-categories' );
            $event_type = 'venue'; // default
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
    <?php
    return ob_get_clean();
}
add_shortcode( 'northam_events', 'northam_events_shortcode' );

/**
 * =============================================================================
 * FLUSH REWRITE RULES ON ACTIVATION
 * =============================================================================
 */

/**
 * Flush rewrite rules when theme is activated
 */
function northam_theme_activation() {
    northam_register_post_types();
    northam_register_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'northam_theme_activation' );

/**
 * =============================================================================
 * EXCERPT LENGTH CUSTOMIZATION
 * =============================================================================
 */

/**
 * Increase excerpt length for attraction cards
 * Since attractions link to external websites (no single pages),
 * they need longer excerpts to provide adequate context
 */
function northam_custom_excerpt_length( $length ) {
    if ( is_post_type_archive( 'northam_attraction' ) || is_tax( 'attraction_category' ) ) {
        return 80; // Increased from default 55 words for better context
    }
    return $length;
}
add_filter( 'excerpt_length', 'northam_custom_excerpt_length', 999 );

/**
 * =============================================================================
 * AJAX HANDLERS
 * =============================================================================
 */

/**
 * AJAX handler for weekly events pagination
 */
function northam_load_weekly_events() {
	// Verify nonce
	check_ajax_referer( 'northam_events_nonce', 'nonce' );

	$business_id = isset( $_POST['business_id'] ) ? absint( $_POST['business_id'] ) : 0;
	$week_offset = isset( $_POST['week_offset'] ) ? absint( $_POST['week_offset'] ) : 0;

	if ( ! $business_id ) {
		wp_send_json_error( array( 'message' => 'Invalid business ID' ) );
	}

	$events = array();

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

				if ( $venue_id == $business_id ) {
					// Check if event is within the current week
					if ( $em_event->start >= $week_start && $em_event->start <= $week_end ) {
						$events[] = array(
							'id' => $em_event->event_id,
							'name' => $em_event->event_name,
							'post_id' => $em_event->post_id,
							'start_date' => date_i18n( 'D, jS M', strtotime( $em_event->event_start_date ) ),
							'start_time' => date_i18n( 'g:i A', strtotime( $em_event->event_start_time ) ),
							'map_url' => get_post_meta( $em_event->post_id, '_northam_event_map_url', true ),
							'thumbnail' => get_the_post_thumbnail( $em_event->post_id, 'thumbnail' ),
						);
					}
				}
			}
		}
	}

	// Format week heading
	$week_start_date = date_i18n( 'j M', $week_start );
	$week_end_date = date_i18n( 'j M', $week_end );

	if ( $week_offset === 0 ) {
		$heading = __( 'This Week\'s Events', 'northam' );
	} else {
		$heading = sprintf( __( 'Events: %s - %s', 'northam' ), $week_start_date, $week_end_date );
	}

	wp_send_json_success( array(
		'events' => $events,
		'heading' => $heading,
		'week_offset' => $week_offset,
	) );
}
add_action( 'wp_ajax_northam_load_weekly_events', 'northam_load_weekly_events' );
add_action( 'wp_ajax_nopriv_northam_load_weekly_events', 'northam_load_weekly_events' );

/**
 * Enqueue events pagination JavaScript on business and venue pages
 */
function northam_enqueue_events_ajax() {
	if ( is_singular( 'northam_business' ) || is_singular( 'northam_venue' ) ) {
		wp_enqueue_script(
			'northam-events-ajax',
			NORTHAM_THEME_URI . '/assets/js/events-pagination.js',
			array( 'jquery' ),
			NORTHAM_VERSION,
			true
		);

		wp_localize_script(
			'northam-events-ajax',
			'northamEvents',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'northam_events_nonce' ),
				'businessId' => get_the_ID(), // Works for both businesses and venues
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'northam_enqueue_events_ajax' );

/**
 * =============================================================================
 * REGULAR CLASSES SCRAPER
 * =============================================================================
 */

/**
 * AJAX handler to fetch and parse classes from Town Council website
 */
function northam_fetch_classes_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'northam_fetch_classes' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
    }

    // Check permissions
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( array( 'message' => 'Permission denied.' ) );
    }

    $url = isset( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';

    if ( empty( $url ) ) {
        wp_send_json_error( array( 'message' => 'No URL provided.' ) );
    }

    // Validate it's a Northam Town Council URL
    if ( strpos( $url, 'northamtowncouncil.gov.uk' ) === false ) {
        wp_send_json_error( array( 'message' => 'URL must be from northamtowncouncil.gov.uk' ) );
    }

    // Fetch the page
    $response = wp_remote_get( $url, array(
        'timeout' => 30,
        'user-agent' => 'Mozilla/5.0 (WordPress/' . get_bloginfo( 'version' ) . ')',
    ) );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( array( 'message' => 'Failed to fetch URL: ' . $response->get_error_message() ) );
    }

    $html = wp_remote_retrieve_body( $response );

    if ( empty( $html ) ) {
        wp_send_json_error( array( 'message' => 'Empty response from URL.' ) );
    }

    // Parse the HTML to extract classes
    $classes = northam_parse_classes_html( $html );

    if ( empty( $classes ) ) {
        wp_send_json_error( array( 'message' => 'No classes found on the page. The page structure may have changed.' ) );
    }

    wp_send_json_success( array( 'classes' => $classes ) );
}
add_action( 'wp_ajax_northam_fetch_classes', 'northam_fetch_classes_ajax' );

/**
 * Parse HTML from Town Council page to extract class data
 *
 * Structure: <h5>MONDAYS</h5> followed by <h6>Class Name (Time) Frequency <a>Contact</a></h6>
 */
function northam_parse_classes_html( $html ) {
    $classes = array();

    // Day mapping (uppercase to proper case)
    $day_map = array(
        'MONDAY' => 'Monday',
        'MONDAYS' => 'Monday',
        'TUESDAY' => 'Tuesday',
        'TUESDAYS' => 'Tuesday',
        'WEDNESDAY' => 'Wednesday',
        'WEDNESDAYS' => 'Wednesday',
        'THURSDAY' => 'Thursday',
        'THURSDAYS' => 'Thursday',
        'FRIDAY' => 'Friday',
        'FRIDAYS' => 'Friday',
        'SATURDAY' => 'Saturday',
        'SATURDAYS' => 'Saturday',
        'SUNDAY' => 'Sunday',
        'SUNDAYS' => 'Sunday',
    );

    // Use DOMDocument to parse HTML
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    $dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ) );
    libxml_clear_errors();

    $xpath = new DOMXPath( $dom );

    // Find all h5 elements (day headers)
    $h5_elements = $xpath->query( '//h5' );

    foreach ( $h5_elements as $h5 ) {
        $day_text = strtoupper( trim( $h5->textContent ) );

        // Check if this is a day header
        $current_day = null;
        foreach ( $day_map as $key => $value ) {
            if ( strpos( $day_text, $key ) !== false ) {
                $current_day = $value;
                break;
            }
        }

        if ( ! $current_day ) {
            continue;
        }

        $classes[ $current_day ] = array();

        // Find all following h6 elements until next h5
        $sibling = $h5->nextSibling;
        while ( $sibling ) {
            if ( $sibling->nodeType === XML_ELEMENT_NODE ) {
                // Stop if we hit another h5
                if ( strtolower( $sibling->nodeName ) === 'h5' ) {
                    break;
                }

                // Process h6 elements
                if ( strtolower( $sibling->nodeName ) === 'h6' ) {
                    $class_data = northam_parse_class_entry( $sibling );
                    if ( $class_data ) {
                        $classes[ $current_day ][] = $class_data;
                    }
                }
            }
            $sibling = $sibling->nextSibling;
        }

        // Remove day if no classes found
        if ( empty( $classes[ $current_day ] ) ) {
            unset( $classes[ $current_day ] );
        }
    }

    return $classes;
}

/**
 * Parse a single class entry from an h6 element
 *
 * Format: "Class Name (9:30 - 10:30) Weekly" or "Class Name (9:30 - 10:30) 1st and 3rd week"
 * May contain <a> tag with contact link
 */
function northam_parse_class_entry( $element ) {
    $full_text = trim( $element->textContent );

    // Clean up unicode non-breaking spaces and other special characters
    $full_text = str_replace( array( "\u{00a0}", "\xc2\xa0", '&nbsp;' ), ' ', $full_text );
    $full_text = preg_replace( '/\s+/', ' ', $full_text ); // Normalize whitespace
    $full_text = trim( $full_text );

    if ( empty( $full_text ) ) {
        return null;
    }

    // Extract contact from <a> tag if present
    $contact = '';
    $links = $element->getElementsByTagName( 'a' );
    if ( $links->length > 0 ) {
        $link = $links->item( 0 );
        $href = $link->getAttribute( 'href' );
        // Extract domain or use link text
        if ( $href ) {
            $parsed = parse_url( $href );
            $contact = isset( $parsed['host'] ) ? preg_replace( '/^www\./', '', $parsed['host'] ) : $link->textContent;
        }
    }

    // Extract time from parentheses: (9:30 - 10:30) or (9:30-10:30)
    $time = '';
    if ( preg_match( '/\((\d{1,2}[:.]\d{2}\s*[-–]\s*\d{1,2}[:.]\d{2})\)/', $full_text, $matches ) ) {
        $time = str_replace( '.', ':', $matches[1] );
        $time = preg_replace( '/\s*[-–]\s*/', ' - ', $time ); // Normalize dash
    }

    // Extract frequency keywords
    $frequency = 'Weekly'; // Default
    $freq_patterns = array(
        '/1st\s*(and|&)\s*3rd\s*week/i' => '1st & 3rd weeks',
        '/2nd\s*(and|&)\s*4th\s*week/i' => '2nd & 4th weeks',
        '/1st\s*week/i' => '1st week',
        '/2nd\s*week/i' => '2nd week',
        '/3rd\s*week/i' => '3rd week',
        '/4th\s*week/i' => '4th week',
        '/last\s*(week|[a-z]+day)/i' => 'Last week',
        '/1st\s*[a-z]+day/i' => '1st of month',
        '/2nd\s*[a-z]+day/i' => '2nd of month',
        '/3rd\s*[a-z]+day/i' => '3rd of month',
        '/4th\s*[a-z]+day/i' => '4th of month',
        '/last\s*[a-z]+day/i' => 'Last of month',
        '/weekly/i' => 'Weekly',
    );

    foreach ( $freq_patterns as $pattern => $label ) {
        if ( preg_match( $pattern, $full_text ) ) {
            $frequency = $label;
            break;
        }
    }

    // Extract class name (everything before the time parentheses)
    $name = $full_text;
    // Remove time
    $name = preg_replace( '/\(\d{1,2}[:.]\d{2}\s*[-–]\s*\d{1,2}[:.]\d{2}\)/', '', $name );
    // Remove frequency keywords
    $name = preg_replace( '/(weekly|1st|2nd|3rd|4th|last)\s*(and|&)?\s*(week|[a-z]+day)?/i', '', $name );
    // Remove "Weblink" text
    $name = preg_replace( '/\s*weblink\s*/i', '', $name );
    // Clean up
    $name = trim( preg_replace( '/\s+/', ' ', $name ) );

    if ( empty( $name ) ) {
        return null;
    }

    return array(
        'name'      => northam_clean_text( $name ),
        'time'      => northam_clean_text( $time ),
        'frequency' => northam_clean_text( $frequency ),
        'contact'   => northam_clean_text( $contact ),
    );
}

/**
 * Clean text by removing non-breaking spaces and normalizing whitespace
 */
function northam_clean_text( $text ) {
    if ( empty( $text ) ) {
        return '';
    }
    // Remove various forms of non-breaking space
    $text = str_replace( array( "\u{00a0}", "\xc2\xa0", '&nbsp;', chr( 194 ) . chr( 160 ) ), ' ', $text );
    // Normalize whitespace
    $text = preg_replace( '/\s+/', ' ', $text );
    return trim( $text );
}

/**
 * =============================================================================
 * INCLUDE ADDITIONAL FILES
 * =============================================================================
 */

// Meta boxes (ACF alternative for custom fields)
require_once NORTHAM_THEME_DIR . '/inc/meta-boxes.php';

// Block patterns
require_once NORTHAM_THEME_DIR . '/inc/block-patterns.php';

// Sample data importer (admin only)
if ( is_admin() ) {
    require_once NORTHAM_THEME_DIR . '/inc/sample-data.php';
}
