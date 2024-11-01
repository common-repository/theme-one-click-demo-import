<?php
	
/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * @since 0.3
 */


include_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
  
    class TOC_DAT_Option extends WP_Customize_Setting {
		
		public function import( $value ) 
		{
			$this->update( $value );	
		}
	}


