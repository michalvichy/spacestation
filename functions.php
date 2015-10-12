<?php

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {
wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
wp_enqueue_style( 'childstyle' );
wp_enqueue_script("default", get_stylesheet_directory_uri()."/js/default.js",array(),false,true);
wp_enqueue_script("corner", "http://malsup.github.io/jquery.corner.js",array(),false,true);
}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);


