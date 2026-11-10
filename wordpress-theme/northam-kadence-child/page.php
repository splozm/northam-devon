<?php
/**
 * Generic Page Template
 *
 * Used for standard pages (About, Privacy Policy, etc.).
 * front-page.php and page-{slug}.php templates still take priority.
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main northam-archive-page northam-page">
    <div class="content-container">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'northam-page-article' ); ?>>

                <header class="northam-archive-header northam-page-header">
                    <div>
                        <h1 class="northam-archive-title"><?php the_title(); ?></h1>
                        <?php if ( has_excerpt() ) : ?>
                            <div class="northam-archive-description">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="northam-page-hero">
                        <?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
                    </figure>
                <?php endif; ?>

                <div class="northam-page-content entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<nav class="northam-page-links">' . esc_html__( 'Pages:', 'northam' ),
                        'after'  => '</nav>',
                    ) );
                    ?>
                </div>

            </article>

        <?php endwhile; ?>

        <?php if ( ! have_posts() ) : ?>
            <div class="northam-no-results">
                <h3><?php esc_html_e( 'Page not found', 'northam' ); ?></h3>
                <p><?php esc_html_e( 'Sorry, there\'s nothing here.', 'northam' ); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
