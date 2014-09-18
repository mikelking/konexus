<?php
/*
Plugin Name: Advanced Blog Data
Version: 1.2
Description: Enhances the data retrieved by get_bloginfo() and provides a simple object to work with. BY itself the plugin does nothing more 
than make the class available to use. You must instantiate the AdminMessage in order to utilize it.
Author: Mikel King
Author URI: http://mikelking.com
*/

class Advanced_Blog_Data {
    const TABLE_NAME = 'wp_blogs';
    
    public $the_protocol;
    public $the_base_url;
    public $the_current_domain;
    public $the_current_wp_domain;
    public $the_blog_path;
    public $the_blog_id;
    public $the_blog_type;
    public $the_current_url;
    public $the_current_uri;

    function __construct() {
        $this->get_the_protocol();
        $this->get_the_blog_type();
        $this->get_the_current_site_domain();
        $this->get_the_current_site_domain_from_wp();
        $this->get_the_base_url();
        if ( $this->the_blog_type === 'network'){
            $this->get_the_blog_path();
            $this->get_blog_id();
        }
        $this->get_the_current_url();
        $this->the_current_uri = $_SERVER["REQUEST_URI"];
    }
    
    public function get_the_protocol() {
        if (is_ssl()) {
            $this->the_protocol = 'https';
        } else {
            $this->the_protocol = 'http';
        }
        /*
        if ( isset($_SERVER['HTTPS']) ) {
                if ( 'on' == strtolower($_SERVER['HTTPS']) ) {
                    return true;
                }
                
                if ( '1' == $_SERVER['HTTPS'] ) {
                    return true;
                }
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_SECURE']) && ( '1' == $_SERVER['HTTP_X_SECURE']) ) {
            return true;
        }
        */
        return false;
        
    }
    
    public function show_data() {
        if ( class_exists('Debug') ) {
            $dbg = new Debug( "Advanced Blog Data" );
        }
        
        $dbg->start_debug_comment_block();
        printf("The base url is %s \n", $this->the_base_url);
        printf("The current url is %s \n", $this->the_current_url);
        printf("The current uri is %s \n", $this->the_current_uri);
        printf("The current domain is %s \n", $this->the_current_domain);
        printf("The current WordPress domain is %s \n", $this->the_current_wp_domain);
        printf("The blog path is %s \n", $this->the_blog_path);
        printf("The blog id is %s \n", $this->the_blog_id);
        printf("The protocol is %s \n", $this->the_protocol);
        printf("The blog type is %s \n", $this->the_blog_type);
        printf("The permalink is %s \n", get_permalink());
        $dbg->end_debug_comment_block();
    }
    
    public function get_the_blog_type() {
        if ( is_multisite() ){
            $this->the_blog_type = 'network';
        } else {
            $this->the_blog_type = 'basic';
        }
        return($this->the_blog_type);
    }
    
    public function get_the_current_site_domain_from_wp() {
        if($this->the_blog_type === 'network') {
            $this->the_current_wp_domain = network_home_url();
        } else {
            $this->the_current_wp_domain = home_url();
        }
        return($this->the_current_wp_domain);
    }
    
    public function get_the_current_site_domain() {
        $this->the_current_domain = $_SERVER['HTTP_HOST'];
        return($this->the_current_domain);
    }
    
/*
    public function get_the_current_url() {
        $this->the_current_url = $_SERVER["SCRIPT_URL"];
        return($this->the_current_url);
    }
*/

    public function get_the_base_url() {
        $this->the_base_url = $this->the_protocol . "://" . $this->the_current_domain;
        return($this->the_base_url);
    }
    
    public function get_the_blog_path() {
        $diffs = explode($this->the_base_url, site_url());
        $diff_url = implode("", $diffs);
        $this->the_blog_path = $diff_url . "/";
        
        return($this->the_blog_path);
    }

    public function get_blog_id() {
        $this->the_blog_path = $this->get_the_blog_path();
        global $wpdb;

        $query_format = "SELECT blog_id from %s where path='%s';";
        $query = sprintf($query_format, self::TABLE_NAME, $this->the_blog_path) ;
        
        $result = $wpdb->get_results( $query, ARRAY_A );
        if ( is_array($result) ) {
            $this->the_blog_id = $result[0]['blog_id'];
            return($this->the_blog_id);
        }
    }
    
    public function get_the_current_url() {
        $this->the_current_url = null;
        
        if (!empty($_SERVER['SCRIPT_URL'])) {
            $this->the_current_url = $_SERVER['SCRIPT_URL'];
        } elseif (!empty($_SERVER['REDIRECT_URL'])) {
            $this->the_current_url = $_SERVER['REDIRECT_URL'];
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            $p = parse_url($_SERVER['REQUEST_URI']);
            $this->the_current_url = $p['path'];
        } else {
            die (__FILE__ . " / " . __FUNCTION__ . ':<br />Couldn\'t determine $_SERVER["SCRIPT_URL"].');
        }
        
        $_SERVER['SCRIPT_URL'] = $this->the_current_url;
        
        return( $this->the_current_url );
        
    }
}