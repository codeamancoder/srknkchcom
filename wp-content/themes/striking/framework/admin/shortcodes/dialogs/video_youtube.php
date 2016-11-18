<?php
return array(
	"title" => __("Youtube", "striking_admin"),
	"shortcode" => 'video',
	"attributes" => 'type="youtube"',
	"type" => 'self-closing',
	"options" => array(
		array(
			"name" => __("Clip_id",'striking_admin'),
			"desc" => __("the id from the clip's URL after v= (e.g. http://www.youtube.com/watch?v=<span style='color:red'>2DclLrdaxQd</span>)",'striking_admin'),
			"id" => "clip_id",
			"size" => 30,
			"default" => "",
			"type" => "text",
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
		array(
			"name" => __("Autohide",'striking_admin'),
			"desc" => __('Set whether the video controls will automatically hide after a video begins playing.','striking_admin'),
			"id" => "autohide",
			"default" => 'default',
			"options" => array(
				"default"  => __('Default','striking_admin'),
				"0" => __('Visible','striking_admin'),
				"1" => __('Hide all','striking_admin'),
				"2" => __('Hide video progress bar','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Autoplay",'striking_admin'),
			"desc" => __('Sets whether or not the initial video will autoplay when the player loads','striking_admin'),
			"id" => "autoplay",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Controls",'striking_admin'),
			"desc" => __('Sets whether or not display the video player controls.','striking_admin'),
			"id" => "controls",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Disablekb",'striking_admin'),
			"desc" => __('Enable it will disable the player keyboard controls.','striking_admin'),
			"id" => "disablekb",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Fs",'striking_admin'),
			"desc" => __('Sets whether or not enable the fullscreen button.','striking_admin'),
			"id" => "fs",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Hd",'striking_admin'),
			"desc" => __('Sets whether or not enable HD version of the video.','striking_admin'),
			"id" => "hd",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Loop",'striking_admin'),
			"desc" => __('Enable it will will cause the player to play the initial video again and again.','striking_admin'),
			"id" => "loop",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("Rel",'striking_admin'),
			"desc" => __('Sets whether the player should load related videos once playback of the initial video starts.','striking_admin'),
			"id" => "rel",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("showinfo",'striking_admin'),
			"desc" => __('Enable it will will cause the player to play the initial video again and again.','striking_admin'),
			"id" => "showinfo",
			"default" => 'default',
			"type" => "tritoggle"
		),
		array(
			"name" => __("showsearch",'striking_admin'),
			"desc" => __('Sets whether or not display search box from displaying when the video is minimized','striking_admin'),
			"id" => "showsearch",
			"default" => 'default',
			"type" => "tritoggle"
		),
	),
);
