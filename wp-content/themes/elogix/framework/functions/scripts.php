<?php

/* ---------------------------------------------------- */
/* Javascripts											*/
/* ---------------------------------------------------- */

function register_scripts() {
	wp_deregister_script('jquery'); // register local jquery
	wp_register_script('jquery', get_template_directory_uri() . '/framework/js/jquery.js', 'jquery', '1.7');
	wp_register_script('easing', get_template_directory_uri() . '/framework/js/jquery.easing.js', 'jquery', '1.0', TRUE);
	wp_register_script('selectivizr', get_template_directory_uri() . '/framework/js/selectivizr.js', 'jquery', '1.0', TRUE);
	wp_register_script('mediaqueries', get_template_directory_uri() . '/framework/js/mediaqueries.js', 'jquery', '1.0', TRUE);
	wp_register_script('superfish', get_template_directory_uri() . '/framework/js/superfish.js', 'jquery', '1.0', TRUE);
	
	wp_register_script('isotope', get_template_directory_uri() . '/framework/js/jquery.isotope.js', 'jquery', '1.0', TRUE);
	wp_register_script('prettyphoto', get_template_directory_uri() . '/framework/js/jquery.prettyPhoto.js', 'jquery', '1.0', TRUE);
	wp_register_style('prettyphoto_css', get_bloginfo('stylesheet_directory').'/framework/js/prettyPhoto/css/prettyPhoto.css');
	wp_register_script('slider', get_template_directory_uri() . '/framework/js/jquery.flexslider.js', 'jquery', '1.0');
	wp_register_script('sliderscript', get_template_directory_uri() . '/framework/js/slider.js', 'jquery', '1.0', TRUE);
	wp_register_script('carousel', get_template_directory_uri() . '/framework/js/jquery.jcarousel.js', 'jquery', '1.0', TRUE);
	wp_register_script('mobilemenu', get_template_directory_uri() . '/framework/js/jquery.mobilemenu.js', 'jquery', '1.0', TRUE);
	wp_register_script('touchwipe', get_template_directory_uri() . '/framework/js/jquery.touchwipe.min.js', 'jquery', '1.0', TRUE);
	
	wp_register_script('scripts', get_template_directory_uri() . '/framework/js/scripts.js', 'jquery', '1.0', TRUE);
	wp_register_script('work', get_template_directory_uri() . '/framework/js/work.js', 'jquery', '1.0', TRUE);
	wp_register_script('custom', get_template_directory_uri() . '/framework/js/custom.php', 'jquery', '1.0', TRUE);
}

 

/* ---------------------------------------------------- */
/* Load Scripts											*/
/* ---------------------------------------------------- */

function print_scripts() {
	// load scripts into queue
	wp_enqueue_script('jquery');
  	wp_enqueue_script('easing');
  	wp_enqueue_script('selectivizr');
  	wp_enqueue_script('mediaqueries');
  	wp_enqueue_script('superfish');
  	wp_enqueue_script('mobilemenu');
  	wp_enqueue_style('prettyphoto_css');
  	wp_enqueue_script('prettyphoto');
  	wp_enqueue_script('slider');
  
  // portfolio scripts
  if(is_page_template('page-work.php')) {
     wp_enqueue_script('isotope');
     wp_enqueue_script('work');
  }
  
  if(is_singular('work')) {
     wp_enqueue_script('sliderscript');
  }
  
  if(is_page_template('page-home.php')) {
  	 wp_enqueue_script('touchwipe');
     wp_enqueue_script('carousel');
     wp_enqueue_script('sliderscript');
  }
    
  // comments reply wordpress script
  if(is_singular() && comments_open() && get_option( 'thread_comments' )) {
   	wp_enqueue_script( 'comment-reply' );
  }  
  
  wp_enqueue_script('scripts');
  wp_enqueue_script('custom');
}

// add scripts not on dashboard
if(!is_admin()) {
 	add_action('init','register_scripts');
 	add_action('wp_print_scripts','print_scripts');
}

/* ---------------------------------------------------- */
/* EOF */
/* ---------------------------------------------------- */

?>