<?php
/*
    Plugin Name: Header Base Class
    Version: 1.9
    Description: Adds a standardized base class for registering and enqueuing the default styles and scripts required by the header.php
    Author: Mikel King
    Author URI: http://mikelking.com
    License: BSD(3 Clause)
*/

class Header_Base {
    const VERSION         = '1.9';
    const IN_HEADER       = false;
    const IN_FOOTER       = true;
    const PROD_URL        = '//www.rd.com';
    const CSS_SUFFIX      = '.css';
    const MIN_CSS_SUFFIX  = '.min.css';
    const JS_SUFFIX       = '.js';
    const MIN_JS_SUFFIX   = '.min.js';
    const JQ_171          = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
    const MARKETING       = 'http://us.readersdigest.com/js/smart_marketing_library.js';
    const RD_STYLES       = 'https://s3-us-west-2.amazonaws.com/contentad/stylesheets/rd_styles.css';
    const CRWDCTRL_SCRIPT = 'http://tags.crwdcntrl.net/c/2211/cc.js?ns=_cc2211';

    private $template_uri;
    private $js_template_url;
    private $css_template_uri;
    private static $instance = array();
    
    protected static $initialized = false;
    
    public static $header_file;
    public static $footer_file;
    
    public $abd;

    public function __construct() {
        $this->template_uri = get_template_directory_uri();
        $this->css_template_uri = get_template_directory_uri() . '/css/';
        $this->js_template_url = get_template_directory_uri() . '/js/';
        $this->abd =  new Advanced_Blog_Data();
        
        add_action( 'wp_enqueue_scripts', array($this, 'register_header_styles' ));
        add_action( 'wp_enqueue_scripts', array($this, 'register_header_scripts' ));
    }

    public function register_header_styles() {
        $this->get_css_suffix();
    
        wp_register_style('main', $this->template_uri . '/style.css');
        wp_register_style('post', $this->css_template_uri . 'post.css');
        wp_register_style('post-style', $this->css_template_uri . 'post-style.css');
        wp_register_style('home', $this->css_template_uri . 'home.css');
        wp_register_style('post-alltopics-style', $this->css_template_uri . 'post-alltopics-style.css');
        wp_register_style('reset', $this->css_template_uri . 'reset.css');
        wp_register_style('rd_styles', self::RD_STYLES);
        wp_register_style('slideshow', $this->css_template_uri .'slideshow_style.css');
        wp_register_style('homepage', $this->css_template_uri .'home.css');
        wp_register_style('homepage-slideshow', $this->css_template_uri .'slideshow.css');
        wp_register_style('category-post', $this->css_template_uri .'category-and-post.css');
        wp_register_style('humor-post', $this->css_template_uri .'humor.css');
        
        
        if ( is_front_page() || is_home() ) {
            $this->enq_frontpage_styles();
            return;
        } 
        if ( is_category() ) {
            $this->enq_category_styles();
            return;
        }
        
        if ( get_post_type(get_the_ID()) === 'laughs')  {
            $this->enq_jokes_tmpl_styles();
            return;
        } 
        
        
        if ( get_post_type(get_the_ID()) === 'slideshows') {
            $this->enq_slideshow_styles();
            return;
        }

        if ( is_single() ) {
            $this->enq_post_styles();
            return;
        }

        if ( is_page() ) {
            $this->enq_page_styles();
            return;
        }
    }
    
    public function enq_frontpage_styles() {
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('reset');
        wp_enqueue_style('main');
        wp_enqueue_style('homepage');
        wp_enqueue_style('homepage-slideshow');
        
    }
    public function enq_category_styles() {
        
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('category-post');
        wp_enqueue_style('reset');
        wp_enqueue_style('main');
        wp_enqueue_style('humor-post');
        wp_enqueue_style('homepage-slideshow');
        
    }
    public function enq_jokes_tmpl_styles() {
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('main');
        wp_enqueue_style('humor-post');
        wp_enqueue_style('post');
    }
    
    
        
    public function enq_slideshow_styles() { 
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('reset');
        wp_enqueue_style('main');
        wp_enqueue_style('post');
        wp_enqueue_style('slideshow');
    }

    public function enq_page_styles() {
        wp_enqueue_style('main');
        wp_enqueue_style('post');
        wp_enqueue_style('post-style');
        wp_enqueue_style('home');
        wp_enqueue_style('post-alltopics-style');
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('reset');
    }

    public function enq_post_styles() {
        wp_enqueue_style('rd_styles');
        wp_enqueue_style('reset');
        wp_enqueue_style('main');
        wp_enqueue_style('post');
    }

    public function register_header_scripts() {
        $this->get_js_suffix();
        $placement = self::IN_HEADER;
        if ($this->in_footer()) {
            $placement = self::IN_FOOTER;
        }

        wp_register_script(
                            'web-fonts',
                            $this->js_template_url . 'web-fonts.js',
                            null,
                            self::VERSION,
                            $placement
                          );

        /*
            jquery-1-7-1-min must stay in the header or the site nav will not render.
        */
        wp_register_script('jquery-1-7-1-min', self::JQ_171, null, '1.7.1', self::IN_HEADER);
        
        wp_register_script(
                            'jquery-cookie',
                            $this->js_template_url . 'jquery.cookie.js',
                            null,
                            self::VERSION,
                            $placement
                          );
        wp_register_script(
                            'write-capture',
                            $this->js_template_url . 'write-capture.js',
                            null,
                            self::VERSION,
                            $placement
                          );
        wp_register_script(
                            'jquery-write-capture',
                            $this->js_template_url . 'jquery.writeCapture.js',
                            array('write-capture'),
                            self::VERSION,
                            $placement
                          );
        wp_register_script(
                            'jquery-ui-1-8-6-min',
                            $this->js_template_url . 'jquery-ui-1.8.6.custom.min.js',
                            null, 
                            '1.8.6',
                            $placement
                          );
        wp_register_script(
                            'tynt',
                            $this->js_template_url . 'tynt.js',
                            null,
                            self::VERSION,
                            $placement
                          );
        wp_register_script(
                            'async-loader',
                            $this->js_template_url . 'async_loader.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
        wp_register_script(
                            'top-menu',
                            $this->js_template_url . 'top_menu.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
        wp_register_script(
                            'rd-share-tools',
                            $this->js_template_url . 'rd-share-tools.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
        wp_register_script(
                            'slideshow-nav',
                            $this->js_template_url . 'rd.plugins.js',
                            null,
                            self::VERSION,
                            self::IN_FOOTER
                          );
        wp_register_script(
                            'footer-analytics',
                            $this->js_template_url . 'footer-analytics.js',
                            null,
                            self::VERSION,
                            self::IN_FOOTER
                          );
        wp_register_script(
                            'floodlight',
                            $this->js_template_url . 'floodlight.js',
                            null,
                            self::VERSION,
                            self::IN_FOOTER
                          );
                          
        wp_register_script(
                            'google-tag-mgr',
                            $this->js_template_url . 'google-tag-mgr.js',
                            null,
                            self::VERSION,
                            $placement
                          );
        wp_register_script(
                            'crowdctl_tag',
                            self::CRWDCTRL_SCRIPT,
                            null,
                            self::VERSION,
                            self::IN_FOOTER
                          );
        wp_register_script(
                            'comscore-analytic',
                           $this->js_template_url. 'comscore.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
         wp_register_script(
                            'marquee-slider',
                           $this->js_template_url. 'jquery.sudoSlider.min.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
         wp_register_script(
                            'jquery-color',
                           $this->js_template_url. 'jquery.color.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
         wp_register_script(
                            'jquery-easing',
                           $this->js_template_url. 'jquery-easing.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
        wp_register_script(
                            'jquery',
                           $this->js_template_url. 'jquery.js',
                            null,
                            self::VERSION,
                            self::IN_HEADER
                          );
        wp_localize_script( 'crowdctl_tag' ,'crowdctl_tag_params', array('_cc2211'=>self::CRWDCTRL_SCRIPT));
        
        $this->enq_ad_scripts();
        
        if ( is_front_page() || is_home() ) {
            $this->enq_frontpage_scripts();
            return;
        }
        
        if ( is_category() ) {
            $this->enq_category_scripts();
           return;
        }

        if ( get_post_type(get_the_ID()) === 'laughs')  {
            $this->enq_jokes_tmpl_scripts();
            return;
        } 

        if ( get_post_type(get_the_ID()) === 'slideshows') {
            $this->enq_slidewhow_scripts();
            return;
        }
        
        if (is_single()) {
            $this->enq_post_scripts();
            $this->localize_post_scripts();
            return;
        } 
        
        $this->enq_default_scripts();
    }
    
    public function localize_post_scripts() {
        $title_base = ' | Reader\'s Digest';
        $post_title = preg_replace("/&#?[a-z0-9]{2,8};/i","", urldecode(get_the_title() . $title_base));
        $the_permalink = urlencode(get_permalink());
        $email_share_url = get_bloginfo('template_url') . '/lib/functions/email_share_ui.php?sharelink=';
        
        $rd_data = array(
                            'post_title'       => $post_title,
                            'the_permalink'    => $the_permalink,
                            'email_share_url'  => $email_share_url
                        );
        
        wp_localize_script( 'rd-share-tools', 'rd_data', $rd_data );
    }
    
    public function enq_default_scripts() {
            wp_enqueue_script('async-loader');
            wp_enqueue_script('web-fonts');
            wp_enqueue_script('jquery-1-7-1-min');
            wp_enqueue_script('jquery-cookie');
            wp_enqueue_script('write-capture');
            wp_enqueue_script('jquery-write-capture');
            wp_enqueue_script('jquery-ui-1-8-6-min');
            wp_enqueue_script('tynt');
            wp_enqueue_script('top-menu');
            wp_enqueue_script('slideshow-nav');
            wp_enqueue_script('footer-analytics');
        }
    
    public function enq_ad_scripts() {
        /*
            Banner between nav & content
            @TODO download and compress
        */
        if ( ! $this->inhibit_ads()) {
            wp_register_script(
                                'smart-marketing-lib',
                                self::MARKETING,
                                null, 
                                self::VERSION,
                                $placement);
            wp_enqueue_script('smart-marketing-lib');
            
            wp_register_script(
                                'monetate',
                                $this->js_template_url . 'monetate.js',
                                'web-fonts', 
                                self::VERSION,
                                $placement);
            wp_enqueue_script('monetate');
        }
    }
    
    public function enq_post_scripts() {
        wp_enqueue_script('async-loader');
        wp_enqueue_script('web-fonts');
        wp_enqueue_script('jquery-1-7-1-min');
        wp_enqueue_script('jquery-cookie');
        wp_enqueue_script('write-capture');
        wp_enqueue_script('jquery-write-capture');
        wp_enqueue_script('jquery-ui-1-8-6-min');
        wp_enqueue_script('tynt');
        wp_enqueue_script('top-menu');
        wp_enqueue_script('rd-share-tools');
        wp_enqueue_script('footer-analytics');
        wp_enqueue_script('google-tag-mgr');
        wp_enqueue_script('floodlight');
    }
    
    public function enq_slidewhow_scripts() { 
    
        wp_enqueue_script('async-loader');
        wp_enqueue_script('web-fonts');
        wp_enqueue_script('jquery-1-7-1-min');
        wp_enqueue_script('slideshow-nav');
        wp_enqueue_script('jquery-cookie');
        wp_enqueue_script('write-capture');
        wp_enqueue_script('jquery-write-capture');
        wp_enqueue_script('jquery-ui-1-8-6-min');
        wp_enqueue_script('tynt');
        wp_enqueue_script('top-menu');
        wp_enqueue_script('rd-share-tools');
        wp_enqueue_script('footer-analytics');
        wp_enqueue_script('google-tag-mgr');
        wp_enqueue_script('floodlight');
        
        //wp_enqueue_script('crowdctl_tag');
        //wp_enqueue_script('comscore-analytic');
    }
    
    public function enq_frontpage_scripts() {
            wp_enqueue_script('async-loader');
            wp_enqueue_script('web-fonts');
            wp_enqueue_script('jquery-1-7-1-min');
            wp_enqueue_script('jquery-easing');
            wp_enqueue_script('jquery-ui-1-8-6-min');
            wp_enqueue_script('jquery-color');
            wp_enqueue_script('jquery-cookie');
            wp_enqueue_script('marquee-slider');
            wp_enqueue_script('jquery-write-capture');
            wp_enqueue_script('write-capture');
            wp_enqueue_script('tynt');
            wp_enqueue_script('top-menu');
            wp_enqueue_script('footer-analytics');
        }
        
    public function enq_category_scripts() {
            wp_enqueue_script('async-loader');
            wp_enqueue_script('web-fonts');
            wp_enqueue_script('jquery-1-7-1-min');
            wp_enqueue_script('jquery-easing');
            wp_enqueue_script('jquery-ui-1-8-6-min');
            wp_enqueue_script('jquery-color');
            wp_enqueue_script('jquery-cookie');
            wp_enqueue_script('marquee-slider');
            wp_enqueue_script('jquery-write-capture');
            wp_enqueue_script('write-capture');
            wp_enqueue_script('tynt');
            wp_enqueue_script('top-menu');
            wp_enqueue_script('footer-analytics');
        }
        
    public function enq_jokes_tmpl_scripts() {
            wp_enqueue_script('async-loader');
            wp_enqueue_script('web-fonts');
            wp_enqueue_script('jquery-1-7-1-min');
            wp_enqueue_script('jquery-ui-1-8-6-min');
            wp_enqueue_script('jquery-color');
            wp_enqueue_script('jquery-cookie');
            wp_enqueue_script('jquery-write-capture');
            wp_enqueue_script('write-capture');
            wp_enqueue_script('tynt');
            wp_enqueue_script('top-menu');
            wp_enqueue_script('footer-analytics');
    }
    
    public function display_headers() {
        $headers  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ';
        $headers .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . PHP_EOL;
        $headers .= '<html ' . get_bloginfo( 'language' ) . ' xmlns="http://www.w3.org/1999/xhtml">' . PHP_EOL;
        $headers .= '<html xmlns:fb="http://ogp.me/ns/fb#">' . PHP_EOL;
        
        print($headers);
    }
    
    public function display_meta_tags() {
        $meta_tags  = '<meta name="apple-itunes-app" content="app-id=411524298">' . PHP_EOL;
        $meta_tags .= '<meta http-equiv="X-UA-Compatible" content="IE=Edge" />' . PHP_EOL;
        $meta_tags .= '<meta http-equiv="Content-Type" content="text/html; charset=' . get_bloginfo( 'charset' ) . '" />' . PHP_EOL;
        
        print($meta_tags);
    }
    
    public function display_meta_links() {
        $meta_links  = '<link rel="shortcut icon" type="image/x-icon" href="/wp-content/plugins/rd-widget/images/favicon.ico" />' . PHP_EOL;
        $meta_links .= '<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="';
        $meta_links .= html_entity_decode_numeric(get_bloginfo('rss2_url')) . '" />' . PHP_EOL;
        $meta_links .= '<link rel="alternate" type="text/xml" title="RSS .92" href="';
        $meta_links .= html_entity_decode_numeric(get_bloginfo('rss_url')) . '" />' . PHP_EOL;
        $meta_links .= '<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="';
        $meta_links .= html_entity_decode_numeric(get_bloginfo('atom_url')) . '" />' . PHP_EOL;
        $meta_links .= '<link rel="alternate" type="application/rss+xml" title=" Reader\'s Digest RSS Comments Feed" href="';
        $meta_links .= html_entity_decode_numeric(get_bloginfo('comments_rss2_url')) . '" />' . PHP_EOL;
        $meta_links .= '<link rel="profile" href="http://gmpg.org/xfn/11" />';
        
        print($meta_links);
    }
    
    public function inhibit_ads() {
        if (isset($_GET['inhibit_ads'])) {
            if ($_GET['inhibit_ads'] === '1') {
                return( true );
            }
        }
    }

    public function in_footer() {
        if (isset($_GET['in_footer'])) {
            if ($_GET['in_footer'] === '1') {
                return( true );
            }
        }
    }

    /*
        This is not the best way to handle this but it is better than what we've been doing.
        This probaly should be moved into it's own static class.
    */
    public static function check_variant( $variation = null ) {
        $variant_msg = '<!-- Variants are active! -->' . PHP_EOL;
        self::$header_file = '';
        self::$footer_file = '';
        
        if ( is_single()) {
            self::$header_file = 'single-post';
            self::$footer_file = 'single-post';
        }

        if ( ! $variation ) {
            if (isset($_GET['header']) || isset($_GET['variant']) ) {
                if ($_GET['header'] === '1' || $_GET['variant'] === '1') {
                    self::$header_file = '';
                    self::$footer_file = '';
                    return( true );
                } elseif ($_GET['header'] === '2' || $_GET['variant'] === '2') {
                    self::$header_file = 'post';
                    self::$footer_file = 'single-post';
                    return( true );
                }
            }
            
            if ($_GET['front'] === '1' || $_GET['variant'] === '1') {
                self::$header_file = 'home';
                self::$footer_file = 'front-page';
/*              print($variant_msg); */
                return( true );
            }
        }
        
        if ($_GET['variant'] === $variation) {
            $variant_msg = '<!-- The ' . $variation . ' variantion is active! -->' . PHP_EOL;
/*             print($variant_msg); */
            return( true );
        }
        
        return( false );
    }

    public function get_js_suffix() {
        if ($this->check_base_url() || $this->check_variant( 'min' )) {
            return(self::MIN_JS_SUFFIX);
        }
        return(self::JS_SUFFIX);
    }

    public function get_css_suffix() {
        if ($this->check_base_url() || $this->check_variant( 'min' )) {
            return(self::MIN_CSS_SUFFIX);
        }
        return(self::CSS_SUFFIX);
    }

    public function check_base_url() {
        return( stripos($this->abd->the_base_url, self::PROD_URL ));
    }

    /*
        Will always return the self initiated copy of itself.
    */
    public static function init() {
        if ( ! self::$initialized ) {
            self::$initialized = true;
            return( self::$initialized );
        }
    }

    public static function get_instance() {
        $caller = get_called_class();
        if ( !isset( self::$instance[$caller] ) ) {
            self::$instance[$caller] = new $caller();
            self::$instance[$caller]->init();
        }

        return( self::$instance[$caller] );
    }
}