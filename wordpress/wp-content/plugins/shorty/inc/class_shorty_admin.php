<?php

class Shorty_Admin {
    private static $initialized;
    
    public static $admin_config;
    
    public function __construct() {
        self::check_admin_options();
    }

    public static function initialized() {
        if(is_null(self::$initialized)) {
            self::$initialized  = new self;
            return(self::$initialized);    
        }
    }

    private static function check_admin_options() {
            self::$admin_config = get_option('shorty_admin_options', self::$admin_config);
    }

    private static function set_default_admin_options() {
        self::$admin_config = array(
            'bitly_user_id' => 'mikelking',
            'bitly_api_key' => 'R_d2b85fab1aeb10b35b4ff15639707238',
            'default_ref_tag' => 'shorty',
            'auto_shrink' => true,
            'shrink_posts' => true,
            'shrink_pages' => true,
            'shrink_categories' => true,
            'shrink_tags' => false,
            'shrink_authors' => true,
            'shrink_home'  => true,
            'https_default' => true
        );
        update_option('shorty_admin_options', self::$admin_config);
    }

    public static function init() {
        add_action('plugins_loaded', array(self::initialized(), '_setup'));
        if(self::$initialized){
            return(self::$initialized);
        }
    }
    
    public static function _setup() {
        add_action('admin_menu', array(__CLASS__, 'register_shorty_admin_menu'));
    }
    
    public static function register_shorty_admin_menu() {
        add_submenu_page('options-general.php', 
            'Shorty URL shortener',
    		'Shorty URL shortener',
    		'manage_options',
    		'shorty-url-options-page',
    		array(__CLASS__,'shorty_options_page')
        );
    }
    
    public static function shorty_options_page() {
    	if ( !current_user_can( 'manage_options' ) )  {
    		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    	}
    	
    	if(self::$admin_config) {
    	    self::check_admin_options();
    	} else {
            self::set_default_admin_options();
            update_option('shorty_admin_options', self::$admin_config);
    	}
    	
    	if (isset($_POST['submit'])) {
            self::check_post_options();
            self::render_admin_menu();
    	} else {
        	self::render_admin_menu();
    	}
    }

    public static function render_admin_menu() {
        include_once('shorty_admin_form.php');
    }

    private static function render_config_option($option_name) {
        if (self::$admin_config[$option_name] === true) {
            print('checked');
        } else {
            print('unchecked');
        }
    }

    private static function check_post_options() {
        if ( empty($_POST) || !wp_verify_nonce($_POST['shorty_nonce'],'shorty_nonce_action') ) {
            die('Sorry, your nonce did not verify.');
        } else {
            if(isset($_POST['shorty_reset'])) {
                self::set_default_admin_options();
            } else {
                foreach(self::$admin_config as $key => $value){
                    if (isset($_POST[$key])) {
                        if ($_POST[$key] === 'on') {
                            self::$admin_config[$key] = true;
                		} elseif ($_POST[$key] === 'off') {
                    		self::$admin_config[$key] = false;
                		} else {
                    		self::$admin_config[$key] = sanitize_text_field($_POST[$key]);
                		}
            		}
        		}
        		update_option('shorty_admin_options', self::$admin_config);
            }
        }
    }

}

$sa = Shorty_Admin::init();
