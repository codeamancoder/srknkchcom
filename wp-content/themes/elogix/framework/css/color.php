<?php 
header("Content-type: text/css");

if(file_exists('../../../../wp-load.php')) :
	include '../../../../wp-load.php';
else:
	include '../../../../../wp-load.php';
endif; 

$color = of_get_option('primary_colorpicker');
$logo_margin = of_get_option('logo_margin');
$header_height = of_get_option('header_height');
$default_background = of_get_option('default_background');
$customcss = of_get_option('css_code');

?>

body{
	background: <?php echo $default_background['color'].' '; echo 'url('.$default_background['image'].') '; echo $default_background['repeat'].' '; echo $default_background['position'].' '; echo $default_background['attachment'];   ?>;
}

#logo{
	margin-top: <?php echo $logo_margin; ?> !important;
}

a:hover, .post-entry h2 a:hover{
	color:<?php echo $color; ?>;
}

#contactform #submit:hover{
	background-color:<?php echo $color; ?>;
}

::-moz-selection { background-color:<?php echo $color; ?>; }
.::selection { background-color:<?php echo $color; ?>; }

.color-hr{
	background: <?php echo $color; ?>;
}

#infobar{
	background: <?php echo $color; ?>;
}

#infobar .openbtn{
	background-color: <?php echo $color; ?>;
}

#infobar2{
	background-color: <?php echo $color; ?>;
}

#nav ul li a:hover{
	color:<?php echo $color; ?>;
	border-color:<?php echo $color; ?>;
}

#nav ul li.current-menu-item a, 
#nav ul li.current-page-ancestor a,
#nav ul li.current-menu-ancestor a{
	border-color:<?php echo $color; ?>;
	background-color: <?php echo $color; ?>;
	color:<?php echo $color; ?>;
}

#nav ul li.current-menu-item ul li a:hover, 
#nav ul li.current-page-ancestor ul li a:hover,
#nav ul li.current-menu-ancestor ul li a:hover{
	color:<?php echo $color; ?> !important;
}

#nav ul.sub-menu{
	border-color:<?php echo $color; ?>;
}

#latestposts .entry a.readmore{
	color:<?php echo $color; ?>;
}

#latestwork .entry:hover {
	border-color:<?php echo $color; ?>;
}
#latestwork .entry:hover h4 a{
	color:<?php echo $color; ?>;
}

#latestwork .entry:hover img{
	border-color:<?php echo $color; ?>;
}

a.work-carousel-prev:hover{
	background-color: <?php echo $color; ?>;
}

a.work-carousel-next:hover{
	background-color: <?php echo $color; ?>;
}

.post-thumb a:hover{
	border-color: <?php echo $color; ?>;
}

.big-post-thumb img{
	border-color: <?php echo $color; ?>;
}

.post-entry a.readmore{
	color:<?php echo $color; ?>;
}
.post-entry a.readmore:hover{
	background-color:<?php echo $color; ?>;
}

.meta a:hover{
	color:<?php echo $color; ?>;
}

.navigation a:hover{
	color:<?php echo $color; ?>;
}

a#cancel-comment-reply-link{
	color:<?php echo $color; ?>;
}

#commentform #submit:hover{
	background-color:<?php echo $color; ?>;
}
.posts-prev a:hover, .posts-next a:hover{
	background-color: <?php echo $color; ?>;
}

#filters li a:hover{
	color:<?php echo $color; ?>;
}

.work-item:hover {
	background-color: #ffffff;
	border-color:<?php echo $color; ?>;
}
.work-item:hover h3 a{
	color:<?php echo $color; ?>;
}

.work-item:hover img{
	border-color:<?php echo $color; ?>;
}

#sidebar .widget_nav_menu li.current-menu-item a{
	color:<?php echo $color; ?> !important;
}

#sidebar a:hover{
	color:<?php echo $color; ?>;
}

#breadcrumb a:hover{
	color:<?php echo $color; ?>;
}

#lasttweet{
	background-color:<?php echo $color; ?>;
}

.plan.featured{
	border-color:<?php echo $color; ?>;
}
.pricing-table .plan.featured:last-child{
	border-color:<?php echo $color; ?>;
}

.plan.featured h3{
	background-color: <?php echo $color; ?>;
}

.plan.featured .price{
	background-color: <?php echo $color; ?>;
}

.toggle .title:hover{
	color:<?php echo $color; ?>;
}
.toggle .title.active{
	color:<?php echo $color; ?>;
}

ul.tabNavigation li a.active{
    color:<?php echo $color; ?>;
    border-bottom:1px solid #ffffff;
    border-top:1px solid <?php echo $color; ?>;
}

ul.tabNavigation li a:hover {
	color:<?php echo $color; ?>;
}

.button{
  background-color: <?php echo $color; ?>;
}

#home-slider .flex-control-nav li a:hover {
	background: <?php echo $color; ?>;
}
#home-slider .flex-control-nav li a.active {
	background: <?php echo $color; ?>;
}

.accordion .title.active a{
	color:<?php echo $color; ?> !important;
}

#latestposts .entry a.readmore:hover{
	background-color:<?php echo $color; ?>;
}

.post-entry h2 a:hover, .search-result h2 a:hover, .work-detail-description a:hover{
	color: <?php echo $color; ?>;
}

<?php echo $customcss; ?>

@media only screen and (max-width: 767px) {
	#header{
		border-top:6px solid <?php echo $color; ?>;
	}
}
