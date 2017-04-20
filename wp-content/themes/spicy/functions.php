<?php
add_action( 'wp_enqueue_scripts', 'spicy_theme_css',999);
function spicy_theme_css() {
    wp_enqueue_style( 'spicy-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'spicy-child-style', get_stylesheet_uri(), array( 'spicy-parent-style' ) );
	wp_enqueue_style( 'default-css', get_stylesheet_directory_uri()."/css/default.css" );
	wp_dequeue_style( 'rambo-default-css',get_template_directory_uri() .'/css/default.css');
	wp_enqueue_style ('spicy-bootstrap',get_template_directory_uri().'/css/bootstrap.css');
	wp_enqueue_style ('spicy-bootstrap-responsive',get_template_directory_uri() .'/css/bootstrap-responsive.css');
}
?>