<?php

/**
 * Helper file for defining the plugin constants.
 * 
 * @since       1.0.0
 * @package     Theme404_Once_Click_Demo_Import
 * @subpackage  Theme404_Once_Click_Demo_Import/Inc/Helpers
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

$plugin_bootstrap = THEME404_OCDI_CORE_FILE;

$THEME404_OCDI_root      = wp_normalize_path(plugin_dir_path($plugin_bootstrap));
$THEME404_OCDI_uri       = plugin_dir_url($plugin_bootstrap);

define('THEME404_OCDI_VERSION', '1.0.0');

/**
 * Core constants to be overridden by individual theme.
 */

defined('THEME404_OCDI_PLUGIN_NAME') || define('THEME404_OCDI_PLUGIN_NAME', 'Theme404 One Click Demo Import');

if (!defined('THEME404_OCDI_AUTHOR')) {
    define('THEME404_OCDI_AUTHOR', 'Theme404');
}

if (!defined('THEME404_OCDI_AUTHOR_URI')) {
    define('THEME404_OCDI_AUTHOR_URI', 'https://wordpress.org/themes/author/theme404/');
}

if (!defined('THEME404_OCDI_API_URL')) {
    define('THEME404_OCDI_API_URL', 'http://demo.theme404.com/wp-json/demos/v1/');
}




// Plugin internal structure.
define('THEME404_OCDI_ROOT', $THEME404_OCDI_root);
define('THEME404_OCDI_LANGUAGES', THEME404_OCDI_ROOT . 'languages/');
define('THEME404_OCDI_INC', THEME404_OCDI_ROOT . 'inc/');
define('THEME404_OCDI_CORE', THEME404_OCDI_INC . 'core/');
define('THEME404_OCDI_CLASSES', THEME404_OCDI_CORE . 'classes/');
define('THEME404_OCDI_IMPORTER', THEME404_OCDI_CLASSES . 'importer/');
define('THEME404_OCDI_UI', THEME404_OCDI_CORE . 'ui/');
define('THEME404_OCDI_CONFIG', THEME404_OCDI_INC . 'config/');
define('THEME404_OCDI_HELPERS', THEME404_OCDI_INC . 'helpers/');
define('THEME404_OCDI_VIEWS', THEME404_OCDI_INC . 'views/');

// Plugin assets urls.
define('THEME404_OCDI_ASSETS', $THEME404_OCDI_uri . 'assets/');
define('THEME404_OCDI_IMAGES', THEME404_OCDI_ASSETS . 'images/');
define('THEME404_OCDI_CSS', THEME404_OCDI_ASSETS . 'css/');
define('THEME404_OCDI_JS', THEME404_OCDI_ASSETS . 'js/');
