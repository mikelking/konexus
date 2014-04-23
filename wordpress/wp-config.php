<?php

require('class_server_conf_finder.php');
$scf = new ServerConfFinder();

/**
 * For developers: WordPress debugging mode.
 * see https://codex.wordpress.org/Debugging_in_WordPress
 */

if ( $scf->wpdbg && $scf->dbg_log && $scf->show_errors && $scf->script_dbg && $scf->save_queries ) {
    define('WP_DEBUG', $scf->wpdbg);
    define('WP_DEBUG_LOG', $scf->dbg_log);
    define('WP_DEBUG_DISPLAY', $scf->show_errors);
    define('SCRIPT_DEBUG', $scf->script_dbg);
    define('SAVEQUERIES', $scf->save_queries);
} else {
    define('WP_DEBUG',false) ;
}
//error_log ( "The db name is " . $scf->server_cfg->db . ".", 0 );

error_reporting($scf->server_cfg->error_level);

/* Setup for a single memcache server with the default data zone.
 */
//define('BATCACHE_DEBUG', true);
//require(__DIR__ . '/class_device_user_agent_finder.php');

/*
$memcached_servers = array(
    'default' => array(
        '127.0.0.1:11211'
    )
);
*/
define('WP_CACHE', $scf->server_cfg->wp_cache_disabled);

/** TimThumb Calls debugger **/
//define('DEBUG_CALLER', true);

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $scf->server_cfg->db);

/** MySQL database username */
define('DB_USER', $scf->server_cfg->user);

/** MySQL database password */
define('DB_PASSWORD', $scf->server_cfg->password);

/** MySQL hostname */
define('DB_HOST', $scf->server_cfg->host);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org 
secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to 
have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',          $scf->server_cfg->auth_key);
define('SECURE_AUTH_KEY',   $scf->server_cfg->secure_auth_key);
define('LOGGED_IN_KEY',     $scf->server_cfg->logged_in_key);
define('NONCE_KEY',         $scf->server_cfg->nonce_key);
define('AUTH_SALT',         $scf->server_cfg->auth_salt);
define('SECURE_AUTH_SALT',  $scf->server_cfg->secure_auth_salt);
define('LOGGED_IN_SALT',    $scf->server_cfg->logged_in_salt);
define('NONCE_SALT',        $scf->server_cfg->nonce_salt);
define('WP_CACHE_KEY_SALT', $scf->server_cfg->wp_cache_salt);
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

// ** Override the WordPress setting for the Blog URL ***//
/** Allow development environment movement */
define('WP_HOME', 'http://'.$_SERVER['HTTP_HOST']);
define('WP_SITEURL', 'http://'.$_SERVER['HTTP_HOST']);

define('CONCATENATE_SCRIPTS', false);

//Disable internal Wp-Cron function
    define('DISABLE_WP_CRON', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

//Changes for AD Problem - wilson_cursino@rd.com
//Load log function class
require_once(ABSPATH.'/wp-content/themes/readersdigest/lib/functions/logClass.php');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

