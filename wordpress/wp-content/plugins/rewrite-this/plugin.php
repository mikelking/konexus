<?php
/*
Plugin Name: Aaa Rewrite this
Version: 1.3
Description: Ads custom rewrite rule based endpoints to a URL <a href='https://readersdigest.atlassian.net/browse/RDCOM-1363' target='_blank'>Read more at RDCOM-1363 ...</a>
Author: Mikel King
Text Domain: rewrite-rules
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause
*/

/*
    Copyright (C) 2014, Mikel King, rd.com, (mikel.king AT rd DOT com)
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
    
        * Redistributions of source code must retain the above copyright notice, this
          list of conditions and the following disclaimer.
        
        * Redistributions in binary form must reproduce the above copyright notice,
          this list of conditions and the following disclaimer in the documentation
          and/or other materials provided with the distribution.
        
        * Neither the name of the {organization} nor the names of its
          contributors may be used to endorse or promote products derived from
          this software without specific prior written permission.
    
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
    AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
    FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
    OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

// see: http://codex.wordpress.org/Plugin_API/Hooks_2.0.x


class Rewrite_This extends Base_Plugin {
    const VERSION          = '1.3';
    const ENABLED          = true;
    const DISABLED         = false;
    const VIEW_ALL         = '/view-all.php';
    const PRINT_VIEW       = '/print.php';
    const SLIDE_VIEW_ALL   = '/view-all-slide.php';
    const SLIDE_PRINT_VIEW = '/print-slide.php';

    protected static $initialized = false;
    protected static $activated = false;
    protected static $template_file;

    protected $notifier;
    
    public static $abd;

    public function __construct() {
        self::$abd =  new Advanced_Blog_Data();

//        add_action( 'admin_init', array( $this, 'add_endpoints'));
        add_action( 'init', array( $this, 'add_endpoints'));
//        add_action( 'template_redirect', array( $this, 'endpoints_template_redirect' ));
/*         add_action('generate_rewrite_rules', array( $this, 'add_endpoints')); */
    }

    public function __activator() {
        if (! self::$activated) {
            self::$activated = true;
            $this->add_endpoints();
            flush_rewrite_rules();
//            $this->flush_wp_rules();
        }
    }

    public function __deactivator() {
        if (self::$activated) {
            flush_rewrite_rules();
//            $this->flush_wp_rules();
        }
    }
    /*
        examples:
            http://mking.dev.rd.com/slideshows/the-flu-virus/view-all/
            http://mking.dev.rd.com/slideshows/the-flu-virus/?view-all
            http://mking.dev.rd.com/slideshows/coconut-oil-uses/print-view/
            http://mking.dev.rd.com/slideshows/the-flu-virus/?print-view
            http://mking.dev.rd.com/health/day-in-the-life-your-knee/?v=print
            http://mking.dev.rd.com/health/day-in-the-life-your-knee/print-view/
    */
    public function add_endpoints() {
        $this->set_view_all_rule();
        $this->set_print_view_rule();
        error_log ('Testing the rewrite this plugin...');
    }

    public function set_view_all_rule() {
        global $wp_rewrite;
        $places = EP_PERMALINK | EP_PAGES;
        $slug   = 'view-all';
        $wp_rewrite->add_endpoint($slug, $places);

//        add_rewrite_endpoint( 'view-all', EP_PERMALINK | EP_PAGES );

    }
    
    public function set_print_view_rule() {
        global $wp_rewrite;
        $places = EP_PERMALINK | EP_PAGES;
        $slug   = 'print-view';
        $wp_rewrite->add_endpoint($slug, $places);
//        add_rewrite_endpoint( 'print-view', EP_PERMALINK | EP_PAGES );
    }

    public static function print_current_uri() {
        print('<!-- Current URI: ' . self::$abd->the_current_uri . ' -->' . PHP_EOL);
    }

    public static function check_uri_params() {
        if (stripos(self::$abd->the_current_uri, 'print-view')) {
            self::$template_file = get_template_directory() . self::PRINT_VIEW;
            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_PRINT_VIEW;                
            }
            return( true );
        } elseif (stripos(self::$abd->the_current_uri, 'view-all')) {
            self::$template_file = get_template_directory() . self::VIEW_ALL;
            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_VIEW_ALL;                
            }
            return( true );
        }
        return( false );
    }
    
    public static function check_url_params() {
        if (isset($_GET['print-view']) || get_query_var( 'print-view' )) {
            self::$template_file = get_template_directory() . self::PRINT_VIEW;
            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_PRINT_VIEW;                
            }
            return( true );
        } elseif (isset($_GET['view-all']) || get_query_var( 'view-all' )) {
            self::$template_file = get_template_directory() . self::VIEW_ALL;
            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_VIEW_ALL;                
            }
            return( true );
        }
        return( false );
    }

    public static function check_endpoints() {

        if (get_query_var( 'print-view' )) {
            if (is_singular()) {
                self::$template_file = get_template_directory() . self::PRINT_VIEW;
            }

            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_PRINT_VIEW;
            }

            return( true );
        } elseif (get_query_var( 'view-all' )) {
            if (is_singular()) {
                self::$template_file = get_template_directory() . self::VIEW_ALL;
            }

            if (self::is_slideshow()) {
                self::$template_file = get_template_directory() . self::SLIDE_VIEW_ALL;
            }

            return( true );
        }

        return( false );
    }

    public function get_template_file() {
        if ( isset(self::$template_file )) {
            include( self::$template_file );
        }
        return( null );
    }
    
    public static function is_slideshow() {
        if ( get_post_type(get_the_ID()) === 'slideshows') {
            return( true );
        }
    }
    
    /*
        Will always return the self initiated copy of itself.
    */
    public static function init() {
        if (function_exists("is_admin") && is_admin() &&
            function_exists('add_filter') && ! self::$initialized) {
            self::$initialized = true;
            return( self::$initialized );
        }
    }
    
    public function flush_wp_rules() {
        /*
            May be overkill
            See: http://codex.wordpress.org/Class_Reference/WP_Rewrite#Examples
        */
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}

$rt = Rewrite_This::get_instance();
register_activation_hook( __FILE__, array( $rt, '__activator'));
register_deactivation_hook( __FILE__, array( $rt, '__deactivator'));

/*
function makeplugins_endpoints_add_endpoint() {
// register a "json" endpoint to be applied to posts and pages
    add_rewrite_endpoint( 'mikel', EP_PERMALINK | EP_PAGES );
}
add_action( 'init', 'makeplugins_endpoints_add_endpoint' );

function makeplugins_endpoints_template_redirect() {
    global $wp_query;

// if this is not a request for json or it's not a singular object then bail
    if ( ! isset( $wp_query->query_vars['mikel'] ) || ! is_singular() )
        return;

// output some JSON (normally you might include a template file here)
    makeplugins_endpoints_do_json();
    exit;
}
add_action( 'template_redirect', 'makeplugins_endpoints_template_redirect' );

function makeplugins_endpoints_do_json() {
    header( 'Content-Type: application/json' );

    $post = get_queried_object();
    echo json_encode( $post );
}

function makeplugins_endpoints_activate() {
// ensure our endpoint is added before flushing rewrite rules
    makeplugins_endpoints_add_endpoint();
// flush rewrite rules - only do this on activation as anything more frequent is bad!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'makeplugins_endpoints_activate' );

function makeplugins_endpoints_deactivate() {
// flush rules on deactivate as well so they're not left hanging around uselessly
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'makeplugins_endpoints_deactivate' );
*/