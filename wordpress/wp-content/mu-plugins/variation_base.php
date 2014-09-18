<?php
/*
    Plugin Name: Variation Base Class
    Version: 1.3
    Description: Adds a standardized base class for creating variants by extension
    Author: Mikel King
    Author URI: http://mikelking.com
    License: BSD(3 Clause)
*/

class Variant_Base extends Base_Plugin {
    const VERSION         = '1.3';

    private static $instance = array();
    
    protected static $initialized = false;

    public static $variant = array();

    public function __construct() {
        /*
            Temporary for troubleshooting only requires the addition of ?debug to the target URL.
        */
        Debug::print_file_name(__FILE__);
    }
    
    public function debug_variant( $variant = null ) {
        if ( $variant ) {
            print('<!-- The variant target: ' . $variant . ' is active -->' . PHP_EOL);
        }
        print('<!-- The variant url param is set to: ' . $this->check_variant() . ' -->' . PHP_EOL);
                
        print('<!-- The variant array contains: ' . PHP_EOL . print_r(self::$variant, true) . ' -->' . PHP_EOL);

    }
    
    public function register_variant( $new_variant ) {
        if (isset($new_variant) && ! $this->find_variant( $new_variant )) {
            self::$variant[] = $new_variant;
            return( true );
        }
    }

    public function is_variant( $needle ) {
        if ( $this->find_variant( $needle ) == $this->check_variant()) {
            return( true );
        }
    }


    public function check_variant() {
        if (isset($_GET['variant'])) {
            return( $this->sanitize_data( $_GET['variant'] ));
        }

    }

    public function find_variant( $needle ) {
        foreach ( self::$variant as $variant) {
            if ($variant === $needle) {
                return( $variant );
            }
        }
    }

    /*
        PHP Warning:  filter_var() expects at most 3 parameters, 7 given
    */
    private function sanitize_data( $data ) {
        $flags = array(
                        FILTER_FLAG_NO_ENCODE_QUOTES,
                        FILTER_FLAG_STRIP_LOW,
                        FILTER_FLAG_STRIP_HIGH,
                        FILTER_FLAG_ENCODE_LOW,
                        FILTER_FLAG_ENCODE_HIGH,
                        FILTER_FLAG_ENCODE_AMP
        );
        return( filter_var( $data, FILTER_SANITIZE_STRING));
    }

    /*
        This is not the best way to handle this but it is better than what we've been doing.
        This probaly should be moved into it's own static class.
    */
/*
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
//             print($variant_msg);
                return( true );
            }
        }
        
        if ($_GET['variant'] === $variation) {
            $variant_msg = '<!-- The ' . $variation . ' variantion is active! -->' . PHP_EOL;
//            print($variant_msg);
            return( true );
        }
        
        return( false );
    }
*/

    /*
        Will always return the self initiated copy of itself.
    */
    public static function init() {
        if ( ! self::$initialized ) {
            self::$initialized = true;
            return( self::$initialized );
        }
    }
}

/* Example:
$variant = 'bacon';
$vb = new Variant_Base;
$vb->register_variant( $variant );
if ($vb->is_variant( $variant )) {
    $vb->debug_variant( $variant );
}
*/