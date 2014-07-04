<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_sidebar();


    if (class_exists('Shorty')) {
        $s = new Shorty;
        $url = get_permalink($post->ID);
        print("<!-- Post Permalink\n" . PHP_EOL);
        print($url);
        print("\n-->\n" . PHP_EOL);
        $s->prepare_api_request($url);
        $s->display_bitly_api_request();
        $s->bitly_get_request();
        $s->decode_bitly_result();
        $s->display_bitly_result();

        print('<!-- Shorty Social: ' . $post->post_title . ' ' . $s->get_short_url() . ' -->' . PHP_EOL);        
    }

get_footer();
