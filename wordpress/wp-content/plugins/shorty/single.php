<?php
/**
 * The Template for displaying all single posts.
 *
 */

// include WXR file parsers
//require dirname( __FILE__ ) . '/debug.php';

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $img_url_plugin = 'etsy-image-url-sanitizer/etsy-image-url-sanitizer.php';
    if (is_plugin_active($img_url_plugin)) {
        if ( class_exists('Etsy_Content_Filter') ) {
            $ecf = new Etsy_Content_Filter();
        }
    }
    
    $blog_event_name = get_blog_event_name();
    $author = etsy_get_author($post->post_author);
    $category_list = list_the_categories();
    $tag_list = list_the_tags();
    if (empty($tag_list)) {
        $tag_list = 'untagged';
    }
    $permalink = get_permalink();
    
    if( ! isset($etsy_user) && (class_exists('EtsyUserIntegration'))) {
        $etsy_user = EtsyUserIntegration::get_user();
    }
    
    if (isset($etsy_user->user_id)) {
        $etsy_user_id = $etsy_user->user_id;
    } else {
        $etsy_user_id = 0;
    }

    $browser_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];

    $page_beacons = array ( 
        'primary_event' => 1,
        'php_event_name' => $blog_event_name . '_article',
        'blog_post_id' => $post->ID,
        'post_author' => $author->user_id,
        'etsy_user_id' => $etsy_user_id,
        'browser_langauge' => $browser_language,
        'category' => $category_list,
        'post_tags' => $tag_list
    );

    if (isset($_COOKIE['NEW_COMMENT_BEACON'])){
        $page_beacons['new_comment'] = absint($_COOKIE['NEW_COMMENT_BEACON']);
    }

    $beacon = get_beacon();
    if ($beacon){
       $beacon->set_custom_beacons($page_beacons);
       add_action('wp_footer', array($beacon, 'render_beacon_footer' ), 100);
    }
    
    get_header(); ?>
    <div id="container">
	    <div id="content" role="main">

<?php
	if (have_posts()){
		the_post();
?>

    <div id="article-view">
        <?php
            $cat= get_the_category();
            $cat= isset($cat[0]) ? $cat[0] : null;
            $userdata= get_userdata($post->post_author);
        ?>
        
        <div id="post-<?php the_ID(); ?>" class="article-view">
        
    <?php include('share-tools.php'); ?>

    <div class="clear"></div>
    
            <h1 class='entry-title'><?php the_title(); ?></h1>
            <?php
                if(StorqueHeaderMedia::hasVideo($post->ID) ){
                    echo StorqueHeaderMedia::getVideo($post->ID);
                }else if (StorqueHeaderMedia::hasImage($post->ID)){
                    $header_media_image = StorqueHeaderMedia::getImage($post->ID);

                    if ( isset( $ecf )) {
                        $header_media_image = $ecf->sanitize_image_url( $header_media_image );
                    }

                    echo $header_media_image;

                }else if (StorqueHeaderMedia::hasSlideshow($post->ID)){
                    echo StorqueHeaderMedia::getSlideshow($post->ID);
                }
            ?>


    <div class='byline'>
        <?php 
        $avatar_image = storque_get_avatar(get_the_author(), 25);

        if ( isset( $ecf )) {
            $avatar_image = $ecf->sanitize_image_url( $avatar_image );
        }

        echo $avatar_image;
        ?>
        <p class='store-by'>
            <?php printf(__('Story by %s', 'storque'), '<a href="'. generate_author_page_url($userdata->user_login) . '" class="profile">' . get_etsy_username($userdata->ID) .'</a>'); ?>
        </p>
        <p class='published'>
            <?php if ($cat): ?>
                <?php /* translators: %1$s: date %2$s: category */ ?>
                <?php printf(__('Published on %1$s in %2$s', 'storque'),
                             '<span class="posted">' . get_pub_date($post) . '</span>',
                             '<a href="' . get_category_link($cat->term_id) .'" class="category">' .$cat->name .'</a>'); ?>
            <?php else: ?>
                <?php printf(__('Published on %s', 'storque'), '<span class="posted">' . get_pub_date($post) . '</span>'); ?>
            <?php endif ?>
        </p>
        <?php echo StorqueHeaderMedia::getCredits($post->ID); ?>
    </div>
    <div class='entry-content'>
        <?php
            global $blog_id;
            $this_blogs_info = get_blog_details(array('blog_id'=>$blog_id));
            // The weddings blog does not need the user description info to display, other blogs do.
            $wedding_regex = '#' . WEDDINGS_BLOG_BASE_URI . '/#';
            if (!preg_match($wedding_regex, $this_blogs_info->path)
                && storque_user_photo_show('photo')
                && storque_user_photo($userdata->ID)
                ) :?>
            <p><a href="<?php echo generate_author_page_url($userdata->user_login); ?>">
                    <img src="<?php echo storque_user_photo($userdata->ID); ?>" alt="<?php echo $userdata->user_login ?>" class="author-photo" />
            </a></p>
        <?php endif;
        $content = apply_filters('the_content', get_the_content());

        if ( isset( $ecf )) {
            $content = $ecf->sanitize_image_url( $content );
        }

        echo $content;
        if (storque_user_photo_show('bio') && isset($userdata->description) && $userdata->description != '') :?>
            <p><em><?php echo $userdata->description; ?></em></p>
       <?php endif; ?>

    </div>

        <?php
        //echo get_discuss_block();
        include('share-tools.php');
		storque_outbrain_display();
        storque_seller_listings();
        storque_related_ids();
        if(current_user_can( 'edit_others_posts')) : ?>
            <?php echo etsy_get_edit_post_link($post->ID); ?>
        <?php endif; ?>
    </div><?php // end post div ?>

                <?php comments_template( '', true ); ?>
            </div><?php // end #article-view ?>
        <?php } ?>
        </div><?php // end #content 

get_sidebar();
get_footer();
?>
