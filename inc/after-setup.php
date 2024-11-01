<?php
class TOC_Demo_Setup_After_Import {

	function TOC_after_import_setup($args) {

		$primary_menu = get_term_by( 'name', 'Primary', 'nav_menu' ); 
        $account_menu = get_term_by( 'name', 'My Account', 'nav_menu' );
        $info_menu = get_term_by( 'name', 'Information', 'nav_menu' );
        $product_menu = get_term_by( 'name', 'Product Category', 'nav_menu' );
        $brands_menu = get_term_by( 'name','Brands', 'nav_menu' );

            set_theme_mod( 'nav_menu_locations' , array( 

                'menu-1' => $primary_menu->term_id,
                'menu-2' => $account_menu->term_id,
                'menu-3' => $info_menu->term_id,
                'menu-4' => $product_menu->term_id,
                'menu-5' => $brands_menu->term_id,
                 ) 

            );

		// Assign front page and posts page (blog page).

        if( $args['import_file'] == 'Demo Import 1' ):

			$front_page_id = get_page_by_title( 'Shop' );

			$blog_page_id  = get_page_by_title( 'Blog' );

		elseif ( $args['import_file'] == 'Demo Import 2' ):

			$front_page_id = get_page_by_title( 'Car Shop' );

			$blog_page_id  = get_page_by_title( 'Blog' );

		elseif ( $args['import_file'] == 'Demo Import 3' ):

			$front_page_id = get_page_by_title( 'Car Shop' );

			$blog_page_id  = get_page_by_title( 'Blog' );
		endif;	

		update_option( 'show_on_front', 'page' );

		update_option( 'page_on_front', $front_page_id->ID );

		update_option( 'page_for_posts', $blog_page_id->ID );

		// Deleting Hello World, Sample Page and Policy Page
		wp_delete_post(1);

		wp_delete_post(2);

	}
}
