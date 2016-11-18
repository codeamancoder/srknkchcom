<?php
$options = array(
	array(
		"name" => __("General",'striking_admin'),
		"type" => "title",
	),
	array(
		"name" => __("Header General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Header Height",'striking_admin'),
			"desc" => "",
			"id" => "header_height",
			"min" => "60",
			"max" => "300",
			"step" => "1",
			"unit" => 'px',
			"default" => "90",
			"type" => "range"
		),
		array(
			"name" => __("Display Custom Logo",'striking_admin'),
			"desc" => sprintf(__('If this option is on, you should fill the text field below. Or you should define "Site Title" in <a href="%s/wp-admin/options-general.php">Settings->General</a> instead of a logo
image.','striking_admin'),get_option('siteurl')),
			"id" => "display_logo",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Custom Logo",'striking_admin'),
			"id" => "logo",
			"default" => "",
			"type" => "upload"
		),
		array(
			"name" => __("Display Site Description",'striking_admin'),
			"desc" => sprintf(__('You can choose whether to display <a href="%s/wp-admin/options-general.php">Tagline</a> after Site Title.','striking_admin'),get_option('siteurl')),
			"id" => "display_site_desc",
			"default" => true,
			"type" => "toggle"
		),
		array(
			"name" => __("Logo Bottom",'striking_admin'),
			"desc" => "",
			"id" => "logo_bottom",
			"min" => "-50",
			"max" => "80",
			"step" => "1",
			"unit" => 'px',
			"default" => "20",
			"type" => "range"
		),
		array(
			"name" => __("Header Widget Area",'striking_admin'),
			"desc" => "",
			"id" => "top_area_type",
			"default" => '',
			"options" => array(
				"html" => __('Html','striking_admin'),
				"wpml_flags" => __('Wpml Flags','striking_admin'),
				"widget" => __('Widget Area','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Header Widget Area Html Code",'striking_admin'),
			"desc" => '',
			"id" => "top_area_html",
			"default" => "",
			'rows' => '3',
			"type" => "textarea"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Navigation Menu",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Navigation Button Style",'striking_admin'),
			"desc" => __("If this option is on, you will see button effect when you hover the navigation items.",'striking_admin'),
			"id" => "nav_button",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Navigation Parent Arrow",'striking_admin'),
			"desc" => __("If this option is on, the naviagtion will display a arrow after the menu item that have children menu. (Only works on wp 3.2 + for Wordpress buit-in Menu and wp 3.3 + for Wordpress Page Menu.)",'striking_admin'),
			"id" => "nav_arrow",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Wordpress Built-in Menu",'striking_admin'),
			"desc" => sprintf(__("If this option is on, you can control over your sites menu with WordPress’s new Navigation Menus feature. See here: <a href=\"%s/wp-admin/nav-menus.php\">%s/wp-admin/nav-menus.php</a>",'striking_admin'),get_option('siteurl'),get_option('siteurl')),
			"id" => "enable_nav_menu",
			"default" => true,
			"type" => "toggle"
		),
		array(
			"name" => __("Exclude Pages From Menu",'striking_admin'),
			"desc" => __("If Wordpress Built-in Menu is off, we will show all pages except pages selected below in the menu.",'striking_admin'),
			"id" => "excluded_pages",
			"default" => array(),
			"page" => '0',
			"prompt" => __("Choose page..",'striking_admin'),
			"chosen" => "true",
			"type" => "multiselect",
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Page General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Boxed Layout",'striking_admin'),
			"id" => "enable_box_layout",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Layout",'striking_admin'),
			"desc" => "Choose the default page layout for the site.",
			"id" => "layout",
			"default" => 'right',
			"options" => array(
				"full" => __('Full Width','striking_admin'),
				"right" => __('Right Sidebar','striking_admin'),
				"left" => __('Left Sidebar','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Custom Favicon",'striking_admin'),
			"desc" => __("Paste the full URL (include <code>http://</code>) of your custom favicon here, or you can insert the icon through the button.",'striking_admin'),
			"id" => "custom_favicon",
			"default" => '',
			"button" => 'Insert Icon',
			"type" => 'upload',
			"imagewidth" => '16',
		),
		array(
			"name" => __("Feature Header",'striking_admin'),
			"desc" => __("If this option is off, you'll globally disable your website's Feature header.",'striking_admin'),
			"id" => "introduce",
			"default" => true,
			"type" => "toggle"
		),
		array(
			"name" => __("Disable Breadcrumbs",'striking_admin'),
			"desc" => __("If this option is on, you'll globally disable your website's breadcrumb navigation.",'striking_admin'),
			"id" => "disable_breadcrumb",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Enable image link lightbox",'striking_admin'),
			"desc" => __("If this option is on, you'll globally enable your website's image link with lightbox support.",'striking_admin'),
			"id" => "lightbox_rel_replace",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Scroll to Top ",'striking_admin'),
			"desc" => __("If this option is on, you'll see an scroll to top button at the bottom of the page.",'striking_admin'),
			"id" => "scroll_to_top",
			"default" => false,
			"type" => "toggle"
		),
	array(
		"type" => "end"
	),	
	array(
		"name" => __("Google Analytics Code",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Google Analytics Position",'striking_admin'),
			"id" => "analytics_position",
			"default" => 'bottom',
			"options" => array(
				"header" => __('Header','striking_admin'),
				"bottom" => __('Bottom','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Google Analytics Code",'striking_admin'),
			"desc" => __("Paste your <a href='http://www.google.com/analytics/' target='_blank'>analytics code</a> here, it will get applied to each page.",'striking_admin'),
			"id" => "analytics",
			"default" => "",
			"type" => "textarea"
		),
	array(
		"type" => "end"
	),

	array(
		"name" => __("Custom CSS & JS",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Custom CSS",'striking_admin'),
			"desc" => '',
			"id" => "custom_css",
			"default" => "",
			'rows' => '10',
			"type" => "textarea"
		),
		array(
			"name" => __("Custom JS",'striking_admin'),
			"desc" => sprintf(__('The code here will display on the footer of the page. Sample: <pre>&lt;script type=&quot;text/javascript&quot; src=&quot;%s/wp-content/themes/striking/js/yourscript.js&quot;&gt;&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot;&gt;alert("hello world");&lt;/script&gt;</pre>','striking_admin'),get_option('siteurl')),
			"id" => "custom_js",
			"default" => "",
			'rows' => '10',
			"type" => "textarea"
		),
	array(
		"type" => "end"
	),
	
);
return array(
	'auto' => true,
	'name' => 'general',
	'options' => $options
);
