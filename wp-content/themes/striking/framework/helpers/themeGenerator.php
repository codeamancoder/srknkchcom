<?php
class themeGenerator {
	function title(){
		global $page, $paged;
		
		/*
		wp_title('',true);
		return;
		*/
		$output =  wp_title( '|', false, 'right' );
		
		// Add the blog name.
		$output .=  get_bloginfo( 'name' );
		
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( !empty($site_description) && is_front_page() )
			$output .=  " | $site_description";
		
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$output .= ' | ' . sprintf( __( 'Page %s', 'striking_front' ), max( $paged, $page ) );
		
		return $output;
	}

	function wpml_flags(){
		$output = '';
		if(function_exists('icl_get_languages')){
			$languages = icl_get_languages('skip_missing=0');
			if(!empty($languages) && is_array($languages)){
				$output .= '<div id="language_flags"><ul>';
				foreach($languages as $l){
					$output .= '<li>';
					if(!$l['active']) $output .=  '<a href="'.$l['url'].'" title="'.$l['native_name'].'">';
					$output .=  '<img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'" />';
					if(!$l['active']) $output .=  '</a>';
					$output .=  '</li>';
				}
				$output .=  '</ul></div>';
			}
		}
		return $output;
	}

	function menu(){
		if (theme_get_option('general','enable_nav_menu') && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 
				'theme_location' => 'primary-menu',
				'container' => 'nav',
				'container_id' => 'navigation',
				'container_class' => 'jqueryslidemenu',
				'fallback_cb' => '',
				'walker' => new Theme_Walker_Nav_Menu
			));
		}else{
			$excluded_pages_with_childs = theme_get_excluded_pages();
			
			$active_class = (is_front_page()) ? 'class="current_page_item"' : '';
			
			$output = '<nav id="navigation" class="jqueryslidemenu">';
			$output .= '<ul id="menu-navigation" class="menu">';
			$output .= '<li ' .$active_class. '><a href="' .get_bloginfo('url'). '">'.__('Home','striking_front').'</a></li>';
			$output .= wp_list_pages("sort_column=menu_order&exclude=$excluded_pages_with_childs&title_li=&echo=0&depth=4");
			$output .= '</ul>';
			$output .= '</nav>';
			
			return $output;
		}
	}
	
	function sidebar(){
		sidebar_generator('get_sidebar',get_queried_object_id());
	}
	
	function footer_sidebar(){
		sidebar_generator('get_footer_sidebar');
	}
	
	function introduce($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = theme_get_option('blog','blog_page');
			$post_id = wpml_get_object_id($blog_page_id,'page');
		}
		if (is_single() || is_page() || (is_front_page() && $post_id != NULL) || (is_home() && $post_id != NULL)){
			$type = get_post_meta($post_id, '_introduce_text_type', true);
			
			if (empty($type))
				$type = 'default';
			
			if (!theme_get_option('general','introduce') && $type=='default'){
				return;
			}
			
			if ($type == 'disable') {
				return;
			}
			if (in_array($type, array('default', 'title', 'title_custom','title_slideshow'))) {
				$custom_title = get_post_meta($post_id, '_custom_title', true);
				if(!empty($custom_title)){
					$title = $custom_title;
				}else{
					$title = get_the_title($post_id);
				}
			}
			if (in_array($type, array('slideshow', 'custom_slideshow','title_slideshow'))) {
				$stype = get_post_meta($post_id, '_slideshow_type', true);
				$scategory = get_post_meta($post_id, '_slideshow_category', true);
				$color = get_post_meta($post_id, '_introduce_background_color', true);
				$number = get_post_meta($post_id, '_slideshow_number', true);

				if ($type == 'slideshow' ){
					return theme_generator('slideShow',$stype,$scategory,$color,$number);
				}else{
					if($type == 'custom_slideshow'){
						$text = str_replace(array('[raw]','[/raw]','</div> <div'),array('','','</div><div'),do_shortcode(get_post_meta($post_id, '_custom_introduce_text', true)));
					}elseif($type == 'title_slideshow'){
						$text = '<h1>'.$title.'</h1>';
					}
					
					return theme_generator('slideShow',$stype,$scategory,$color,$number,$text);
				}
			}
			
			$blog_page_id = theme_get_option('blog','blog_page');
			$blog_page_id = wpml_get_object_id($blog_page_id,'page');
			if ($type == 'default' && is_singular('post') && $post_id!=$blog_page_id) {
				$show_in_header = theme_get_option('blog','show_in_header');
				if($show_in_header){
					$title = get_the_title($post_id);
					$text = '<div class="entry_meta">';
					$text .= $this->blog_meta(true);
					$text .= '</div>';
					/*$outputs = array();
					if (theme_get_option('blog','single_meta_date')){
						$outputs[]='<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
					}
					if (theme_get_option('blog','single_meta_category')){
						$outputs[]= '<span class="categories">'.get_the_category_list(',').'</span>'; 
					}
					if (theme_get_option('blog','single_meta_tags')){
						$content =  '<span class="tags">'.get_the_tag_list('',',').'</span>'; 
						if(!empty($content)){
							$outputs[] = $content;
						}
					}
					$text.= implode('<span class="separater">|</span>',$outputs);
					ob_start();
						edit_post_link( __( 'Edit', 'striking_front' ), '<span class="separater">|</span> <span class="edit-link">', '</span>' );
						global $post;
						if(theme_get_option('blog','single_meta_comment') && ($post->comment_count > 0 || comments_open())):
							echo '<span class="comments">';
							comments_popup_link(__('No Comments','striking_front'), __('1 Comment','striking_front'), __('% Comments','striking_front'));
							echo '</span>';
						endif;
					$text .= ob_get_clean();
					*/
				}else{
					return $this->introduce($blog_page_id);
				}
			}
			
			if (in_array($type, array('custom', 'title_custom'))) {
				$text = str_replace(array('[raw]','[/raw]','</div> <div'),array('','','</div><div'),do_shortcode(get_post_meta($post_id, '_custom_introduce_text', true)));
			}
		}elseif(!theme_get_option('general','introduce')){
			return;
		}

		if (is_archive()){
			$title = __('Archives','striking_front');
			if(function_exists('is_post_type_archive')){
				if(is_post_type_archive()){
					$title = wpml_t(THEME_NAME, get_query_var( 'post_type' ) . ' Post Type Archive Title',theme_get_option('advance','archive_'.get_query_var( 'post_type' ).'_title'));
					if($title === false){
						$title = theme_get_option('advance','archive_'.get_query_var( 'post_type' ).'_title');
					}
					$text = wpml_t(THEME_NAME, get_query_var( 'post_type' ) . ' Post Type Archive Text',theme_get_option('advance','taxonomy_'.get_query_var( 'taxonomy' ).'_text'));
					if($text === false ){
						$text = theme_get_option('advance','archive_'.get_query_var( 'post_type' ).'_text');
					}
					$post_type = get_post_type_object( get_query_var( 'post_type' ) );
					$title = sprintf($title,$post_type->name);
					$text = sprintf($text,$post_type->name);
				}
			}
			if(is_category()){
				$title = wpml_t(THEME_NAME, 'Category Archive Title',theme_get_option('advance','category_title'));
				$text = wpml_t(THEME_NAME, 'Category Archive Text',theme_get_option('advance','category_text'));
				$title = sprintf($title,single_cat_title('',false));
				$text = sprintf($text,single_cat_title('',false));
			}elseif(is_tag()){
				$title = wpml_t(THEME_NAME, 'Tag Archive Title',theme_get_option('advance','tag_title'));
				$text = wpml_t(THEME_NAME, 'Tag Archive Text',theme_get_option('advance','tag_text'));
				$title = sprintf($title,single_tag_title('',false));
				$text = sprintf($text,single_tag_title('',false));
			}elseif(is_date() && is_numeric(get_query_var('w')) && 0 !== get_query_var('w') ){
				$title = wpml_t(THEME_NAME, 'Weekly Archive Title',theme_get_option('advance','weekly_title'));
				$text = wpml_t(THEME_NAME, 'Weekly Archive Text',theme_get_option('advance','weekly_text'));
				$title = sprintf($title,get_the_time('W'));
				$text = sprintf($text,get_the_time('W'));
			}elseif(is_day()){
				$title = wpml_t(THEME_NAME, 'Daily Archive Title',theme_get_option('advance','daily_title'));
				$text = wpml_t(THEME_NAME, 'Daily Archive Text',theme_get_option('advance','daily_text'));
				$title = sprintf($title,get_the_time('F jS, Y'));
				$text = sprintf($text,get_the_time('F jS, Y'));
			}elseif(is_month()){
				$title = wpml_t(THEME_NAME, 'Monthly Archive Title',theme_get_option('advance','monthly_title'));
				$text = wpml_t(THEME_NAME, 'Monthly Archive Text',theme_get_option('advance','monthly_text'));
				$title = sprintf($title,get_the_time('F, Y'));
				$text = sprintf($text,get_the_time('F, Y'));
			}elseif(is_year()){
				$title = wpml_t(THEME_NAME, 'Yearly Archive Title',theme_get_option('advance','yearly_title'));
				$text = wpml_t(THEME_NAME, 'Yearly Archive Text',theme_get_option('advance','yearly_text'));
				$title = sprintf($title,get_the_time('Y'));
				$text = sprintf($text,get_the_time('Y'));
			}elseif(is_author()){
				$title = wpml_t(THEME_NAME, 'Author Archive Title',theme_get_option('advance','author_title'));
				$text = wpml_t(THEME_NAME, 'Author Archive Text',theme_get_option('advance','author_text'));

				if(get_query_var('author_name')){
					$curauth = get_user_by('slug', get_query_var('author_name'));
				} else {
					$curauth = get_userdata(get_query_var('author'));
				}
				$title = sprintf($title,$curauth->nickname);
				$text = sprintf($text,$curauth->nickname);
			}elseif(isset($_GET['paged']) && !empty($_GET['paged'])) {
				$title = wpml_t(THEME_NAME, 'Blog Archive Title',theme_get_option('advance','blog_title'));
				$text = wpml_t(THEME_NAME, 'Blog Archive Text',theme_get_option('advance','blog_text'));
			}elseif(is_tax()){
				$title = wpml_t(THEME_NAME, get_query_var( 'taxonomy' ) . ' Taxonomy Archive Title',theme_get_option('advance','taxonomy_'.get_query_var( 'taxonomy' ).'_title'));
				if($title === false){
					$title = theme_get_option('advance','taxonomy_'.get_query_var( 'taxonomy' ).'_title');
				}
				if($title === false){
					$title = wpml_t(THEME_NAME, 'Taxonomy Archive Title',theme_get_option('advance','taxonomy_title'));
				}
				$text = wpml_t(THEME_NAME, get_query_var( 'taxonomy' ) . ' Taxonomy Archive Text',theme_get_option('advance','taxonomy_'.get_query_var( 'taxonomy' ).'_text'));
				if($text === false ){
					$text = theme_get_option('advance','taxonomy_'.get_query_var( 'taxonomy' ).'_text');
				}
				if($text === false ){
					$text = wpml_t(THEME_NAME, 'Taxonomy Archive Text',theme_get_option('advance','taxonomy_text'));
				}
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$title = sprintf($title,$term->name);
				$text = sprintf($text,$term->name);
			}
			$title = stripslashes($title);
			$text = stripslashes($text);
		}
		
		if (is_404()) {
			$title = wpml_t(THEME_NAME, '404 Page Title',theme_get_option('advance','404_title'));
			$text = wpml_t(THEME_NAME, '404 Page Text',theme_get_option('advance','404_text'));
			$title = stripslashes($title);
			$text = stripslashes($text);
		}
		
		if (is_search()) {
			$title = wpml_t(THEME_NAME, 'Search Page Title',theme_get_option('advance','search_title'));
			$text = wpml_t(THEME_NAME, 'Search Page Text',theme_get_option('advance','search_text'));
			$text = sprintf($text,stripslashes( strip_tags( get_search_query() ) ));
			$title = stripslashes($title);
			$text = stripslashes($text);
		}
		
		
		
		$color = get_post_meta($post_id, '_introduce_background_color', true);
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		$output = '';
		$output .= '<div id="feature"'.$color.'>';
		$output .= '<div class="top_shadow"></div>';
		$output .= '<div class="inner">';
		if (isset($title)) {
			$output .= '<h1>' . $title . '</h1>';
		}
		if (isset($text)) {
			$output .= '<div id="introduce">';
			$output .= $text;
			$output .= '</div>';
		}
		$output .= '</div>';
		$output .= '<div class="bottom_shadow"></div>';
		$output .= '</div>';

		echo $output;
	}
	
	function breadcrumbs($post_id = NULL) {
		$output = '';
		if( (!$post_id && !theme_get_option('general','disable_breadcrumb')) ||
			($post_id && !theme_is_enabled(get_post_meta($post_id, '_disable_breadcrumb', true), theme_get_option('general','disable_breadcrumb')))
		){
			$output = breadcrumbs_plus(array(
				'prefix' => '<section id="breadcrumbs">',
				'suffix' => '</section>',
				'title' => false,
				'home' => __( 'Home', 'striking_front' ),
				'sep' => '&raquo;',
				'front_page' => false,
				'bold' => false,
				'blog' => __( 'Blog', 'striking_front' ),
				'echo' => false
			));
		}
		echo $output;
	}
	
	function portfolio_featured_image($layout='',$effect= ''){
		if (!has_post_thumbnail()){
			return;
		}
		if($layout == 'full'){
			$width = 958;
		}else{
			$width = 628;
		}
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		$adaptive_height = theme_get_option('portfolio', 'adaptive_height');
		
		if($adaptive_height){
			$height = floor($width*($image_src_array[2]/$image_src_array[1]));
		}else{
			$height = theme_get_option('portfolio', 'fixed_height');
		}
		$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>get_post_thumbnail_id()), array($width, $height));
		$output = '';
		if(empty($effect)){
			$effect = theme_get_option('blog','effect');
		}
		$output .= '<div class="image_styled entry_image">';
		$output .= '<span class="image_frame effect-'.$effect.'" style="height:'.$height.'px;width:'.$width.'px">';
		if(is_single()){
			if(theme_get_option('portfolio', 'featured_image_lightbox')){
				if(theme_get_option('portfolio', 'featured_image_lightbox_gallery')){
					$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" rel="post-'.get_queried_object_id().'" title="'.get_the_title().'">';
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
					$output .= '</a>';

					$children = array(
						'post_parent' => get_queried_object_id(),
						'post_status' => 'inherit',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'ASC',
						'orderby' => 'menu_order ID',
						'numberposts' => -1,
						'offset' => ''
					);

					/* Get image attachments. If none, return. */
					$attachments = get_children( $children );
					if(!empty($attachments)){
						$output .= '<div class="hidden">';
						$post_thumbnail_id = get_post_thumbnail_id();
						foreach ( $attachments as $id => $attachment ) {
							$img_src = wp_get_attachment_image_src($id, 'full');
							if($id != $post_thumbnail_id){
							//$title = wptexturize( esc_html($attachment->post_excerpt) );
								$output .= '<a class="lightbox" href="'.$img_src[0].'" title="'.get_the_title().'" rel="post-'.get_queried_object_id().'">'.$id.'</a>';
							}
						}
						$output .= '</div>';
					}
				}else{
					$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" title="'.get_the_title().'">';
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
					$output .= '</a>';
				}
			} else {
				if($effect!='none'){
					$output .= '<a class="image_icon_doc" href="#" title=""><img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" /></a>';
				}else{
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
				}
			}
		} else {
			$output .= '<a class="image_icon_doc" href="'.get_permalink().'" title="">';
			$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
			$output .= '</a>';
		}
		$output .= '</span>';
		$output .= '<img src="'.THEME_IMAGES.'/image_shadow.png" class="image_shadow" width="'.($width+2).'" alt="" style="width:'.($width+2).'px">';
		$output .= '</div>';

		return $output;
	}

	function blog_featured_image($type='full',$layout='',$height='',$frame = false,$effect= ''){
		if (!has_post_thumbnail()){
			return '';
		}
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		if($layout == 'full'){
			$width = 958;
		}elseif(is_numeric($layout)){
			$width = $layout-2;
		}else{
			$width = 628;
		}
		if(empty($effect)){
			$effect = theme_get_option('blog','effect');
		}
		if($frame && isset($width)){
			$width = $width - 32;
		}
		if($type=='left' || $type=='right'){
			if(is_numeric($layout)){
				$width = $layout-2;
			}else{
				$width = theme_get_option('blog', 'left_width');
			}
			if($height == ''){
				$height = theme_get_option('blog', 'left_height');
			}
		}else{
			if(empty($height)){
				$adaptive_height = theme_get_option('blog', 'adaptive_height');
				if($adaptive_height && !empty($image_src_array[1])){
					$height = floor($width*($image_src_array[2]/$image_src_array[1]));
				}else{
					$height = theme_get_option('blog', 'fixed_height');
				}
			}
		}
		$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>get_post_thumbnail_id()), array($width, $height));
		$output = '';
		$output .= '<div class="image_styled entry_image"'.(($type=='left' || $type=='right')?' style="width:'.$width.'px"':'').'>';
		$output .= '<span class="image_frame effect-'.$effect.'" style="height:'.$height.'px;width:'.$width.'px">';
		if(is_single()){
			if(theme_get_option('blog', 'featured_image_lightbox')){
				if(theme_get_option('blog', 'featured_image_lightbox_gallery')){
					$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" rel="post-'.get_queried_object_id().'" title="'.get_the_title().'">';
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
					$output .= '</a>';

					$children = array(
						'post_parent' => get_queried_object_id(),
						'post_status' => 'inherit',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'ASC',
						'orderby' => 'menu_order ID',
						'numberposts' => -1,
						'offset' => ''
					);

					/* Get image attachments. If none, return. */
					$attachments = get_children( $children );
					if(!empty($attachments)){
						$output .= '<div class="hidden">';
						$post_thumbnail_id = get_post_thumbnail_id();
						foreach ( $attachments as $id => $attachment ) {
							$img_src = wp_get_attachment_image_src($id, 'full');
							if($id != $post_thumbnail_id){
							//$title = wptexturize( esc_html($attachment->post_excerpt) );
								$output .= '<a class="lightbox" href="'.$img_src[0].'" title="'.get_the_title().'" rel="post-'.get_queried_object_id().'">'.$id.'</a>';
							}
						}
						$output .= '</div>';
					}
				}else{
					$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" title="'.get_the_title().'">';
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
					$output .= '</a>';
				}
			} else {
				if($effect!='none'){
					$output .= '<a class="image_icon_zoom" href="#" title="'.get_the_title().'"><img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" /></a>';
				}else{
					$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
				}
			}
		} else {
			if(theme_get_option('blog', 'index_featured_image_lightbox')){
				$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" title="'.get_the_title().'">';
				$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
				$output .= '</a>';
			} else {
				$output .= '<a class="image_icon_doc" href="'.get_permalink().'" title="">';
				$output .= '<img width="'.$width.'" height="'.$height.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
				$output .= '</a>';
			}
			
		}
		$output .= '</span>';
		$output .= '<img src="'.THEME_IMAGES.'/image_shadow.png" class="image_shadow" width="'.($width+2).'" alt="" style="width:'.($width+2).'px">';
		$output .= '</div>';

		return $output;
	}
	
	function blog_meta($single = false) {
 		global $post;
		if(get_post_type(get_the_ID())=='page'){
			return '';
		}
		$output = '';
		if($single){
			$meta_items = theme_get_option('blog','single_meta_items');
		}else{
			$meta_items = theme_get_option('blog','meta_items');
		}
		
		if(!empty($meta_items)){
			$outputs = array();
			foreach($meta_items as $item){
				switch($item){
					case 'category':
						$content = get_the_category_list(', ');
						if(!empty($content)){
							$outputs[] = '<span class="categories">'.__('Posted in: ', 'striking_front').$content.'</span>';
						}
						break;
					case 'tags':
						$content = get_the_tag_list(__('Tags: ', 'striking_front'),', ','');
						if(!empty($content)){
							$outputs[] = '<span class="tags">'.$content.'</span>';
						}
						break;
					case 'author':
						global $authordata;
						if(!$authordata){
							$authordata = get_userdata($post->post_author);
						}
						if(theme_get_option('blog','author_link_to_website')){
							$outputs[] = '<span class="author">'.__('By: ', 'striking_front').  get_the_author_link().'</span>';
						}else{
							$outputs[] = '<span class="author">'.__('By: ', 'striking_front').  get_the_author_posts_link().'</span>';
						}
						
						break;
					case 'date':
						$outputs[] = '<time datetime="'.get_the_time('Y-m-d').'"><a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_date().'</a></time>';
						break;
					/*
					case 'comment':
						if(($post->comment_count > 0 || comments_open())){
							ob_start();
							comments_popup_link(__('No Comments','striking_front'), __('1 Comment','striking_front'), __('% Comments','striking_front'),'');
							$outputs[] = '<span class="comments">'.ob_get_clean().'</span>';
						}
						break;
					*/
				}
			}
			$output = implode('<span class="separater">|</span>',$outputs);
			$output .= get_edit_post_link( __( 'Edit', 'striking_front' ), '<span class="separater">|</span> <span class="edit-link">', '</span>' );
			if(in_array('comment',$meta_items) && ($post->comment_count > 0 || comments_open())){
				ob_start();
				comments_popup_link(__('No Comments','striking_front'), __('1 Comment','striking_front'), __('% Comments','striking_front'),'');
				$output .= '<span class="comments">'.ob_get_clean().'</span>';
			}
		}		
		
		return $output;
	}

	function blog_author_info()	{
		if(theme_get_option('blog','author_link_to_website')){
			$author = get_the_author_link();
		}else{
			$author = get_the_author_posts_link();
		}
		$output = '<section id="about_the_author">'.
	'<h3>'.__('About the author','striking_front').'</h3>'.
	'<div class="author_content">'.
		'<div class="gravatar">'.get_avatar( get_the_author_meta('user_email'), '60' ).'</div>'.
		'<div class="author_info">'.
			'<div class="author_name">'.$author.'</div>'.
			'<p class="author_desc">'.get_the_author_meta('description').'</p>'.
		'</div>'.
		'<div class="clearboth"></div>'.
	'</div>'.
'</section>';
		return $output;
	}

	function blog_popular_posts() {		
		$r = new WP_Query(array(
			'category__not_in' => theme_get_option('blog','exclude_categorys'),
			'showposts' => 3, 
			'nopaging' => 0, 
			'orderby'=> 'comment_count', 
			'post_status' => 'publish', 
			'ignore_sticky_posts' => 1
		));
		$output = '';
		if ($r->have_posts()){
			$output .= '<h3>'.__('Popular Posts','striking_front').'</h3>';
			$output .= '<section class="popular_posts_wrap">';
			$output .= '<ul class="posts_list">';
			while ($r->have_posts()){
				$r->the_post();
				$output .= '<li>';
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if( has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}elseif(theme_get_option('blog','display_default_thumbnail')){
					if($default_thumbnail_custom = theme_get_option('blog','default_thumbnail_custom')){
						$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
					}else{
						$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
					}
					$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
				$output .= '<div class="post_extra_info">';
				$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
				$output .= '</div>';
				$output .= '<div class="clearboth"></div>';
				$output .= '</li>';
			}
			$output .= '</ul>';
			$output .= '</section>';
		}

		wp_reset_postdata();
		return $output;
	}

	function blog_related_posts() {
		global $post;
		$backup = $post;  
		$exclude_cats = theme_get_option('blog','exclude_categorys');
		$related_base_on = theme_get_option('blog','related_base_on');


		$related_post_found = false;
		$output = '';

		if($related_base_on == 'tags'){
			$tags = wp_get_post_tags($post->ID);
			$tagIDs = array();

			if ($tags) {
				$tagcount = count($tags);
				for ($i = 0; $i < $tagcount; $i++) {
					$tagIDs[$i] = $tags[$i]->term_id;
				}
				$r = new WP_Query(array(
					'category__not_in' => $exclude_cats,
					'tag__in' => $tagIDs,
					'post__not_in' => array($post->ID),
					'showposts'=>3,
					'ignore_sticky_posts'=>1
				));
			}
		}elseif($related_base_on == 'categories'){
			$categories = wp_get_post_categories($post->ID);
			$categoryIDs = array();

			if ($categories) {
				$categorycount = count($categories);
				for ($i = 0; $i < $categorycount; $i++) {
					$categoryIDs[$i] = $categories[$i];
				}
				$r = new WP_Query(array(
					'category__not_in' => $exclude_cats,
					'category__in' => $categoryIDs,
					'post__not_in' => array($post->ID),
					'showposts'=>3,
					'ignore_sticky_posts'=>1
				));
			}
		}
		if (isset($r) && $r->have_posts()){
			$related_post_found = true;
			$output .= '<h3>'.__('Related Posts','striking_front').'</h3>';
			$output .= '<section class="related_posts_wrap">';
			$output .= '<ul class="posts_list">';
			while ($r->have_posts()){
				$r->the_post();
				$output .= '<li>';
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if( has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}elseif(theme_get_option('blog','display_default_thumbnail')){
					if($default_thumbnail_custom = theme_get_option('blog','default_thumbnail_custom')){
						$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
					}else{
						$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
					}
					$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
				$output .= '<div class="post_extra_info">';
				$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
				$output .= '</div>';
				$output .= '<div class="clearboth"></div>';
				$output .= '</li>';
			}
			$output .= '</ul>';
			$output .= '</section>';
		}

		
		if(!$related_post_found){
			$r = new WP_Query(array(
				'category__not_in' => $exclude_cats,
				'showposts' => 3, 
				'nopaging' => 0, 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => 1
			));
			if ($r->have_posts()){
				$output .= '<h3>'.__('Recent Posts','striking_front').'</h3>';
				$output .= '<section class="recent_posts_wrap">';
				$output .= '<ul class="posts_list">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
					if( has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}elseif(theme_get_option('blog','display_default_thumbnail')){
						if($default_thumbnail_custom = theme_get_option('blog','default_thumbnail_custom')){
							$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
						}else{
							$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
						}
						$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '</a>';
					$output .= '<div class="post_extra_info">';
					$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
					$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
					$output .= '</div>';
					$output .= '<div class="clearboth"></div>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</section>';
			}
		}
		$post = $backup;

		wp_reset_postdata();

		return $output;
	}

	function portfolio_recent_posts() {
		$r = new WP_Query(array(
			'showposts' => 3, 
			'nopaging' => 0, 
			'post_status' => 'publish', 
			'ignore_sticky_posts' => 1,
			'post_type' => 'portfolio', 
		));
		$output = '';
		if ($r->have_posts()){
			$output .= '<h3>'.__('Recent Portfolio','striking_front').'</h3>';
			$output .= '<section class="recent_portfolio_wrap">';
			$output .= '<ul class="posts_list">';
			while ($r->have_posts()){
				$r->the_post();
				$output .= '<li>';
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if( has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}elseif(theme_get_option('portfolio','display_default_thumbnail')){
					if($default_thumbnail_custom = theme_get_option('portfolio','default_thumbnail_custom')){
						$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
					}else{
						$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
					}
					$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
				$output .= '<div class="post_extra_info">';
				$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
				$output .= '</div>';
				$output .= '<div class="clearboth"></div>';
				$output .= '</li>';
			}
			$output .= '</ul>';
			$output .= '</section>';
		}

		wp_reset_postdata();
		return $output;
	}

	function portfolio_related_posts() {
		global $post;
		$backup = $post;  
		$cats  =  wp_get_object_terms($post->ID, 'portfolio_category');
		$catSlugs = array();
		$related_post_found = false;
		$output = '';
		if ($cats) {
			$catcount = count($cats);
			for ($i = 0; $i < $catcount; $i++) {
				$catSlugs[$i] = $cats[$i]->slug;
			}
			$query = array(
				'post__not_in' => array($post->ID),
				'showposts'=>3,
				'ignore_sticky_posts'=>1,
				'post_type' => 'portfolio', 
			);
			global $wp_version;
			if(version_compare($wp_version, "3.1", '>=')){
				$query['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio_category',
						'field' => 'slug',
						'terms' => $catSlugs
					)
				);
			}else{
				$query['taxonomy'] = 'portfolio_category';
				$query['term'] = implode(',',$catSlugs);
			}
			$r = new WP_Query($query);
			if ($r->have_posts()){
				$related_post_found = true;
				$output .= '<h3>'.__('Related Portfolio','striking_front').'</h3>';
				$output .= '<section class="related_portfolio_wrap">';
				$output .= '<ul class="posts_list">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
					if( has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}elseif(theme_get_option('portfolio','display_default_thumbnail')){
						if($default_thumbnail_custom = theme_get_option('portfolio','default_thumbnail_custom')){
							$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
						}else{
							$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
						}
						$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '</a>';
					$output .= '<div class="post_extra_info">';
					$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
					$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
					$output .= '</div>';
					$output .= '<div class="clearboth"></div>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</section>';
			}
		}
		if(!$related_post_found){
			$r = new WP_Query(array(
				'showposts' => 3, 
				'nopaging' => 0, 
				'orderby'=> 'comment_count', 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => 1,
				'post_type' => 'portfolio', 
			));
			if ($r->have_posts()){
				$output .= '<h3>'.__('Popular Portfolio','striking_front').'</h3>';
				$output .= '<section class="popular_portfolio_wrap">';
				$output .= '<ul class="posts_list">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
					if( has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}elseif(theme_get_option('portfolio','display_default_thumbnail')){
						if($default_thumbnail_custom = theme_get_option('blog','default_thumbnail_custom')){
							$default_thumbnail_image = theme_get_image_src($default_thumbnail_custom);
						}else{
							$default_thumbnail_image = THEME_IMAGES.'/widget_posts_thumbnail.png';
						}
						$output .= '<img src="'.$default_thumbnail_image.'" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '</a>';
					$output .= '<div class="post_extra_info">';
					$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
					$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
					$output .= '</div>';
					$output .= '<div class="clearboth"></div>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</section>';
			}
		}
		$post = $backup;
		wp_reset_postdata();

		return $output;
	}
	function slideShow($type, $category = '', $color = '',$number ='-1',$text = false) {
		/** fix **/
		if(empty($category)){
			$category = '{s}'; 
		}elseif(strpos($category, '|') != false){
			list($target, $cat) = explode("|", $category);
			$category = '{'.$target.':'.$cat.'}';
		}
		/** end fix **/
		if($type == "3d"){
			require_once (THEME_PLUGINS . '/Browser.php');
			$browser = new Browser();
			if($browser->isMobile()){
				$type = theme_get_option('slideshow','3d_mobile');
			}
		}
		if(empty($number)){
			$number = '-1';
		}
		$output = '';
		switch($type){
			case 'nivo':
				$output = $this->slideShow_nivo($category,$color,$number,$text);
				break;
			case '3d':
				$output = $this->slideShow_3d($category,$color,$number,$text);
				break;
			case 'kwicks':
				$output = $this->slideShow_kwicks($category,$color,$number,$text);
				break;
			case 'anything':
				$output = $this->slideShow_anything($category,$color,$number,$text);
				break;
		}
		echo $output;
	}

	function slideShowHeader() {
		$type = false;

		if( is_front_page() || (is_home() && !get_option('page_on_front') && get_queried_object_id()== 0 )){
			$page= theme_get_option('homepage','home_page');
			if($page){
				if (in_array( get_post_meta($page, '_introduce_text_type', true), array('slideshow', 'custom_slideshow','title_slideshow'))) {
					$type = get_post_meta($page,'_slideshow_type', true);
				}
			}else{
				if (theme_get_option('homepage', 'disable_slideshow')) {
					return;
				}
				$type = theme_get_option('homepage', 'slideshow_type');
			}
		}elseif( is_single() || is_page() || (is_home() && get_queried_object_id() == get_option('page_for_posts'))){
			$post_id = get_queried_object_id();
			
			$introduce_type = get_post_meta($post_id, '_introduce_text_type', true);
			if (in_array( $introduce_type, array('slideshow', 'custom_slideshow','title_slideshow'))) {
				$type = get_post_meta($post_id,'_slideshow_type', true);
			}
			$blog_page_id = theme_get_option('blog','blog_page');
			if('default' == $introduce_type && $post_id!=$blog_page_id){
				$show_in_header = theme_get_option('blog','show_in_header');
				if(!$show_in_header){
					$introduce_type = get_post_meta($blog_page_id, '_introduce_text_type', true);
					if('slideshow' == $introduce_type){
						$type = get_post_meta($blog_page_id,'_slideshow_type', true);
					}
				}
			}
		}elseif( is_home() && get_queried_object_id()== 0 && defined('ICL_SITEPRESS_VERSION')){ //wpml other language's homepage
			$home_page_id = theme_get_option('homepage','home_page');
			$home_page_id = wpml_get_object_id($home_page_id,'page');
			
			$introduce_type = get_post_meta($home_page_id, '_introduce_text_type', true);
			if (in_array( $introduce_type, array('slideshow', 'custom_slideshow','title_slideshow'))) {
				$type = get_post_meta($home_page_id,'_slideshow_type', true);
			}
		}
		if($type == "3d"){
			require_once (THEME_PLUGINS . '/Browser.php');
			$browser = new Browser();
			if($browser->isMobile()){
				$type = theme_get_option('slideshow','3d_mobile');
			}
		}
		
		switch($type){
			case 'nivo':
				$this->slideShowHeader_nivo();
				break;
			case '3d':
				$this->slideShowHeader_3d();
				break;
			case 'kwicks':
				$this->slideShowHeader_kwicks();
				break;
			case 'anything':
				$this->slideShowHeader_anything();
				break;
		}
	}
	function slideShowHeader_nivo() {
		$move_bottom = theme_get_option('advance','move_bottom');
		wp_enqueue_script('jquery-nivo');
		wp_enqueue_script('nivo-init', THEME_JS . '/nivoSliderInit.js',array('jquery'),false,$move_bottom);
	}
	function slideShowHeader_3d() {
		
	}
	function slideShowHeader_kwicks() {
		$move_bottom = theme_get_option('advance','move_bottom');
		wp_enqueue_script('jquery-easing');
		wp_enqueue_script('jquery-kwicks');
		wp_enqueue_script('kwicks-init', THEME_JS . '/kwicksSliderInit.js',array('jquery'),false,$move_bottom);
	}
	function slideShowHeader_anything() {
		$move_bottom = theme_get_option('advance','move_bottom');
		wp_enqueue_script('jquery-easing');
		wp_enqueue_script('jquery-anything');
		wp_enqueue_script('jquery-anything-video');
		wp_enqueue_script('anything-init', THEME_JS . '/anythingSliderInit.js',array('jquery'),false,$move_bottom);
	}
	
	function slideShow_getImages($source_string='',$number='-1',$size=array(960,440)){
		$pattern = '/{([sbpg]):{0,1}([^}]+?){0,1}}/i';
		preg_match_all($pattern, $source_string, $match);
		$sources = array();
		if(empty($match[0])){
			$source_value = $source_string;
		}else{
			foreach($match[1] as $index => $cat){
				$sources[$cat] = $match[2][$index];
			}
		}
		$images = array();
		foreach($sources as $key=>$source_value){
			switch($key){
				case 'b':
					$query = array( 
						'post_type' => 'post', 
						'showposts'=>$number, 
						'orderby'=>'date', 
						'order'=>'DESC',
						'meta_key'=>'_thumbnail_id',
					);
					if(!empty($source_value)){
						$query['category_name'] = $source_value;
					}

					$loop = new WP_Query($query);
					
					while ( $loop->have_posts() ) : $loop->the_post();
						$images[] = array(
							'source' => array(
								'type'=>'attachment_id',
								'value'=>get_post_thumbnail_id()
							),
							'type' => 'blog',
							'post_id'=> get_the_ID(),
							'title' => get_the_title(),
							'desc'  => get_the_excerpt(),
							'link' => get_permalink(),
							'target' => '_self'
						);
					endwhile;
					break;
				case 'p':
					$query = array( 
						'post_type' => 'portfolio', 
						'showposts'=>$number, 
						'orderby'=>'menu_order', 
						'order'=>'ASC',
					);
					if(!empty($source_value)){
						global $wp_version;
						if(version_compare($wp_version, "3.1", '>=')){
							$query['tax_query'] = array(
								array(
									'taxonomy' => 'portfolio_category',
									'field' => 'slug',
									'terms' => explode(',', $source_value)
								)
							);
						}else{
							$query['taxonomy'] = 'portfolio_category';
							$query['term'] = $source_value;
						}
					}
					
					$loop = new WP_Query($query);
					
					while ( $loop->have_posts() ) : $loop->the_post();
						$images[] = array(
							'source' => array(
								'type'=>'attachment_id',
								'value'=>get_post_thumbnail_id()
							),
							'type' => 'portfolio',
							'post_id'=> get_the_ID(),
							'title' => get_the_title(),
							'desc'  => get_the_excerpt(),
							'link' => get_permalink(),
							'target' => '_self'
						);
					endwhile;
					break;
				case 's':
					$query = array( 
						'post_type' => 'slideshow', 
						'showposts'=>$number, 
						'orderby'=>'menu_order', 
						'order'=>'ASC',
					);
					if(!empty($source_value)){
						global $wp_version;
						if(version_compare($wp_version, "3.1", '>=')){
							$query['tax_query'] = array(
								array(
									'taxonomy' => 'slideshow_category',
									'field' => 'slug',
									'terms' => explode(',', $source_value)
								)
							);
						}else{
							$query['taxonomy'] = 'slideshow_category';
							$query['term'] = $source_value;
						}
					}
					
					$loop = new WP_Query($query);
					
					while ( $loop->have_posts() ) : $loop->the_post();
						$link_to = get_post_meta(get_the_ID(), '_link_to', true);
						$link = theme_get_superlink($link_to);			
						$link_target = get_post_meta(get_the_ID(), '_link_target', true);

						$link_target = $link_target?$link_target:'_self';
						$images[] = array(
							'source' => array(
								'type'=>'attachment_id',
								'value'=>get_post_thumbnail_id()
							),
							'type' => 'slideshow',
							'post_id'=> get_the_ID(),
							'title' => get_the_title(),
							'desc'  => get_post_meta(get_the_ID(), '_description', true),
							'link' => $link,
							'target' => $link_target
						);
					endwhile;
					break;
				case 'g':
					if($source_value==''){
						$post_id =  get_queried_object_id();
					}else{
						$post_id = $source_value;
					}
					
					$children = array(
						'post_parent' => $post_id,
						'post_status' => 'inherit',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'ASC',
						'orderby' => 'menu_order ID',
						'numberposts' => -1,
						'offset' => ''
					);

					/* Get image attachments. If none, return. */
					$attachments = get_children( $children );
					foreach ( $attachments as $id => $attachment ) {
						$images[] = array(
							'source' => array(
								'type'=>'attachment_id',
								'value'=>$id
							),
							'type' => 'gallery',
							'post_id'=> $post_id,
							'title' => wptexturize( esc_html($attachment->post_excerpt) ),
							'desc'  => '',
							'src' => $img_src[0],
							'link' => '',
							'target' => '_self'
						);
					}
					break;
			}
		}
		wp_reset_query();
		if($number!='-1'){
			return array_slice($images, 0, $number);
		} else {
			return $images;
		}
	}
	
	function slideShow_nivo($category='',$color='',$number='-1',$text = false) {
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		$height = theme_get_option('slideshow','nivo_height');
		if($text){
			$text = '<div id="introduce" class="slider_top">'.$text.'</div>';
		}else{
			$text = '';
		}
		$output = <<<HTML
<div id="feature" class="nivo"{$color}>
	<div class="top_shadow"></div>
	<div class="inner">{$text}
		<div id="nivo_slider_wrap">
			<div id="nivo_slider">
HTML;

		$images = $this->slideShow_getImages($category,$number,'full');
		
		$captions = theme_get_option('slideshow', 'nivo_captions');
		
		foreach($images as $image) {
			$title = $captions?$image['title']:'';
			$image_src = theme_get_image_src($image['source'], array(960, $height));
			if($image['link'] != false){
				$output .= '<a href="'.$image['link'].'" target="'.$image['target'].'"><img src="' . $image_src . '" width="960" height="'.$height.'" title="'.$title.'" alt="'.$image['title'].'" /></a>';
			}else{
				$output .= '<img src="' . $image_src. '" width="960" height="'.$height.'" title="'.$title.'" alt="'.$image['title'].'" />';
			}
			
		}
		$output .= <<<HTML

			</div>
		</div>
	</div>
	<div class="bottom_shadow"></div>
</div>
HTML;

		$options = array(
			'effect' => theme_get_option('slideshow', 'nivo_effect'), 
			'slices' => theme_get_option('slideshow', 'nivo_slices'), 
			'boxCols' => theme_get_option('slideshow', 'nivo_boxCols'), 
			'boxRows' => theme_get_option('slideshow', 'nivo_boxRows'), 
			'animSpeed' => theme_get_option('slideshow', 'nivo_animSpeed'), 
			'pauseTime' => theme_get_option('slideshow', 'nivo_pauseTime'), 
			'directionNav' => theme_get_option('slideshow', 'nivo_directionNav'), 
			'directionNavHide' => theme_get_option('slideshow', 'nivo_directionNavHide'), 
			'controlNav' => theme_get_option('slideshow', 'nivo_controlNav'), 
			'keyboardNav' => theme_get_option('slideshow', 'nivo_keyboardNav'), 
			'pauseOnHover' => theme_get_option('slideshow', 'nivo_pauseOnHover'), 
			'manualAdvance' => theme_get_option('slideshow', 'nivo_manualAdvance'),
			'randomStart' => theme_get_option('slideshow', 'nivo_randomStart'),
			'captions' => theme_get_option('slideshow', 'nivo_captions'),
			'captionOpacity' => theme_get_option('slideshow', 'nivo_captionOpacity'),
			'stopAtEnd' => theme_get_option('slideshow', 'nivo_stopAtEnd'),
		);
		
		$output .= "\n<script type=\"text/javascript\">\n";
		$output .= "var slideShow = []; \n";
		foreach($options as $key => $value) {
			if (is_bool($value)) {
				$value = $value ? "true" : "false";
			} elseif(is_numeric($value)){
				$value = $value;
			} elseif($value!="true"&&$value!="false") {
				$value = "'" . $value . "'";
			}
			$output .= "slideShow['" . $key . "'] = " . $value . "; \n";
		}
		$output .= "</script>\n";
		return $output;
	}
	function slideShow_3d($category='',$color='',$number='-1',$text = false) {
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		if($text){
			$text = '<div class="inner"><div id="introduce" class="slider_top">'.$text.'</div></div>';
		}else{
			$text = '';
		}
		$height = theme_get_option('slideshow', '3d_height');
		$wrap_height = $height+70;
		$uri = THEME_URI;
		$uploads = wp_upload_dir();
		if($category == '{g}'){
			$category = '{g:'.get_queried_object_id().'}';
		}
		$category = $category?'[category='.$category.']':'';
		$lang = wpml_get_current_languages();
		if($lang){
			$lang = '[lang='.$lang.']';
		}else{
			$lang = '';
		}
		$noflash = __('You need to <a href="http://www.adobe.com/products/flashplayer/" target="_blank">upgrade your Flash Player</a> to version 10 or newer.','striking_front');
		$output = <<<HTML

<div id="feature" class="3d"{$color}>
	<div class="top_shadow"></div>{$text}
	<object width="100%" height="{$wrap_height}" type="application/x-shockwave-flash" data="{$uri}/piecemaker/piecemaker_{$height}.swf" id="piecemaker">
		<param name="wmode" value="transparent">
		<param name="expressInstaller" value="{$uri}/swf/expressInstall.swf"/>
		<param name="flashvars" value="xmlSource={$uri}/piecemaker/piecemakerXML.php?vars=[number={$number}]{$category}{$lang}&amp;cssSource={$uri}/piecemaker/piecemakerCSS.css&amp;imageSource={$uploads['baseurl']}">
		<param name="movie" value="{$uri}/piecemaker/piecemaker_{$height}.swf"/>
		<embed src="{$uri}/piecemaker/piecemaker_{$height}.swf" type="application/x-shockwave-flash" wmode="transparent" width="100%" height="{$wrap_height}" />
	</object>
	<div class="bottom_shadow"></div>
</div>
HTML;
		return $output;
	}
	
	function slideShow_kwicks($category='',$color='',$number='-1',$text = false) {
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		$options = array(
			'autoplay' => theme_get_option('slideshow', 'kwicks_autoplay'),
			'pauseTime' => theme_get_option('slideshow', 'kwicks_pauseTime'),
			'number' => theme_get_option('slideshow', 'kwicks_number'),
			'max' => theme_get_option('slideshow', 'kwicks_max'),
			'duration' => theme_get_option('slideshow', 'kwicks_duration'),
			'easing' => theme_get_option('slideshow', 'kwicks_easing'),
			'title' => theme_get_option('slideshow', 'kwicks_title'),
			'title_speed' => theme_get_option('slideshow', 'kwicks_title_speed'),
			'title_opacity' => theme_get_option('slideshow', 'kwicks_title_opacity'),
			'detail' => theme_get_option('slideshow', 'kwicks_detail'),
			'detail_speed' => theme_get_option('slideshow', 'kwicks_detail_speed'),
			'detail_opacity' => theme_get_option('slideshow', 'kwicks_detail_opacity')
		);
		$height = theme_get_option('slideshow','kwicks_height');
		
		
		
		if($number > 8){
			$number = 8;
		}elseif($number < 2 && $number != -1){
			$number = 2;
		}elseif($number == -1){
			$number = -1;
		}
		
		$images = $this->slideShow_getImages($category,$number,'full');
		
		$number = count($images);
		if($number > 8){
			$number = 8;
		}
		$images = array_splice($images, $number);
		$options['number'] = $number;
		//$number = theme_get_option('slideshow', 'kwicks_number');
		//$number = $number ? $number : 4;
		if($text){
			$text = '<div id="introduce" class="slider_top">'.$text.'</div>';
		}else{
			$text = '';
		}
		$output = <<<HTML

<div id="feature" class="kwicks_slider"{$color}>
	<div class="top_shadow"></div>
		<div class="inner">{$text}
HTML;
		$output .= '<ul id="kwicks" class="kwicks-number-'.$number.'">';
		$images = $this->slideShow_getImages($category,$number,'full');

		foreach($images as $image) {
			$image_src = theme_get_image_src($image['source'], array($options['max'], $height));
			if($image['link'] != false){
				$link = $image['link'];
			}else{
				$link = '#';
			}
			$output .= "\n<li>";
			$output .= '<a href="'.$link.'" target="'.$image['target'].'"><img src="' . $image_src.'" width="'.$options['max'].'" height="'.$height.'" alt="'.$image['title'].'" /></a>';
			$output .= '<div class="kwick_title">' . $image['title'] . '</div>';
			$output .= '<div class="kwick_detail"><h3>' . $image['title'] . '</h3><div class="kwick_desc">' . $image['desc'] . '</div></div>';
			$output .= "</li>";
		}
$output .= <<<HTML

			</ul>
			<div id="kwicks_shadow"></div>
		</div>
	<div class="bottom_shadow"></div>
</div>
HTML;
		
		$output .= "\n<script type=\"text/javascript\">\n";
		$output .= "var slideShow = []; \n";
		foreach($options as $key => $value) {
			if (is_bool($value)) {
				$value = $value ? "true" : "false";
			} else if (is_numeric($value)) {
			
			} else {
				$value = "'" . $value . "'";
			}
			$output .= "slideShow['" . $key . "'] = " . $value . "; \n";
		}
		$output .= "</script>\n";
		return $output;
	}

	function slideShow_anything($category='',$color='',$number='-1',$text = false) {
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		if($text){
			$text = '<div id="introduce" class="slider_top">'.$text.'</div>';
		}else{
			$text = '';
		}
		$output =  <<<HTML

<div id="feature" class="anything"{$color}>
	<div class="top_shadow"></div>
	<div class="inner">{$text}
		<div id="anything_slider_wrap">
			<ul id="anything_slider">
HTML;
		
		$images = $this->slideShow_getImages($category,$number,'full');
		$height = theme_get_option('slideshow','anything_height');
		
		foreach($images as $image) {
			$stop = '';
			$click_stop = '';
			$bg = '';
			if($image['type'] !== 'slideshow'){
				$type = 'image';
			}else{
				$bg = get_post_meta($image['post_id'], '_anything_bg', true);
				if($bg != ''){
					$bg = ' style="background-color:'.$bg.'"';
				}
				if(theme_is_enabled(get_post_meta($image['post_id'], '_anything_stop', true))){
					$stop = ' stoped';
				}
				if(theme_is_enabled(get_post_meta($image['post_id'], '_anything_click_stop', true))){
					$click_stop = ' click_stoped';
				}
				
				$type = get_post_meta($image['post_id'], '_anything_type', true);
			}
			
			$output .= "\n<li class='panel".$stop.$click_stop."'".$bg.">\n";
			
			switch($type){
				case 'sidebar':
					$image_src = theme_get_image_src($image['source'], array(660, $height));
					$output .=  '<div class="anything_sidebar_'.get_post_meta($image['post_id'], '_sidebar_position', true).'">';
					$output .=  '<div class="anything_sidebar_content">';
					$page_data = get_page( $image['post_id'] );
					$content = $page_data->post_content; 
					$output .=  apply_filters('the_content', stripslashes( $content ));
					$output .=  '</div>';
					$output .=  '<div class="anything_sidebar_image">';
					if($image['link'] != false){
						$output .=  '<a href="'.$image['link'].'" target="'.$image['target'].'"><img class="slideimage" src="' . $image_src.'" width="660" height="'.$height.'" alt="'.$image['title'].'" /></a>';
					}else{
						$output .=  '<img class="slideimage" src="' . $image_src.'" width="660" height="'.$height.'" alt="'.$image['title'].'" />';
					}
					$output .=  '</div>';
					$output .=  '</div>';
					break;
				case 'html':
					$page_data = get_page( $image['post_id'] );
					$post_content = $page_data->post_content; 
					$output .=  apply_filters('the_content', stripslashes( $post_content ));
					break;
				case 'image':
				default:
					$image_src = theme_get_image_src($image['source'], array(960, $height));
					if($image['type'] !== 'slideshow'){
						$caption_position = theme_get_option('slideshow','anything_postsCaptionPosition');
					}else{
						$caption_position = get_post_meta($image['post_id'], '_image_caption_position', true);
					}
					if($image['link'] != false){
						if($caption_position != '' && $caption_position !='disable'){
							$output .=  '<a href="'.$image['link'].'" target="'.$image['target'].'" class="anything_caption caption_'.$caption_position.'">';
							$output .=  '<h3>'.$image['title'].'</h3>';
							if($image['desc']) $output .= '<p>'.$image['desc'].'</p>';
							$output .=  '</a>';
						}
						$output .=  '<a href="'.$image['link'].'" target="'.$image['target'].'"><img class="slideimage" src="' . $image_src.'" width="960" height="'.$height.'" alt="'.$image['title'].'" /></a>';
					}else{
						if($caption_position != '' && $caption_position !='disable'){
							$output .=  '<div class="anything_caption caption_'.$caption_position.'">';
							$output .=  '<h3>'.$image['title'].'</h3>';
							if($image['desc']) $output .= '<p>'.$image['desc'].'</p>';
							$output .=  '</div>';
						}
						$output .=  '<img class="slideimage" src="' . $image_src .'" width="960" height="'.$height.'" alt="'.$image['title'].'" />';
					}
					break;
			}
			$output .=  "\n</li>\n";
		}
		$output .=  <<<HTML

			</ul>
		</div>
		<div id="anything_shadow"></div>
	</div>
	<div class="bottom_shadow"></div>
</div>
HTML;

		$options = array(
			'easing' => theme_get_option('slideshow', 'anything_easing'),
			
			'buildArrows' => theme_get_option('slideshow', 'anything_buildArrows'), 
			'buildNavigation' => theme_get_option('slideshow', 'anything_buildNavigation'), 
			
			'toggleArrows' => theme_get_option('slideshow', 'anything_toggleArrows'), 
			'toggleControls' => theme_get_option('slideshow', 'anything_toggleControls'), 
			
			// Function
			"enableArrows" => theme_get_option('slideshow', 'anything_enableArrows'), 
			"enableNavigation" => theme_get_option('slideshow', 'anything_enableNavigation'), 
			"enableKeyboard" => theme_get_option('slideshow', 'anything_enableKeyboard'), 
			
			// Slideshow options
			'autoPlay' => theme_get_option('slideshow', 'anything_autoPlay'), 
			'autoPlayLocked' => theme_get_option('slideshow', 'anything_autoPlayLocked'), 
			'autoPlayDelayed' => theme_get_option('slideshow', 'anything_autoPlayDelayed'), 
			'pauseOnHover' => theme_get_option('slideshow', 'anything_pauseOnHover'), 
			'stopAtEnd' => theme_get_option('slideshow', 'anything_stopAtEnd'),
			'playRtl' => theme_get_option('slideshow', 'anything_playRtl'),
			
			'delay' => theme_get_option('slideshow', 'anything_delay'),
			'resumeDelay' => theme_get_option('slideshow', 'anything_resumeDelay'),
			'animationTime' => theme_get_option('slideshow', 'anything_animationTime'),
			
			'resumeOnVideoEnd' => theme_get_option('slideshow', 'anything_resumeOnVideoEnd'),
			'captionOpacity' => theme_get_option('slideshow', 'anything_captionOpacity'),
		);
		
		$output .=  "\n<script type=\"text/javascript\">\n";
		$output .=  "var slideShow = []; \n";
		foreach($options as $key => $value) {
			if (is_bool($value)) {
				$value = $value ? "true" : "false";
			} elseif(is_numeric($value)){
				$value = $value;
			} elseif($value!="true"&&$value!="false") {
				$value = "'" . $value . "'";
			}
			$output .=  "slideShow['" . $key . "'] = " . $value . "; \n";
		}
		$output .=  "</script>\n";

		return $output;
	}

	function portfolio_list($options){
		global $wp_filter;
		$the_content_filter_backup = $wp_filter['the_content'];

		$options = shortcode_atts(array(
			'column' => 4,
			'layout' => 'full',//sidebar
			'cat' => '',
			'max' => -1,
			'title' => '',
			'titlelinkable' => 'false',
			'desc' => '',
			'desc_length'=>'default',
			'more' => '',
			'moretext' => '',
			'height' => '',
			"ajax" => 'false',
			'current' => '',
			'nopaging' => 'false',
			'sortable' => 'false',
			'group' => 'true',
			'lightboxtitle' => 'portfolio', //portfolio,image,imagecaption,imagedesc,none
			'advancedesc'=>'false',
			'effect' => 'default', //icon,grayscale,none
			'ids' => '',
			'order'=> 'ASC',
			'orderby'=> 'menu_order', //none, id, author, title, date, modified, parent, rand, comment_count, menu_order
			'paged' => null
		), $options);
		
		extract($options);
		if($desc_length != 'default'){
			$excerpt_constructor = new Theme_The_Excerpt_Length_Constructor($desc_length);
			add_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
		}
		$output = '<div class="portfolio_wrap">';
		$size = array();
		switch($column){
			case 1:
				$column_class = 'one_column';
				if($layout=='sidebar'){
					$size[0] = '400';
				}else{
					$size[0] = '600';
				}
				$size[1] = (int)theme_get_option('portfolio','1_column_height');
				break;
			case 2:
				$column_class = 'two_columns';
				if($layout=='sidebar'){
					$size[0] = '293';
				}else{
					$size[0] = '450';
				}
				$size[1] = (int)theme_get_option('portfolio','2_columns_height');
				break;
			case 3:
				$column_class = 'three_columns';
				if($layout=='sidebar'){
					$size[0] = '188';
				}else{
					$size[0] = '292';
				}
				$size[1] = (int)theme_get_option('portfolio','3_columns_height');
				break;
			case 5:
				$column_class = 'five_columns';
				if($layout=='sidebar'){
					$size[0] = '110';
				}else{
					$size[0] = '172';
				}
				$size[1] = (int)theme_get_option('portfolio','5_columns_height');
				break;
			case 6:
				$column_class = 'six_columns';
				if($layout=='sidebar'){
					$size[0] = '90';
				}else{
					$size[0] = '140';
				}
				$size[1] = (int)theme_get_option('portfolio','6_columns_height');
				break;
			case 7:
				$column_class = 'seven_columns';
				if($layout=='sidebar'){
					$size[0] = '72';
				}else{
					$size[0] = '120';
				}
				$size[1] = (int)theme_get_option('portfolio','7_columns_height');
				break;
			case 8:
				$column_class = 'eight_columns';
				if($layout=='sidebar'){
					$size[0] = '63';
				}else{
					$size[0] = '99';
				}
				$size[1] = (int)theme_get_option('portfolio','8_columns_height');
				break;
			case 4:
			default:
				$column_class = 'four_columns';
				if($layout=='sidebar'){
					$size[0] = '136';
				}else{
					$size[0] = '217';
				}
				$size[1] = (int)theme_get_option('portfolio','4_columns_height');
		}
		if($height){
			$size[1] = $height;
		}
		$rel_group = 'portfolio_'.rand(1,1000); //for lightbox group

		if($layout=='sidebar'){
			$output .= '<ul class="portfolio_' . $column_class . ' with_sidebar portfolio_container">';
		}else{
			$output .= '<ul class="portfolio_' . $column_class . ' portfolio_container">';
		}

		if ($nopaging == 'false') {
			if(is_null($paged)){
				global $wp_version;
				if(is_front_page() && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query
					$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
				}else{
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				}
			}
			
			$query = array(
				'post_type' => 'portfolio', 
				'posts_per_page' => $max, 
				'paged' => $paged,
				'orderby'=> $orderby, 
				'order'=> $order
			);
		} else {
			$query = array(
				'post_type' => 'portfolio', 
				'showposts' => $max,
				'orderby'=> $orderby, 
				'order'=> $order
			);
		}
		if(!empty($current)){
			$cat = $current;
		}
		if($cat != ''){
			global $wp_version;
			if(version_compare($wp_version, "3.1", '>=')){
				$query['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio_category',
						'field' => 'slug',
						'terms' => explode(',', $cat)
					)
				);
			}else{
				$query['taxonomy'] = 'portfolio_category';
				$query['term'] = $cat;
			}
		}
		
		if($ids){
			$query['post__in'] = explode(',',$ids);
		}
		$r = new WP_Query($query);

		if($effect == 'default'){
			$effect = theme_get_option('portfolio','effect');
		}
		
		$i = 1;
		//deprecated
		if($title == ''){
			if(theme_get_option('portfolio','display_title') || $column == 1){
				$title = 'true';
			}
		}
		if($desc == ''){
			if(theme_get_option('portfolio','display_excerpt') || $column == 1){
				$desc = 'true';
			}
		}
		
		switch($more){
			case '':
				if( theme_get_option('portfolio','display_more_button') ){
					$more = true;
				}
				break;
			case 'false':
				$more = false;
				break;
			case 'true':
			default:
				$more = true;
				break;
		}
		while($r->have_posts()) {
			$r->the_post();
			$terms = get_the_terms(get_the_id(), 'portfolio_category');
			$terms_slug = array();
			if (is_array($terms)) {
				foreach($terms as $term) {
					$terms_slug[] = $term->slug;
				}
			}
			
			if (($i % $column) == 0 && $column != 1) {
				$output .= '<li class="portfolio_item" data-id="'.get_the_id().'" data-type="' . implode(',', $terms_slug) . '">';
			} else {
				$output .= '<li class="portfolio_item" data-id="'.get_the_id().'" data-type="' . implode(',', $terms_slug) . '">';
			}
			$i++;
			$type = get_post_meta(get_the_id(), '_type', true);
			
			if (has_post_thumbnail()) {
				$image_source = array('type'=>'attachment_id','value'=>get_post_thumbnail_id(get_the_id()));
				$image_src = theme_get_image_src($image_source, $size);
				
				$width = '';
				$height = '';
				$iframe = '';
				$inline = '';
				if($type == 'image'){
					$href =  get_post_meta(get_the_id(), '_image', true);
					if(empty($href) || (!empty($href) && is_array($href) && isset($href['value']) && empty($href['value']))){
						$href = theme_get_image_src($image_source);
					}else{
						$href = theme_get_image_src($href);
					}
					$icon = 'zoom';
					$lightbox = ' lightbox';
					if($group == 'true'){
						$rel = ' rel="'.$rel_group.'"';
					}else{
						$rel = '';
					}
				}elseif($type == 'video'){
					$href =  get_post_meta(get_the_id(), '_video', true);
					if(empty($href)){
						$href = theme_get_image_src($image_source);
					}
					$video_width = get_post_meta(get_the_id(), '_video_width', true);
					$video_height = get_post_meta(get_the_id(), '_video_height', true);
					if($video_width==''){
						$video_width = theme_get_option('portfolio','video_width');
					}
					if($video_height==''){
						$video_height = theme_get_option('portfolio','video_height');
					}
					$width = ' data-width="'.$video_width.'"';
					$height = ' data-height="'.$video_height.'"';
					
					$icon = 'play';
					$lightbox = ' lightbox';
					if($group == 'true'){
						$rel = ' rel="'.$rel_group.'"';
					}else{
						$rel = '';
					}
				}elseif($type == 'lightbox'){
					$href =  get_post_meta(get_the_id(), '_lightbox_href', true);
					if(empty($href)){
						$inline_id = 'portfolio_inline_'.get_the_id();
						$href = '#';
						$inline = ' data-inline="true" data-href="#'.$inline_id.'"';
						$output .= '<div class="hidden"><div id="'.$inline_id.'">';
						$output .= do_shortcode(get_post_meta(get_the_id(), '_lightbox_content', true));
						$output .= '</div></div>';
					}else{
						$iframe = ' data-iframe="true"';
					}
					$lightbox_width = get_post_meta(get_the_id(), '_lightbox_width', true);
					$lightbox_height = get_post_meta(get_the_id(), '_lightbox_height', true);
					if($lightbox_width==''){
						$lightbox_width = theme_get_option('portfolio','lightbox_width');
					}
					if($lightbox_height==''){
						$lightbox_height = theme_get_option('portfolio','lightbox_height');
					}
					$width = ' data-width="'.$lightbox_width.'"';
					$height = ' data-height="'.$lightbox_height.'"';
					
					$icon = 'zoom';
					$lightbox = ' fancyLightbox';
					if($group == 'true'){
						$rel = ' rel="'.$rel_group.'"';
					}else{
						$rel = '';
					}
				}elseif($type == 'link'){
					$link = get_post_meta(get_the_ID(), '_link', true);
					$href = theme_get_superlink($link);
					$link_target = get_post_meta(get_the_ID(), '_link_target', true);
					$link_target = $link_target?$link_target:'_self';
					$icon = 'link';
					$lightbox = '';
					$rel = '';
				}elseif($type == 'gallery'){
					$image_ids_str = get_post_meta(get_the_id(), '_image_ids', true);
					$image_ids = array();
					if(!empty($image_ids_str)){
						$image_ids = explode(',',str_replace('image-','',$image_ids_str));
						$image_id = array_shift($image_ids);
						if($lightboxtitle=='portfolio'){
							$image_title = get_the_title();
						}elseif($lightboxtitle=='image'){
							$image_title = get_the_title($image_id);
						}elseif($lightboxtitle=='imagecaption'){
							$attachment = get_post( $image_id );
							$image_title = $attachment->post_excerpt;//Caption
						}elseif($lightboxtitle=='imagedesc'){
							$attachment = get_post( $image_id );
							$image_title = $attachment->post_content;;//Description
						}else{
							$image_title = '';
						}
						$base_image_src = wp_get_attachment_image_src($image_id,'full');
						$href = $base_image_src[0];
					}else{
						$href =  get_post_meta(get_the_id(), '_image', true);
						if(empty($href)){
							$href = theme_get_image_src($image_source);
						}else{
							$href = theme_get_image_src($href);
						}
						if($lightboxtitle=='portfolio'){
							$image_title = get_the_title();
						}else{
							$image_title = '';
						}
					}
					$icon = 'zoom';
					$lightbox = ' lightbox';
					if($group == 'true'){
						$rel = ' rel="'.$rel_group.'"';
					}else{
						$rel = ' rel="gallery-'.get_the_ID().'"';
					}
				}else{
					$href = get_permalink();
					$icon = 'doc';
					$lightbox = '';
					$rel = '';
				}
				
				if($type!=='gallery'){
					if($lightboxtitle=='portfolio'){
							$image_title = get_the_title();
					}elseif($lightboxtitle=='image'){
							$image_title = get_the_title($image_id);
					}else{
							$image_title = '';
					}
				}
				$override_icon = get_post_meta(get_the_ID(), '_icon', true);
				if($override_icon && $override_icon != 'default'){
					$icon = $override_icon;
				}
				
				
				$output .= '<div class="image_styled portfolio_image">';
				$output .= '<span class="image_frame effect-'.$effect.'" style="height:'.$size[1].'px">';
				$output .= '<a class="image_icon_'.$icon.$lightbox.'" '.(isset($link_target)?'target="'.$link_target.'" ':'').' title="'. $image_title .'" href="' . $href . '"'.$rel.$width.$height.$inline.$iframe.'>';
				$output .= '<img src="' . $image_src . '" width="'.$size[0].'" height="'.$size[1].'" title="' . get_the_title() . '" alt="' . get_the_title() . '" />';
				$output .= '</a>';
				$output .= '</span>';
				$output .= '<img src="' . THEME_IMAGES . '/image_shadow.png" class="image_shadow">';
				$output .= '</div>';
			}
			
			$output .= '<div class="portfolio_details">';
			
			if($title == 'true'){
				if($titlelinkable == 'true'){
					if($type != 'link'){
						$href = get_permalink();
					}
					$output .= '<div class="portfolio_title"><a href="'.$href.'">' . get_the_title() . '</a></div>';
				} else{
					$output .= '<div class="portfolio_title">' . get_the_title() . '</div>';
				}
			}
			
			if($desc == 'true'){
				if($advancedesc == 'true'){
					remove_filter('get_the_excerpt', 'wp_trim_excerpt');
					$output .= '<div class="portfolio_desc">' . do_shortcode(wpautop(get_the_excerpt())) . '</div>';
				}else{
					$output .= '<div class="portfolio_desc">' . get_the_excerpt() . '</div>';
				}
				
			}
			
			if(theme_is_enabled(get_post_meta(get_the_id(), '_more', true), $more)){
				$more_link = theme_get_superlink(get_post_meta(get_the_id(), '_more_link', true), get_permalink());
				$more_link_target = get_post_meta(get_the_ID(), '_more_link_target', true);
				$more_link_target = $more_link_target?$more_link_target:'_self';
				if($moretext == ''){
					$moretext = wpml_t(THEME_NAME , 'Portfolio More Button Text',theme_get_option('portfolio','more_button_text'));
				}
				if(theme_get_option('portfolio','read_more_button')){
					$output .= '<div class="portfolio_more_button"><a href="'.$more_link.'" class="'.apply_filters( 'theme_css_class', 'button' ).'" target="'.$more_link_target.'"><span>'.$moretext.'</span></a></div>';
				}else{
					$output .= '<div class="portfolio_more_button"><a href="'.$more_link.'" target="'.$more_link_target.'"><span>'.$moretext.'</span></a></div>';
				}
					
			}
			if($type == 'gallery'&&!empty($image_ids)){
				$output .= '<div class="hidden">';
				foreach($image_ids as $image_id){
					if($lightboxtitle=='portfolio'){
						$image_title = get_the_title();
					}elseif($lightboxtitle=='image'){
						$image_title = get_the_title($image_id);
					}elseif($lightboxtitle=='imagecaption'){
						$attachment = get_post( $image_id );
						$image_title = $attachment->post_excerpt;//Caption
					}elseif($lightboxtitle=='imagedesc'){
						$attachment = get_post( $image_id );
						$image_title = $attachment->post_content;;//Description
					}else{
						$image_title = '';
					}
					$image_src = wp_get_attachment_image_src($image_id,'full');
					$output .= '<a class="lightbox" href="'.$image_src[0].'" title="'. $image_title .'" rel="'.(($group=='true')?$rel_group:'gallery-'.get_the_ID()).'">gallery-'.get_the_ID().'</a>';
				}
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		if ($nopaging == 'false') {
			ob_start();
			theme_portfolio_pagenavi('', '', $r, $paged);
			$output .= ob_get_clean();
		}
		$output .= '</div>';
		if($desc_length != 'default'){
			remove_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
		}
		wp_reset_postdata();
		$wp_filter['the_content'] = $the_content_filter_backup;
		return $output;
	}
}
function theme_generator($function){
	global $_themeGenerator;
	if($_themeGenerator==NULL){
		$_themeGenerator = new themeGenerator;
	}
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_themeGenerator, $function ), $args );
}
