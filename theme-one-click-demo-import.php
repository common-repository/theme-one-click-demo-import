<?php

/**
 * Theme404 Once Click Import
 *
 * @package           Theme404_One_Click_Demo_Import
 * @author            Theme404
 * @copyright         2020 Theme404
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Theme One Click Demo Import
 * Plugin URI:        https://theme404.com/
 * Description:       Import Theme404 official themes demo content, widgets and theme settings with just one click.
 * Version:           2.1
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Theme404
 * Author URI:        https://theme404.com
 * Text Domain:       theme404-one-click-demo-import
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


if (!defined('WPINC')) {
    exit;   // Exit if accessed directly.
}

/* Set constant path to the main file for activation call */
define('THEME404_OCDI_CORE_FILE', __FILE__);

/* Require our constants declaration file */
require_once plugin_dir_path(__FILE__) . 'inc/helpers/constant.php';

/* Load our other helper files. */
require_once THEME404_OCDI_HELPERS . 'debug-helper.php';
require_once THEME404_OCDI_HELPERS . 'functions.php';

/**
 * Fires immediately after this plugin is activated.
 */
function theme404ocdiActivation()
{
    require_once THEME404_OCDI_CORE . 'class-theme404-ocdi-activation.php';
    Theme404_OCDI_Activation::activate();
}
register_activation_hook(__FILE__, 'theme404ocdiActivation');

/* Load our plugin core file */
require_once THEME404_OCDI_CORE . 'class-theme404-ocdi.php';

/**
 * The main function responsible for returning the one true
 * Theme404_OCDI Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except
 * without needing to declare the global.
 *
 * @since 1.0.0
 * @return Theme404_OCDI Theme404_OCDI Instance
 */
function theme404_ocdi()
{
    return Theme404_OCDI::getInstance();
}

/**
 * Loads the main instance of Theme404_OCDI to prevent
 * the need to use globals.
 *
 * @since 1.0.0
 * @return object Theme404_OCDI
 */
theme404_ocdi();
