<?php
/*
Plugin Name: Etsy Class Meta Terms
Description: Enables adds a category_termmeta table simplar in functionality to the post_meta.
Author: Mikel King
Version: 1.0
*/

$plugin_path = dirname(__FILE__);

global $wpdb;
global $wp_version;
global $type;
global $etsy_redirector_db_version;
$etsy_redirector_db_version = '1.0';
$type = 'category_term';

function etsy_class_termmeta_init() {
    global $wpdb;
    global $type;

    $variable_name = $type . 'meta';
    $table_name = $wpdb->prefix . $variable_name;
    $wpdb->$variable_name = $table_name;
    $wpdb->category_termmeta = $wpdb->prefix . 'category_termmeta';
}
add_action('init','etsy_class_termmeta_init');

function etsy_class_termmeta_install()
{
    global $wpdb;
    global $type;
    $table_name = $wpdb->prefix . $type . 'meta';
    
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        create_metadata_table($table_name, $type);
    }
}
register_activation_hook(__FILE__, 'etsy_class_termmeta_install');

function create_metadata_table($table_name, $type) {
	global $wpdb;
 
	if (!empty ($wpdb->charset))
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	if (!empty ($wpdb->collate))
		$charset_collate .= " COLLATE {$wpdb->collate}";
 
	  $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
	  	meta_id bigint(20) NOT NULL AUTO_INCREMENT,
	  	{$type}_id bigint(20) NOT NULL default 0,
		meta_key varchar(255) DEFAULT NULL,
		meta_value longtext DEFAULT NULL,
	  	UNIQUE KEY meta_id (meta_id)
	) {$charset_collate};";
 
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	add_option("etsy_class_termmeta_version", $etsy_class_termmeta_version);
}

add_action ( 'edit_category_form_fields', 'category_input_metabox' );
add_action ( 'edited_terms', 'save_category_data' );
 
function category_input_metabox($category) {
    global $type;
    global $wpdb;
    
	$redirection_url = get_metadata($type, $category->term_id, 'redirection-url', TRUE);	

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="category_widget"><?php _e('Redirection base URL') ?></label></th>
		<td>
    		<input name='category_meta_input' id='category_meta_input' type="text" value="<?php _e($redirection_url) ?>" size="60 " />
    		<?php
    		  $redirection_url_msg = '<strong>***IMPORTANT:</strong> Enter a the complete URL blog that the ';
    		  $redirection_url_msg .= 'category of articles has been migrated to. For example: ';
    		  $redirection_url_msg .= '<strong>http://www.etsy.com/blog/news/</strong><br />';
    		  _e($redirection_url_msg);
    		?>
		</td>
	</tr>
	<?php

}
 
function save_category_data($term_id) {
    global $type;
    
        if (isset($_POST['category_meta_input'])) {
	        $category_metadata = esc_attr($_POST['category_meta_input']);
	        update_metadata($type, $term_id, 'redirection-url', $category_metadata);
        }
}