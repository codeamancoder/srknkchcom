<?php
return array(
	"title" => __("Advanced Divider Line", "striking_admin"),
	"shortcode" => 'divider_advanced',
	"type" => 'self-closing',
	"options" => array(
		array(
			"name" => __("Text Color (optional)",'striking_admin'),
			"id" => "color",
			"default" => "",
			"type" => "color"
		),
		array (
			"name" => __("Padding Top (optional)",'striking_admin'),
			"id" => "paddingTop",
			"default" => '0',
			"min" => 0,
			"max" => 200,
			"step" => "1",
			"type" => "range"
		),
		array (
			"name" => __("Padding Bottom (optional)",'striking_admin'),
			"id" => "paddingBottom",
			"default" => '0',
			"min" => 0,
			"max" => 200,
			"step" => "1",
			"type" => "range"
		),
	),
	"custom" => '',
);