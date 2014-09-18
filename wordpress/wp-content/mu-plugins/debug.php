<?php
/*
Plugin Name: Debug
Version: 1.5
Description: Provides a simple object to work insert debuggin data into the content stream with easy to locate markers. BY itself the plugin does nothing more 
than make the class available to use. You must instantiate the AdminMessage in order to utilize it.
Author: Mikel King
Author URI: http://mikelking.com
*/

class Debug {
    const VERSION = '1.5';
    
    public $debug_label;
    public $debug_value;
    public $debug_environment;
    public $debug_this = false;
    public $target_addr;
    public $whitelist;
    public $whitelisted;
    public $debug_output_enabled = false;
    public static $debug_data;

    public function __construct() {
        $this->whitelist = array(
                        ip2long("10.252.8.0") => ip2long("10.252.11.255"),
                        ip2long("172.16.0.0") => ip2long("172.31.255.255")                        
                    );
                             
        $this->get_debug_this_flag();
/*         $this->get_debug_environment(); */
        $this->get_target_addr();
/*         $this->whitelist_check(); */
        $this->set_debug_label();
        $this->set_debug_output();
    }

    public function start_debug_comment_block() { 
        printf("<!-- Begin debug comment thread %s\n", $this->debug_label);
    }

    public function end_debug_comment_block() {
        printf("End debug comment thread %s --> \n", $this->debug_label);
    }

    public function short_debug_comment() {
        printf( "<!-- debug comment %s -->\n", $this->debug_label );
    }

    public function render_debug_data() {
        if (isset(self::$debug_data)) {
            var_dump(self::$debug_data);
        }
    }

    public function debug_store($value) {
        if ($this->debug_output_enabled === true) {
            if (isset($value)) {
                if (is_array($value) || is_object($value)) {
                    self::$debug_data .= sprintf("<!-- %s ", $this->debug_label);
                    self::$debug_data .= print_r($value, true);
                    self::$debug_data .= sprintf(" --> \n");
                } else {
                    self::$debug_data .= sprintf("<!-- %s ==== %s -->\n", $this->debug_label, $value);
                }
            } else {
                self::$debug_data .= sprintf("<!-- %s -- MARK POINT assumed because &#36;value was not set. -->\n", $this->debug_label);
            }
        }
    }

    public function debug_print($value) {
        if ($this->debug_output_enabled === true) {
            if (isset($value)) {
                if (is_array($value) || is_object($value)) {
                    printf("<!-- %s ", $this->debug_label);
                    var_dump($value);
                    print(" --> \n");
                } else {
                    printf("<!-- %s ==== %s -->\n", $this->debug_label, $value);
                }
            } else {
                printf("<!-- %s -- MARK POINT assumed because &#36;value was not set. -->\n", $this->debug_label);
            }
        }
    }

    public function debug_print_r($value) {
        if ($this->debug_output_enabled === true) {
            if (isset($value)) {
                    printf("<!-- %s ", $this->debug_label);
                    print_r($value);
                    print(" --> \n");
                }
            } else {
                printf("<!-- %s -- MARK POINT assumed because &#36;value was not set. -->\n", $this->debug_label);
            }
        }
        
    public function debug_dump($value) {
        if ($this->debug_output_enabled === true) {
            if (isset($value)) {
                    printf("<!-- %s ", $this->debug_label);
                    var_dump($value);
                    print(" --> \n");
                }
            } else {
                printf("<!-- %s -- MARK POINT assumed because &#36;value was not set. -->\n", $this->debug_label);
            }
        }

    public function set_debug_label( $label = null ) {
        if (isset($label)) {
            $this->debug_label = $label;
        } else {
            $this->debug_label = 'Default Label';
        }
    }

    public function get_debug_this_flag() {
        if (isset($_REQUEST["debug_this"])) {
            if ($_REQUEST["debug_this"] === '1') {
                $this->debug_this = true;
            }
        }
    }

    public function set_debug_output() {
        if ($this->debug_this && $this->whitelisted) {
            $this->debug_output_enabled = true;
        } else {
            $this->debug_output_enabled = false;
        }
    }
    
    /*
        This section requires some improvement; a switch statement could clean this up.
    */
    public function get_debug_environment() {
        if (isset($_SERVER['ENVIRONMENT'])){
            $this->debug_environment = 'dev';
        } else {
            /*
                Although I am no fond of ternary conditionals as they force you to 
                think backwards there are some rare cases that I do find them useful
            */
            $this->debug_environment = (isset($_SERVER['ETSY_ENVIRONMENT'])) ? $_SERVER['ETSY_ENVIRONMENT'] : 'production';
        }
    }

    public function get_target_addr() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $this->target_addr = ip2long($_SERVER["HTTP_X_FORWARDED_FOR"]);
        } elseif (isset($_SERVER["REMOTE_ADDR"])) {
            $this->target_addr = ip2long($_SERVER["REMOTE_ADDR"]);
        }
    }

    public function whitelist_check() {
        foreach ( $this->whitelist as $network => $broadcast) {
            if (($this->target_addr > $network) && ($this->target_addr < $broadcast)) { 
                $this->whitelisted = true;
            } else {
                $this->whitelisted = false;
            }

        }
    }

    public static function print_file_name( $file = null ) {
        $filename = __FILE__;
        if ($file && file_exists($file)) {
            $filename = $file;
        }
        
        
        if (isset($_GET['debug'])) {
            print('<!-- FileName: ' . $filename . ' -->' . PHP_EOL);
        }
    }
    
    public static function print_wp_rewrite() {
        if (isset($_GET['debug']) && $_GET['debug'] === 'wprewrite' ) {
        	global $wp_rewrite;
        	print('<h2>WP Rewrite obj</h2>' . PHP_EOL);
            var_dump($wp_rewrite);
        	print('<h3>WP Rewrite obj</h3>' . PHP_EOL);
        	exit;
    	}
    }

}
/*    
    if (isset($_SERVER['IS_PRINCESS'])) {
        $dbg = new Etsy_Debug( "She thinks she's the princess..." );
        $dbg->start_etsy_debug_comment_block();
        print("Somebody save the princess!");
        $dbg->end_etsy_debug_comment_block();
        
    }
*/