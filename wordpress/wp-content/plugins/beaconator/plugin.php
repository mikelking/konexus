<?php
/*
Plugin Name: Beaconator
Description: Activation enables a Javascript based beacon in the theme header & footer. Simply instantiate the class in your theme, add an custom array of key pairs to override the default.
Author: Mikel King
Version: 1.0
*/

class Beaconator {
    const BEACON_URL = 'http://konex.us/beacon_catcher.php';
    const BEACON_COLLECTOR_URL = 'beacon_collector_base_url';
    const BEACON_ANALYTICS_ID = 'analytics_second_beacon';

    protected static $initialized = false;

    public static $beacons;
    
    public $encoded_beacon_string;

    public static function init(){
        if ( ! self::$initialized) {
        add_action('wp_head', array(__CLASS__, 'render_beacon_header' ), 100);
        self::$initialized = true;
        }
    }

    public function set_custom_beacons( $custom_beacons = null ) {
        if(is_array($custom_beacons)){
            self::$beacons = $custom_beacons;
        } else {
            self::$beacons = array ( 
                'primary_event' => '1',
                'php_event_name' => 'blog'
            );
        }    
    }

    public function convert_beacon_to_json() {
	    reset(self::$beacons);
		$this->encoded_beacon_string = json_encode(self::$beacons);

    }
        
    public function render_beacon_footer( $beacon_name = null ) {
    	if ( ! $beacon_name) {
    		$beacon_name = 'Etsy Beacon System Footer';
    	}
    	
    	$this->convert_beacon_to_json();
        ?>
            <!-- <?php echo $beacon_name; ?> -->
            <script type="text/javascript">
                jQuery(document).logEvent("ready", <?php echo $this->encoded_beacon_string; ?>);
            </script>
            <!-- End <?php echo $beacon_name; ?> -->
        <?php
    }
    
    public function render_beacon_header() {
    ?>
        <!-- Etsy Beacon System Header -->
        <script type="text/html"
            id="<?php echo self::BEACON_COLLECTOR_URL; ?>"><?php echo self::BEACON_URL; ?></script>
        <script type="text/html" id="<?php echo self::BEACON_ANALYTICS_ID; ?>">1</script>
        <!-- End Etsy Beacon System Header -->
    <?php
    }

    public function get_blog_event_name() {
        $deliminator = '/';
        $the_blog_path = 'blog_en';
        if (class_exists('Advanced_Blog_Data')) {
           $abd = new Advanced_Blog_Data();
        } else {
            return($the_blog_path);
        }
    
        $the_blog_path = str_replace($deliminator, '_', trim($abd->the_blog_path, $deliminator));
        
        return($the_blog_path);
    }

    public function get_url_to_asset( $asset ) {
        return( plugins_url($asset, __FILE__ ));
    }

    function get_beacon() {
        $bcn = null;
        $beacon_plugin = 'etsy-beacon-system/etsy-beacon-system.php';
        if (is_plugin_active($beacon_plugin)) {
            if (class_exists('Etsy_Beacon')) {
                $bcn = new Etsy_Beacon();
            } elseif (file_exists(PLUGIN_DIR . DIRECTORY_SEPARATOR . $beacon_plugin)){
                require(PLUGIN_DIR . DIRECTORY_SEPARATOR . $beacon_plugin);
                $bcn = new Etsy_Beacon();
            }
        } else {
            add_action('wp_head', 'default_beacon_header', 100);
            add_action('wp_footer', 'default_beacon_footer', 100);
        }
        return($bcn);
    }

    function get_blog_event_name() {
        $deliminator = '/';
        $the_blog_path = 'blog_en';
        if (class_exists('Advanced_Blog_Data')) {
            $abd = new Advanced_Blog_Data();
        } elseif (file_exists(ETSY_WPLIB . DIRECTORY_SEPARATOR . 'advanced_blog_data.php')){
            require(ETSY_WPLIB . DIRECTORY_SEPARATOR . 'advanced_blog_data.php');
            $abd = new Advanced_Blog_Data();
        } else {
            return($the_blog_path);
        }

        $the_blog_path = str_replace($deliminator, '_', trim($abd->the_blog_path, $deliminator));

        return($the_blog_path);
    }
}

Beaconator::init();
