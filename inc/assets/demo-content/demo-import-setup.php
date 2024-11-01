<?php
/*Import demo data*/
if ( ! function_exists( 'TOC_demo_import_files' ) ) :
    function TOC_demo_import_files() {
        return array(
            array(
                'import_file'                   => 'Power Magazine', 
                'theme_name'                    => esc_html__( 'Power Magazine', 'TOC-demo-import' ), 
                'import_file_url'               => 'power-magazine/powermagazine.xml',
                'import_widget_file_url'        => 'power-magazine/power-magazine.wie',
                'import_customizer_file_url'    => 'power-magazine/power-magazine.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/power-magazine/screenshot.png',
                'import_preview_url'		    => 'https://demo.theme404.com/power-magazine/default/',         
            ),           
            array(
                'import_file'                   => 'Minimal Business', 
                'theme_name'                    => 'Minimal Business',
                'import_file_url'               => 'minimal-business/minimalbusiness.xml',
                'import_widget_file_url'        => 'minimal-business/minimal-business.wie',
                'import_customizer_file_url'    => 'minimal-business/minimal-business.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/minimal-business/screenshot.png',
                'import_preview_url'            => 'https://demo.theme404.com/minimal-business/',         
            ),  
            array(
                'import_file'                   => 'Minimal Business corporate', 
                'theme_name'                    => 'Minimal Business',
                'import_file_url'               => 'minimal-business/corporate/minimalbusinesscorporate.WordPress.2020-02-05.xml',
                'import_widget_file_url'        => 'minimal-business/corporate/minimal-business-corporate.wie',
                'import_customizer_file_url'    => 'minimal-business/corporate/minimal-business-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/minimal-business/corporate/screenshot.jpg',
                'import_preview_url'            => 'https://demo.theme404.com/minimal-business/corporate/',         
            ),
            array(
                'import_file'                   => 'Real Estater', 
                'theme_name'                    => esc_html__( 'Real Estater', 'TOC-demo-import' ), 
                'import_file_url'               => 'real-estater/realestate.WordPress.2020-02-05.xml',
                'import_widget_file_url'        => 'real-estater/demo.theme404.com-real-estater-widgets.wie',
                'import_customizer_file_url'    => 'real-estater/real-estater-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/real-estater/screenshot.png',
                'import_preview_url'            => 'https://demo.theme404.com/real-estater/',         
            ), 
            array(
                'import_file'                   => 'eCommerce Shop', 
                'theme_name'                    => esc_html__( 'eCommerce Shop', 'TOC-demo-import' ), 
                'import_file_url'               => 'ecommerce-shop/default/ecommerce-shop.xml',
                'import_widget_file_url'        => 'ecommerce-shop/default/ecommerce-shop-widgets.wie',
                'import_customizer_file_url'    => 'ecommerce-shop/default/ecommerce-shop-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/ecommerce-shop/screenshot.png',
                'import_preview_url'            => 'https://demo.theme404.com/ecommerce-shop/default/',         
            ),  
            array(
                'import_file'                   => 'Book Store', 
                'theme_name'                    => esc_html__( 'eCommerce Shop', 'TOC-demo-import' ), 
                'import_file_url'               => 'ecommerce-shop/bookstore/bookhouse.wordpress.xml',
                'import_widget_file_url'        => 'ecommerce-shop/bookstore/ecommerce-shop-bookhouse-widgets.wie',
                'import_customizer_file_url'    => 'ecommerce-shop/bookstore/ecommerce-shop-export-book.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/ecommerce-shop/bookstore/screenshot.jpg',
                'import_preview_url'            => 'https://demo.theme404.com/ecommerce-shop/bookstore/',         
            ),  
            array(
                'import_file'                   => 'Decore', 
                'theme_name'                    => esc_html__( 'eCommerce Shop', 'TOC-demo-import' ), 
                'import_file_url'               => 'ecommerce-shop/decore/decorhouse.wordpress.xml',
                'import_widget_file_url'        => 'ecommerce-shop/decore/ecommerce-shop-decore-widgets.wie',
                'import_customizer_file_url'    => 'ecommerce-shop/decore/ecommerce-shop-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/ecommerce-shop/decore/screenshot.jpg',
                'import_preview_url'            => 'https://demo.theme404.com/ecommerce-shop/decore/',         
            ),
            array(
                'import_file'                   => 'Career Portfolio', 
                'theme_name'                    => esc_html__( 'Career Portfolio', 'TOC-demo-import' ), 
                'import_file_url'               => 'career-portfolio/careerportfolio.xml',
                'import_widget_file_url'        => 'career-portfolio/career-portfolio-widgets.wie',
                'import_customizer_file_url'    => 'career-portfolio/career-portfolio-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/career-portfolio/screenshot.png',
                'import_preview_url'            => 'https://demo.theme404.com/career-portfolio/default/',         
            ),  
            array(
                'import_file'                   => 'Business Agency', 
                'theme_name'                    => esc_html__( 'Career Portfolio', 'TOC-demo-import' ), 
                'import_file_url'               => 'career-portfolio/business-agency/businessagency.xml',
                'import_widget_file_url'        => 'career-portfolio/business-agency/career-portfolio-business-agency-widgets.wie',
                'import_customizer_file_url'    => 'career-portfolio/business-agency/career-portfolio-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/career-portfolio/business-agency/career-portfolio-business.png',
                'import_preview_url'            => 'https://demo.theme404.com/career-portfolio/business-agency/',         
            ),
            array(
                'import_file'                   => 'Education', 
                'theme_name'                    => esc_html__( 'Career Portfolio', 'TOC-demo-import' ), 
                'import_file_url'               => 'career-portfolio/education/education.xml',
                'import_widget_file_url'        => 'career-portfolio/education/career-portfolio-education-widgets.wie',
                'import_customizer_file_url'    => 'career-portfolio/education/career-portfolio-export.dat',
                'import_preview_image_url'      => THEME_ONE_CLICK_DEMO_CONTENT_PATH_URL .'/career-portfolio/education/career-portfolio-education.png',
                'import_preview_url'            => 'https://demo.theme404.com/career-portfolio/education/',         
            ),             
        ); 
    }

    add_filter( 'before_theme_setup', 'TOC_demo_import_files', 9 );

endif;

/**
 * Action that happen after import
 */
if ( ! function_exists( 'TOC_after_demo_import' ) ) :
    function TOC_after_demo_import( $selected_import ) {

        $primary_menu   = get_term_by('name', 'Main Menu', 'nav_menu'); 
        $social_menu    = get_term_by('name', 'Social Menu', 'nav_menu');    
        $top_menu       = get_term_by('name', 'Top Menu', 'nav_menu');         
        set_theme_mod( 'nav_menu_locations' , array( 
            'menu-1'        => $primary_menu->term_id,
            'social-media'  => $social_menu->term_id, 
            'top-menu'      => $social_menu->term_id, 
            ) 

        );
        //Set Front page
        $page = get_page_by_title( 'Home');
        if ( isset( $page->ID ) ) {
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
        } 
        
    }

    add_action( 'after_theme_setup', 'TOC_after_demo_import', 10, 1 );

endif;

/**
 * Required Plugins
 */

if ( ! function_exists( 'TOC_require_plugin' ) ) :
    function TOC_require_plugin( $selected_import ) {

        if( $selected_import == 'Power Magazine'):
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),
                array('name' => 'Newsletter', 'install' => 'newsletter/plugin.php'),
            );                    
        elseif ( $selected_import == 'Minimal Business corporate'): 
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),
                array('name' => 'Newsletter', 'install' => 'newsletter/plugin.php'),
                array('name' => 'Elementor', 'install' => 'elementor/elementor.php'),
            ); 
        elseif ( $selected_import == 'Real Estater'): 
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),
                array('name' => 'Newsletter', 'install' => 'newsletter/plugin.php'),
            );
        elseif ( $selected_import == 'Business Agency'): 
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),
                array('name' => 'Elementor', 'install' => 'elementor/elementor.php'),
            );
        elseif ( $selected_import == 'Education'): 
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),
                array('name' => 'Elementor', 'install' => 'elementor/elementor.php'),
            );                                              
        else:        
            $plugins = array(
                array('name' => 'Contact Form 7', 'install' => 'contact-form-7/wp-contact-form-7.php'),                
            );    
        endif;

        return $plugins;
    }

    add_filter( 'plugin_list', 'TOC_require_plugin');

endif;