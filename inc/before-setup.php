<?php

include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php' );

class TOC_Demo_Setup_Before_Import{

	public function __construct() {
		add_filter( 'before_theme_setup', array( $this, 'predefined_demo_options'), 5);
        wp_delete_post(1);
        $sample_page = get_page_by_title( 'Sample Page' );

        if ( $sample_page ){
             wp_delete_post($sample_page->ID);
        }

       
	}

	function predefined_demo_options(){
		$demo_options = array(
	        array(
	            'import_file'                => 'No File',
	            'categories'                 => 'category-1',
	            'import_file_url'            => '',
	            'import_customizer_file_url' =>	'',
	            'import_widget_file_url' 	 => '',
	            'import_preview_image_url'   => THEME_ONE_CLICK_DEMO_IMPORT_URL.'/inc/assets/demo-files/demo-1/image.jpg',
	        ),

		);

		return($demo_options);
	}

    /**
       * Delete existing navigation menus.
       */
    function delete_nav_menus() {
        $nav_menus = wp_get_nav_menus();

        // Delete navigation menus.
        if ( ! empty( $nav_menus ) ) {
            foreach ( $nav_menus as $nav_menu ) {
                wp_delete_nav_menu( $nav_menu->slug );
            }
        }
    }


}

class Quiet_Skin extends WP_Upgrader_Skin {
    public function feedback($string, ...$args)
    {
        // just keep it quiet
    }
}



function TOC_get_plugins($plugin)
{
    TOC_plugin_download( $plugin['name'] );
    TOC_plugin_activate($plugin['install']);
}

function TOC_plugin_download($pluguin_name)
{
    
    include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..

    $plugin = $pluguin_name;

    $api = plugins_api( 'plugin_information', array(
        'slug' => $plugin,
        'fields' => array(
            'short_description' => false,
            'sections' => false,
            'requires' => false,
            'rating' => false,
            'ratings' => false,
            'downloaded' => false,
            'last_updated' => false,
            'added' => false,
            'tags' => false,
            'compatibility' => false,
            'homepage' => false,
            'donate_link' => false,
        ),
    ));

    //includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
    include_once( ABSPATH . 'wp-admin/includes/file.php' );
    include_once( ABSPATH . 'wp-admin/includes/misc.php' );
    include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
    
    $upgrader = new Plugin_Upgrader( new Quiet_Skin() );

    $upgrader->install($api->download_link);

}

function TOC_plugin_activate($installer)
{
    $current = get_option('active_plugins');
    $plugin = plugin_basename(trim($installer));

    if(!in_array($plugin, $current))
    {
            $current[] = $plugin;
            sort($current);
            do_action('activate_plugin', trim($plugin));
            update_option('active_plugins', $current);
            do_action('activate_'.trim($plugin));
            do_action('activated_plugin', trim($plugin));
            return true;
    }
    else
            return false;
}



