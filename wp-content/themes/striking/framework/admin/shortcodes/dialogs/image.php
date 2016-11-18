<?php
if (! function_exists("theme_get_image_size")) {
	function theme_get_image_size(){
		$customs =  theme_get_option('image','customs');
		$sizes = array(
			"small" => __("Small",'striking_admin'),
			"medium" => __("Medium",'striking_admin'),
			"large" => __("Large",'striking_admin'),
		);
		if(!empty($customs)){
			$customs = explode(',',$customs);
			foreach($customs as $custom){
				$sizes[$custom] = ucfirst(strtolower($custom));
			}
		}
		return $sizes;
	}
}
return array(
	"title" => __("Image", "striking_admin"),
	"shortcode" => 'image',
	"type" => 'self-closing',
	"options" => array(
		array(
			"name" => __("Image Source",'striking_admin'),
			"id" => "source",
			"size" => 30,
			"default" => "",
			"type" => "upload",
		),
		array(
			"name" => __("Image Title (optional)",'striking_admin'),
			"id" => "title",
			"size" => 30,
			"default" => "",
			"type" => "text",
		),
		array(
			"name" => __("Align (optional)",'striking_admin'),
			"id" => "align",
			"default" => '',
			"prompt" => __("Choose one..",'striking_admin'),
			"options" => array(
				"left" => __('Left','striking_admin'),
				"right" => __('Right','striking_admin'),
				"center" => __('Center','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Effect",'striking_admin'),
			"desc" => __("Effect when hover the feature image.",'striking_admin'),
			"id" => "effect",
			"default" => 'icon',
			"options" => array(
				"icon" => __("Icon",'striking_admin'),
				"grayscale" => __("Grayscale",'striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Icon (optional)",'striking_admin'),
			"id" => "icon",
			"default" => '',
			"prompt" => __("Choose one..",'striking_admin'),
			"options" => array(
				"zoom" => __('Zoom','striking_admin'),
				"play" => __('Play','striking_admin'),
				"doc" => __('Doc','striking_admin'),
				"link" => __('Link','striking_admin'),
			),
			"type" => "select",
		),
		array (
			"name" => __("Lightbox",'striking_admin'),
			"id" => "lightbox",
			"default" => false,
			"type" => "toggle"
		),
		array (
			"name" => __("Lightbox group (optional)",'striking_admin'),
			"id" => "group",
			"default" => '',
			"type" => "text"
		),
		array(
			"name" => __("Size (optional)",'striking_admin'),
			"id" => "size",
			"default" => '',
			"prompt" => __("Choose one..",'striking_admin'),
			"options" => theme_get_image_size(),
			"type" => "select",
		),
		array (
			"name" => __("Width (optional)",'striking_admin'),
			"id" => "width",
			"default" => 0,
			"min" => 0,
			"max" => 960,
			"step" => "1",
			"type" => "range"
		),
		array (
			"name" => __("Height (optional)",'striking_admin'),
			"id" => "height",
			"default" => 0,
			"min" => 0,
			"max" => 960,
			"step" => "1",
			"type" => "range"
		),
		array (
			"name" => __("Auto adjust Height",'striking_admin'),
			"id" => "autoHeight",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Link (optional)",'striking_admin'),
			"size" => 30,
			"id" => "link",
			"default" => "",
			"type" => "text",
		),
		array(
			"name" => __("Link Target (optional)",'striking_admin'),
			"id" => "linkTarget",
			"default" => '',
			"prompt" => __("Choose one..",'striking_admin'),
			"options" => array(
				"_blank" => __('Load in a new window','striking_admin'),
				"_self" => __('Load in the same frame as it was clicked','striking_admin'),
				"_parent" => __('Load in the parent frameset','striking_admin'),
				"_top" => __('Load in the full body of the window','striking_admin'),
			),
			"type" => "select",
		),
		array (
			"name" => __("Quality (optional)",'striking_admin'),
			"id" => "quality",
			"default" => 75,
			"min" => 75,
			"max" => 100,
			"step" => "1",
			"type" => "range"
		),
	),
	"custom" => '',
);