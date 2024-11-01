<?php 
/**
 * TOC demo setup
 */

class TOC_DemoSetup{

	function __construct(){
		add_action( 'admin_menu',array( $this,'menu' ));
		add_action( 'admin_enqueue_scripts', array($this,'style_scripts'));
		add_action( 'wp_ajax_TOC_demo_import_ajax_setup', array( $this, 'TOC_import_xml' ) );
		add_action( 'wp_ajax_TOC_demo_install_plugin', array( $this, 'TOC_install_plugin' ) );
		ini_set( 'max_execution_time', 0 );
	}

	function menu(){

		global $page_hook_suffix;

    	$page_hook_suffix = add_theme_page( __( 'Theme One Click Demo Setup', 'TOC-demo-import' ), __( 'TOC Demo Setup', 'TOC-demo-import' ), 'upload_files', 'TOC-demo-import', array( $this, 'screen' ) );
	}

	function style_scripts($hook){

		global $page_hook_suffix;
		
		wp_enqueue_script( 'TOC-demo-import', THEME_ONE_CLICK_DEMO_IMPORT_URL.'/inc/assets/js/TOC-demo-import.js', array( 'jquery' ), '1.0', true );
		$data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		wp_localize_script( 'TOC-demo-import', 'TOC_demo_import_object', $data );
		
		//return if not option page
    	if( $hook == $page_hook_suffix ):

        	//enqueue bootstrap to option page
		    wp_register_style('TOC_view_bootstrap', THEME_ONE_CLICK_DEMO_IMPORT_URL.'/inc/assets/css/bootstrap.min.css');
		    wp_enqueue_style('TOC_view_bootstrap');
		    wp_enqueue_style( 'TOC-demo-style', THEME_ONE_CLICK_DEMO_IMPORT_URL.'/inc/assets/css/style.css' );
		    wp_enqueue_script( 'TOC-demo-bsjs', THEME_ONE_CLICK_DEMO_IMPORT_URL.'/inc/assets/js/bootstrap.min.js', array( 'jquery' ), '', true );
        endif;
	    
	    
	}

	function TOC_import_xml(){
		
		$import_value = $_POST[ 'import_id' ];
		$download_xml = $_POST[ 'dl_xml_file' ];
		$download_dat = $_POST[ 'dl_dat_file' ];
		$download_wie = $_POST[ 'dl_wie_file' ];

		$before_setup_setting = new TOC_Demo_Setup_Before_Import();
		
		// $before_setup_setting->before_demo_import();
		
		$pre_demo_options = apply_filters( 'before_theme_setup' , array() );
		    
		    //Extracting the demo files
		    $args = array(
		    	'import_file' => $pre_demo_options[$import_value]['import_file'],
		        'file'        => $pre_demo_options[$import_value]['import_file_url'],
		        'dat_file'    => $pre_demo_options[$import_value]['import_customizer_file_url'],
				'wie_file'    => $pre_demo_options[$import_value]['import_widget_file_url'],
				'slider_file'    => $pre_demo_options[$import_value]['slider_file_url'],			
		        'map_user_id' => 1,
		    );
		    

		//File directory to check the above required file

		$xml_file_name = $args['file'];
		$dat_file_name = $args['dat_file'];
		$wie_file_name = $args['wie_file'];
		$slider_file_name = $args['slider_file'];


		// Validate url
		if (filter_var($xml_file_name, FILTER_VALIDATE_URL) !== false) {
			$file_basename = basename($xml_file_name); 
			$file_path = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$file_basename;
			file_put_contents( $file_path,file_get_contents($xml_file_name));
			$xml_file = $file_path;
			$args['file'] = $file_basename;

		} else {
			$xml_file = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$xml_file_name;
		}

		if (filter_var($dat_file_name, FILTER_VALIDATE_URL) !== false) {
			$dat_basename = basename($dat_file_name);
			$dat_path = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$dat_basename;
			file_put_contents( $dat_path,file_get_contents($dat_file_name));
			$dat_file = $dat_path;
			$args['dat_file'] = $dat_basename;
		} else {
			$dat_file = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$dat_file_name;
		}

		if (filter_var($wie_file_name, FILTER_VALIDATE_URL) !== false) {
			$wie_basename = basename($wie_file_name);
			$wie_path = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$wie_basename;
			file_put_contents( $wie_path,file_get_contents($wie_file_name));
			$wie_file = $wie_path;
			$args['wie_file'] = $wie_basename;
		} else {
			$wie_file = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$wie_file_name;
		}
		


		if( file_exists($xml_file) && $download_xml == "yes" && file_exists($dat_file) ):

			$before_setup_setting->delete_nav_menus();
				// Call Slider Importer function 
				
				// Call XML importer function 
				   TOC_xml_import( $args );
				   TOC_dat_import($args);

				   if( is_plugin_active( 'smart-slider-3/smart-slider-3.php' ) ) {
					   foreach($slider_file_name as $slider_value){

						if (filter_var($slider_value, FILTER_VALIDATE_URL) !== false) {
							$slider_file = $slider_value;
						} else {
							$slider_file = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$slider_value;
						}
						// $slider_file = THEME_ONE_CLICK_DEMO_IMPORT_FILE_PATH.$slider_value;
						SmartSlider3::import($slider_file);
					   }
					   
					
				}

		endif;
		if ( file_exists($dat_file) && $download_dat == "yes" ):
			
			//Call dat importer function		
		endif;

		if ( file_exists($wie_file) && $download_wie == "yes" ): 
		    // $before_setup_setting->reset_widgets();
		    // Call WEI importer function
			TOC_wie_import( $args );
		endif;
		

		apply_filters( 'after_theme_setup' , $args);


	}

	function TOC_install_plugin() {

		$import_value = $_POST[ 'import_id' ];

		$strip_value = stripcslashes($import_value);

		$import_data = json_decode( $strip_value, true );

		TOC_get_plugins($import_data);
	}

    function screen() {

    	new TOC_Demo_Setup_Before_Import();
    	
    	$pre_demo_options = apply_filters( 'before_theme_setup' , array() );

		include THEME_ONE_CLICK_DEMO_IMPORT_PATH .'/admin/view.php';
	    
	    ?>
	    <div class="ajax-loader">
		  
		  <div class="loader"></div>
		
		</div>
		
		<div id="div1" class="container result">
		
		</div>

	    
	    <?php
	}




	
}

$TOC_demo_setup = new TOC_DemoSetup();



