<?php 
if(!class_exists('Theme')){
	/* Load the Theme class. */
	require_once (TEMPLATEPATH . '/framework/theme.php');

	$theme = new Theme();
	$options = include(TEMPLATEPATH . '/framework/info.php');

	$theme->init($options);
}

/*
You can add your custom functions below 
It will not override by theme upgrade.
*/