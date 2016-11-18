<?php
return array(
	"title" => __("Styled List", "striking_admin"),
	"shortcode" => 'list',
	"type" => 'enclosing',
	"options" => array(
		array(
			"name" => __("Style",'striking_admin'),
			"id" => "style",
			"default" => '',
			"options" => array(
				"list1" => 'list1',
				"list2" => 'list2',
				"list3" => 'list3',
				"list4" => 'list4',
				"list5" => 'list5',
				"list6" => 'list6',
				"list7" => 'list7',
				"list8" => 'list8',
				"list9" => 'list9',
				"list10" => 'list10',
				"list11" => 'list11',
				"list12" => 'list12',
			),
			"type" => "select",
		),
		array(
			"name" => __("Color (optional)",'striking_admin'),
			"id" => "color",
			"default" => "",
			"prompt" => __("Choose one..",'striking_admin'),
			"options" => array(
				"black" => 'Black',
				"gray" => 'Gray',
				"red" => 'Red',
				"yellow" => 'Yellow',
				"blue" => 'Blue',
				"pink" => 'Pink',
				"green" => 'Green',
				"rosy" => 'Rosy',
				"orange" => 'Orange',
				"magenta" => 'Magenta',
			),
			"type" => "select",
		),
		array(
			"name" => __("Content",'striking_admin'),
			"id" => "content",
			"default" => "",
			"type" => "textarea"
		),
	),
	"custom" => '',
);