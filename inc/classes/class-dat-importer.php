<?php

/**
 * The main export/import class.
 *
 * @since 0.1
 */
final class TOC_DAT_IMPORTER {

	/**
	 * Load a translation for this plugin.
	 *
	 * @since 0.1
	 * @return void
	 */
	
	/**
	 * Check to see if we need to do an export or import.
	 * This should be called by the customize_register action.
	 *
	 * @since 0.1
	 * @since 0.3 Passing $wp_customize to the export and import methods.
	 * @param object $wp_customize An instance of WP_Customize_Manager.
	 * @return void
	 */
	public function init( $wp_customize, $args )
	{
		if ( current_user_can( 'edit_theme_options' ) ) {

				$this->TOC_import( $wp_customize, $args );
		}
	}


	/**
	 * Imports uploaded mods and calls WordPress core customize_save actions so
	 * themes that hook into them can act before mods are saved to the database.
	 *
	 * @since 0.1
	 * @since 0.3 Added $wp_customize param and importing of options.
	 * @access private
	 * @param object $wp_customize An instance of WP_Customize_Manager.
	 * @return void
	 */
	public function TOC_import( $wp_customize, $args )
	{
		

		// Make sure WordPress upload support is loaded.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		// Setup global vars.
		global $wp_customize;
		global $cei_error;

		
		

		$dat_file_path = THEME_ONE_CLICK_DEMO_CONTENT_PATH.$args['dat_file'];
		$raw  = file_get_contents( $dat_file_path );
		$data = @unserialize( $raw );

		// Import images.
		
			$data['mods'] = $this->TOC_import_images( $data['mods'] );
		

		// Import custom options.
		if ( isset( $data['options'] ) ) {

			foreach ( $data['options'] as $option_key => $option_value ) {
				$option = new TOC_DAT_Option( $wp_customize, $option_key, array(
					'default'		=> '',
					'type'			=> 'option',
					'capability'	=> 'edit_theme_options'
				) );

				$option->import( $option_value );
			}
		}

		// If wp_css is set then import it.
		if( function_exists( 'wp_update_custom_css_post' ) && isset( $data['wp_css'] ) && '' !== $data['wp_css'] ) {
			wp_update_custom_css_post( $data['wp_css'] );
		}

		

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {

			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );

			// Save the mod.
			set_theme_mod( $key, $val );
		}

		
		echo '<h3 class="title"> Customizer Import Report </h2>' ;
		echo "<p> Customizer Data Successfully Imported</p>";
	}

	/**
	 * Imports images for settings saved as mods.
	 *
	 * @since 0.1
	 * @access private
	 * @param array $mods An array of customizer mods.
	 * @return array The mods array with any new import data.
	 */
	private function TOC_import_images( $mods )
	{
		foreach ( $mods as $key => $val ) {

			if ( $this->TOC_is_image_url( $val ) ) {

				$data = $this->TOC_sideload_image( $val );

				if ( ! is_wp_error( $data ) ) {

					$mods[ $key ] = $data->url;

					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	/**
	 * Taken from the core media_sideload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $file The image file path.
	 * @return array An array of image data.
	 */
	private function TOC_sideload_image( $file )
	{
		$data = new stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}
		if ( ! empty( $file ) ) {

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );



			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			//Checking if the media already exist or not
			global $wpdb;
			
			$year = date("Y/m");
			$image_src = wp_upload_dir()['baseurl'] . '/'.$year. '/' . _wp_relative_upload_path( $file_array['name'] );
			
			$query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE guid='$image_src'";
			
			$count = intval($wpdb->get_var($query));
			
			if($count == 0):
					// Do the validation and storage stuff.
					$id = media_handle_sideload( $file_array, 0 );
			else:
				$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_src ));
				$id = $attachment[0];
			endif;

			
			

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}

			// Build the object to return.
			$meta					= wp_get_attachment_metadata( $id );
			$data->attachment_id	= $id;
			$data->url				= wp_get_attachment_url( $id );
			$data->thumbnail_url	= wp_get_attachment_thumb_url( $id );
			$data->height			= $meta['height'];
			$data->width			= $meta['width'];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $string The string to check.
	 * @return bool Whether the string is an image url or not.
	 */
	private function TOC_is_image_url( $string = '' )
	{
		if ( is_string( $string ) ) {

			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}
}

function TOC_dat_import($args){

$dat_import = new TOC_DAT_IMPORTER();
$dat_import->init( $wp_customize, $args );

}