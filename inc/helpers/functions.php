<?php

/**
 * Helper file for defining helper functions.
 * 
 * @since       1.0.0
 * @package     Theme404_Once_Click_Demo_Import
 * @subpackage  Theme404_Once_Click_Demo_Import/Inc/Helpers
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}


/**
 * Get active theme slug.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function theme404_ocdi_get_theme()
{
    if (defined('THEME404_OCDI_THEME')) {
        return THEME404_OCDI_THEME;
    }

    $activeTheme = wp_get_theme();

    if ($activeTheme->get('Template')) {
        return $activeTheme->get('Template');
    }

    return $activeTheme->get('TextDomain');
}

/**
 * Get active theme slug.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function theme404_ocdi_get_actual_theme()
{
    $activeTheme = wp_get_theme();

    if ($activeTheme->get('Template')) {
        return $activeTheme->get('Template');
    }

    return $activeTheme->get('TextDomain');
}

/**
 * Get active theme author name.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function theme404_ocdi_get_theme_author()
{
    $theme = wp_get_theme();

    if ($theme->get('Template')) {
        $theme = wp_get_theme($theme->get('Template'));
    }

    return $theme->get('Author');
}

/**
 * Returns the parent theme name.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function theme404_ocdi_get_theme_name()
{
    $theme = wp_get_theme();

    if ($theme->get('Template')) {
        $theme = wp_get_theme($theme->get('Template'));
    }

    return $theme->get('Name');
}

/**
 * Returns the WordPress uploads base directory.
 * 
 * @since 1.0.0
 * 
 * @return string Path to wordpress uploads folder.
 */
function theme404_ocdi_get_upload_base_dir()
{
    $wp_upload  = wp_upload_dir();
    $base_dir   = $wp_upload['basedir'];

    return $base_dir;
}

/**
 *  Custom directory path.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function theme404_ocdi_get_custom_uploads_dir()
{

    $upload_dir       = theme404_ocdi_get_theme();
    $base_dir         = theme404_ocdi_get_upload_base_dir();
    $demos_dir        = "{$base_dir}/{$upload_dir}-templates";

    return $demos_dir;
}

/**
 * Get the demos directory.
 * 
 * @since 1.0.0
 * @param string $slug Demo slug.
 * 
 * @return string.
 */
function theme404_ocdi_get_demos_dir($slug)
{
    $baseDir =  theme404_ocdi_get_custom_uploads_dir();

    return "{$baseDir}/$slug";
}

/**
 * Get the available widgets.
 * 
 * @return array
 */
function theme404_ocdi_get_available_widgets()
{
    global $wp_registered_widget_controls;

    $widget_controls = $wp_registered_widget_controls;

    $available_widgets = [];

    foreach ($widget_controls as $widget) {

        // No duplicates.
        if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
            $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
            $available_widgets[$widget['id_base']]['name']    = $widget['name'];
        }
    }

    return apply_filters('wie_available_widgets', $available_widgets);
}

/**
 * Error Log
 *
 * A wrapper function for the error_log() function.
 *
 * @since 1.0.0
 *
 * @param  mixed $message Error message.
 * 
 * @return void
 */
function theme404_ocdi_error_log($message = '')
{
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {

        if (is_array($message)) {
            $message = wp_json_encode($message);
        }

        error_log($message); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
    }
}
