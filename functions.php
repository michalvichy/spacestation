<?php

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {
wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
wp_enqueue_style( 'childstyle' );
}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);


/* Register Menus */

if (!function_exists('qode_register_menus')) {
	/**
	 * Function that registers menu positions
	 */
	function qode_register_menus() {
		global $qode_options_proya;

		if((isset($qode_options_proya['header_bottom_appearance']) && $qode_options_proya['header_bottom_appearance'] != "stick_with_left_right_menu") || (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes")){
			//header and left menu location
			register_nav_menus(
				array('top-navigation' => __( 'Top Navigation', 'qode')
				)
			);
			
			//header right menu location
			register_nav_menus( array( 'right-top-navigation' => __( 'Right Top Navigation', 'qode') ) );
		}

		//popup menu location
		register_nav_menus(
			array('popup-navigation' => __( 'Fullscreen Navigation', 'qode')
			)
		);

		if((isset($qode_options_proya['header_bottom_appearance']) && $qode_options_proya['header_bottom_appearance'] == "stick_with_left_right_menu") && (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "no")){
			//header left menu location
			register_nav_menus(
				array('left-top-navigation' => __( 'Left Top Navigation', 'qode')
				)
			);

			//header right menu location
			register_nav_menus( array( 'right-top-navigation' => __( 'Right Top Navigation', 'qode') ) );
		}
	}

	add_action( 'after_setup_theme', 'qode_register_menus' );
}
