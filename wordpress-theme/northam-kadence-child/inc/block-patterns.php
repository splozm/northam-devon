<?php
/**
 * Block Patterns for Northam Theme
 *
 * @package Northam
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function northam_register_pattern_category() {
    register_block_pattern_category( 'northam', array( 'label' => __( 'Northam', 'northam' ) ) );
}
add_action( 'init', 'northam_register_pattern_category' );

function northam_register_block_patterns() {

    register_block_pattern( 'northam/hero-section', array(
        'title' => __( 'Hero Section', 'northam' ),
        'categories' => array( 'northam', 'featured' ),
        'content' => '<!-- wp:cover {"customOverlayColor":"#267373","minHeight":70,"minHeightUnit":"vh"} -->
<div class="wp-block-cover" style="min-height:70vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#267373"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"clamp(3rem, 2rem + 5vw, 5rem)"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:clamp(3rem, 2rem + 5vw, 5rem)">Northam, Devon</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.25rem">A charming coastal village where community spirit meets Devon\'s stunning shores</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="/events">Explore Events</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","textColor":"white"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/things-to-do">Discover Northam</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->',
    ) );

    register_block_pattern( 'northam/stats-section', array(
        'title' => __( 'Quick Stats', 'northam' ),
        'categories' => array( 'northam' ),
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem"}}},"backgroundColor":"sand-light","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-sand-light-background-color has-background" style="padding-top:3rem;padding-bottom:3rem"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"2.5rem"}}} --><h3 class="wp-block-heading has-text-align-center" style="font-size:2.5rem">12+</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center","textColor":"muted-foreground"} --><p class="has-text-align-center has-muted-foreground-color has-text-color">Upcoming Events</p><!-- /wp:paragraph --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"2.5rem"}}} --><h3 class="wp-block-heading has-text-align-center" style="font-size:2.5rem">45+</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center","textColor":"muted-foreground"} --><p class="has-text-align-center has-muted-foreground-color has-text-color">Local Businesses</p><!-- /wp:paragraph --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"2.5rem"}}} --><h3 class="wp-block-heading has-text-align-center" style="font-size:2.5rem">8</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center","textColor":"muted-foreground"} --><p class="has-text-align-center has-muted-foreground-color has-text-color">Community Groups</p><!-- /wp:paragraph --></div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"2.5rem"}}} --><h3 class="wp-block-heading has-text-align-center" style="font-size:2.5rem">20+</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center","textColor":"muted-foreground"} --><p class="has-text-align-center has-muted-foreground-color has-text-color">Things to Do</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
    ) );

    register_block_pattern( 'northam/upcoming-events', array(
        'title' => __( 'Upcoming Events', 'northam' ),
        'categories' => array( 'northam' ),
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"accent"} --><p class="has-accent-color has-text-color" style="letter-spacing:0.05em;text-transform:uppercase">What\'s On</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Upcoming Events</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/events">View All Events</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->
<!-- wp:shortcode -->[northam_events limit="3"]<!-- /wp:shortcode --></div><!-- /wp:group -->',
    ) );

    register_block_pattern( 'northam/cta-banner', array(
        'title' => __( 'CTA Banner', 'northam' ),
        'categories' => array( 'northam' ),
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:cover {"customOverlayColor":"#3d9999","minHeight":300,"style":{"border":{"radius":"1.5rem"}}} -->
<div class="wp-block-cover" style="border-radius:1.5rem;min-height:300px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#3d9999"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"600px","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:heading {"textColor":"white"} --><h2 class="wp-block-heading has-white-color has-text-color">Join Our Community</h2><!-- /wp:heading --><!-- wp:paragraph {"textColor":"white"} --><p class="has-white-color has-text-color">From the WI to sports clubs, Northam has a vibrant community waiting to welcome you.</p><!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent"} --><div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="/community">Explore Groups</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover --></div><!-- /wp:group -->',
    ) );

}
add_action( 'init', 'northam_register_block_patterns' );
