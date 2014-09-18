<?php
/*
Plugin Name: Admin Message Class
Version: 1.0
Description: Adds a standardized base class for rendering messages in the WordPress Admin console. BY itself the plugin does nothing more 
than make the class available to use. You must instantiate the AdminMessage in order to utilize it.
Author: Mikel King
Author URI: http://mikelking.com
Plugin URI: http://olivent.com/wordpress-plugins/konexus-admin_messages
*/

// see: http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices

// I did this because I thought it might be useful and eventually phpunit testable

//         self::$setup->admin_error_msg(print_r(self::$setup, self::PRINT_OFF));

// Remember to implement any abstact methods.
class Admin_Message {
    const ERROR_DIV_CLASS_FMT = '<div id="message" class="error"><p><strong>%s</strong></p></div>';
    const NORMAL_DIV_CLASS_FMT = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';
    const PRINT_OFF = true;
    
    private $admin_msg;
    
    public $error_level;

    function __construct($message) {
        if (isset($message)) {
            $this->admin_msg = $message;
        }
    }
    
	public function get_admin_error_message() {
        if ($this->admin_msg) {
            $the_message = sprintf(self::ERROR_DIV_CLASS_FMT, $this->admin_msg);
            return($the_message);
        }
    }

	public function get_admin_normal_message() {
        if ($this->admin_msg) {
            $the_message = sprintf(self::NORMAL_DIV_CLASS_FMT, $this->admin_msg);
            return($the_message);
        }
    }

    public function display_admin_error_message() {
        print($this->get_admin_error_message());
    }

    public function display_admin_normal_message() {
        print($this->get_admin_normal_message());
    }
    
    public function set_admin_message_level($level) {
        if (isset($level)) {
            $this->error_level = $level;
        } else {
            $this->error_level = 'normal';
        }
    }

    public function get_the_admin_message() {
        if ( $this->error_level == 'error') {
            return($this->get_admin_error_message());
        } else {
            return($this->get_admin_normal_message());
        }
    }

    public function show_the_admin_message() {
        print($this->get_the_admin_message());
    }

    
    public static function print_eol() {
        print('<br>' . PHP_EOL);
    }
}
