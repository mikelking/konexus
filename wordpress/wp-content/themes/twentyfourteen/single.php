<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

					// Previous/next post navigation.
					twentyfourteen_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();

if (class_exists('Shorty')) {
    $s = new Shorty;
    $url = get_permalink($post->ID);
/*
    print("<!-- Post Permalink\n" . PHP_EOL);
    print($url);
    print("\n-->\n" . PHP_EOL);
*/
/*
    $s->prepare_api_request($url);
    $s->display_bitly_api_request();
    $s->bitly_get_request();
    $s->decode_bitly_result();
    $s->display_bitly_result();
*/

    print('<!-- Shorty Social: ' . $post->post_title . ' ' . $s->get_short_url() . ' -->' . PHP_EOL);        
}


get_footer();
