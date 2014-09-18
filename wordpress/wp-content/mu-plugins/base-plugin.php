<?php

/*
Plugin Name: Base Plugin Class
Version: 1.0
Description: Sets a standard class to build new plugin from.
Author: Scott Taylor
Author URI: http://scotty-t.com
Plugin URI: http://scotty-t.com/2012/07/09/wp-you-oop/
*/

// Remember to implement the abstact methods.
abstract class Base_Plugin {
    private static $instance = array();

    protected function __construct() {}

    public static function get_instance() {
        $caller = get_called_class();
        if ( !isset( self::$instance[$caller] ) ) {
            self::$instance[$caller] = new $caller();
            self::$instance[$caller]->init();
        }

        return( self::$instance[$caller] );
    }

    abstract public static function init();
}
