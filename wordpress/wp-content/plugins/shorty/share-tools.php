<?php

function get_comment_bubble_icon() {
    $icon_name = 'comment_bubble_icon.png';
    
    if (class_exists('Advanced_Blog_Data')) {
       $abd = new Advanced_Blog_Data();
    } elseif (file_exists(ETSY_WPLIB . DIRECTORY_SEPARATOR . 'advanced_blog_data.php')){
       require(ETSY_WPLIB . DIRECTORY_SEPARATOR . 'advanced_blog_data.php');
       $abd = new Advanced_Blog_Data();
    } else {
        return($the_blog_path);
    }
    $icon_fmt = "<img src=\"%s://%s%swp-content/themes/storque/images/%s\" width=\"24\" height=\"24\">";
    return(sprintf($icon_fmt, THE_PROTOCOL, DOMAIN_CURRENT_SITE, $abd->the_blog_path, $icon_name));
}

?>
    <div class="share-tools">
        <li class="twitter">
            <a href="https://twitter.com/share"
                class="twitter-share-button"
                data-url="<?php echo get_short_url($post); ?>"
                data-text="<?php echo the_title() . " on Etsy"; ?>"
                data-via="Etsy"
                rel="nofollow"
                target="_blank"
                title="Share on twitter">Tweet</a>
            <script>
                !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
            </script>
        </li>
        <li class="fb-like">
            <fb:like
                id="share1-fb-like"
                href="<?php echo get_short_url($post); ?>" 
                data-width="120" 
                height="25" 
                scrolling="no" 
                layout="button_count" 
                show_faces="false" 
                ref="like_button"></fb:like>
        </li>
        <li class="gplus">
            <g:plusone size="medium" href="<?php echo get_short_url($post); ?>"></g:plusone>
        </li>
        <li class="pinterest">
            <a class="etsy-pin-it" href="http://pinterest.com/pin/create/button/?url=<?php echo get_short_url($post); ?>&media=<?php echo StorqueHeaderMedia::getSmallImageUrl($post->ID); ?>&description=<?php echo the_title() . " on Etsy"; ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
        </li>
        <li class="tumblr">
        <a href="http://www.tumblr.com/share/photo?source=<?php echo urlencode(StorqueHeaderMedia::getSmallImageUrl($post->ID)); ?>&caption=<?php echo urlencode(get_the_title() . " on Etsy"); ?>&clickthru=<?php echo urlencode(get_short_url($post)); ?>" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:63px; height:20px; background:url('http://platform.tumblr.com/v1/share_2.png') top left no-repeat transparent;">Share on Tumblr</a>
        </li>
        <li class="comment_bubble">
            <a href="<?php echo the_permalink(); ?>#comments" title="Comment on <?php echo the_title() . " on Etsy"; ?>"><?php
             echo get_comments_number();
            ?></a>
        </li>
    </div>
