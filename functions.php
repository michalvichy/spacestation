<?php

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {

wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
wp_enqueue_style( 'childstyle' );
wp_enqueue_script("default", get_stylesheet_directory_uri()."/js/default.js",array(),false,true);
wp_enqueue_script("corner", "http://malsup.github.io/jquery.corner.js",array(),false,true);

	/**
	  * LOAD SCRIPTS BASED ON TEMPLATE NAME
	  */

	// IF CURRENT TEMPLATE IS APP

	if(get_option('current_page_template') === 'Full Width App'){

		wp_register_style( 'pb-style', get_stylesheet_directory_uri() . '/css/pb-style.css'  );
		wp_enqueue_style( 'pb-style' );
	
		wp_enqueue_script("masonry", "https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js",array(),false,true);
		wp_enqueue_script("imagesLoaded", "http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js",array(),false,true);
		wp_enqueue_script("cookie", "http://jquery-list-grid.ssdtutorials.com/js/cookie.js",array(),false,true);
		wp_enqueue_script("PB_app", get_stylesheet_directory_uri()."/js/PB_app.js",array(),false,true);
	
	}
	
	// IF CURRENT TEMPLATE IS LISTING

	if(get_option('current_page_template') === 'Full Width Listing'){

		wp_register_style( 'pb-style', get_stylesheet_directory_uri() . '/css/pb-style.css'  );
		wp_enqueue_style( 'pb-style' );
		wp_register_style( 'px-video', get_stylesheet_directory_uri() . '/css/px-video.css'  );
		wp_enqueue_style( 'px-video' );
		wp_register_style( 'fontello', get_stylesheet_directory_uri() . '/css/fontello.css'  );
		wp_enqueue_style( 'fontello' );
	
		wp_enqueue_script("tabs", site_url()."/wp-includes/js/jquery/ui/tabs.min.js",array(),false,true);
		wp_enqueue_script("jcarousel", "https://cdnjs.cloudflare.com/ajax/libs/jcarousel/0.3.4/jquery.jcarousel.min.js",array(),false,true);
		wp_enqueue_script("jcarouselResponsive", get_stylesheet_directory_uri()."/js/jcarousel.responsive.js",array(),false,true);
		wp_enqueue_script("px-video", get_stylesheet_directory_uri()."/js/px-video.js",array(),false,true);
		wp_enqueue_script("PB_listing", get_stylesheet_directory_uri()."/js/PB_listing.js",array(),false,true);

	}

}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);


// get_childTheme_url
function get_childTheme_url() {
    return dirname( get_bloginfo('stylesheet_url') );
}


