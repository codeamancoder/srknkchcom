<?php 
$config = array(
	'title' => __('Blog Single Options','striking_admin'),
	'id' => 'single',
	'pages' => array('post'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Featured Image",'striking_admin'),
		"desc" => __("Whether to dispaly Featured Image in Single Blog page. This will override the global configuration",'striking_admin'),
		"id" => "_featured_image",
		"default" => '',
		"type" => "tritoggle",
	),
);
new metaboxesGenerator($config,$options);