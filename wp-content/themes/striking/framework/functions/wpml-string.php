<?php
wpml_register_string( THEME_NAME , 'Top Area Html Code', stripslashes(theme_get_option('general','top_area_html')));

wpml_register_string( THEME_NAME , 'Portfolio More Button Text', theme_get_option('portfolio','more_button_text'));

wpml_register_string( THEME_NAME , 'Portfolio Sortable Show Text', theme_get_option('portfolio','show_text'));

wpml_register_string( THEME_NAME , 'Copyright Footer Text', stripslashes(theme_get_option('footer','copyright')));

wpml_register_string( THEME_NAME , 'Footer Right Area Html Code', stripslashes(theme_get_option('footer','footer_right_area_html')));

wpml_register_string( THEME_NAME , 'Social Icon Widget Alt Title', __('Follow Us on','striking_front'));

wpml_register_string( THEME_NAME , 'Blog Post Read More Button Text', stripslashes(theme_get_option('blog','read_more_text')));

wpml_register_string( THEME_NAME , 'Category Archive Title', stripslashes(theme_get_option('advance','category_title')));
wpml_register_string( THEME_NAME , 'Tag Archive Title', stripslashes(theme_get_option('advance','tag_title')));
wpml_register_string( THEME_NAME , 'Daily Archive Title', stripslashes(theme_get_option('advance','daily_title')));
wpml_register_string( THEME_NAME , 'Monthly Archive Title', stripslashes(theme_get_option('advance','monthly_title')));
wpml_register_string( THEME_NAME , 'Weekly Archive Title', stripslashes(theme_get_option('advance','weekly_title')));
wpml_register_string( THEME_NAME , 'Yearly Archive Title', stripslashes(theme_get_option('advance','yearly_title')));
wpml_register_string( THEME_NAME , 'Author Archive Title', stripslashes(theme_get_option('advance','author_title')));
wpml_register_string( THEME_NAME , 'Blog Archive Title', stripslashes(theme_get_option('advance','blog_title')));
wpml_register_string( THEME_NAME , 'Taxonomy Archive Title', stripslashes(theme_get_option('advance','taxonomy_title')));
wpml_register_string( THEME_NAME , '404 Page Title', stripslashes(theme_get_option('advance','404_title')));
wpml_register_string( THEME_NAME , 'Search Page Title', stripslashes(theme_get_option('advance','search_title')));

wpml_register_string( THEME_NAME , 'Category Archive Text', stripslashes(theme_get_option('advance','category_text')));
wpml_register_string( THEME_NAME , 'Tag Archive Text', stripslashes(theme_get_option('advance','tag_text')));
wpml_register_string( THEME_NAME , 'Daily Archive Text', stripslashes(theme_get_option('advance','daily_text')));
wpml_register_string( THEME_NAME , 'Monthly Archive Text', stripslashes(theme_get_option('advance','monthly_text')));
wpml_register_string( THEME_NAME , 'Weekly Archive Text', stripslashes(theme_get_option('advance','weekly_text')));
wpml_register_string( THEME_NAME , 'Yearly Archive Text', stripslashes(theme_get_option('advance','yearly_text')));
wpml_register_string( THEME_NAME , 'Author Archive Text', stripslashes(theme_get_option('advance','author_text')));
wpml_register_string( THEME_NAME , 'Blog Archive Text', stripslashes(theme_get_option('advance','blog_text')));
wpml_register_string( THEME_NAME , 'Taxonomy Archive Text', stripslashes(theme_get_option('advance','taxonomy_text')));
wpml_register_string( THEME_NAME , '404 Page Text', stripslashes(theme_get_option('advance','404_text')));
wpml_register_string( THEME_NAME , 'Search Page Text', stripslashes(theme_get_option('advance','search_text')));
wpml_register_string( THEME_NAME , 'Search Nothing Found Text', stripslashes(theme_get_option('blog','search_nothing_found')));

$archives = get_post_types(array(
  'public'   => true,
  '_builtin' => false,
  'show_ui'=> true,
),'objects');
if ($archives) {
	foreach ($archives  as $archive ) {
		wpml_register_string( THEME_NAME , $archive->name.' Post Type Archive Title', stripslashes(theme_get_option('advance','archive_'.$archive->name.'_title')));
		wpml_register_string( THEME_NAME , $archive->name.' Post Type Archive Text', stripslashes(theme_get_option('advance','archive_'.$archive->name.'_text')));
	}
}
$taxonomies=get_taxonomies(array(
	'public'   => true,
	'_builtin' => false,
	'show_ui'=> true,
),'objects');
if ($taxonomies) {
	foreach ($taxonomies  as $taxonomy ) {
		wpml_register_string( THEME_NAME , $taxonomy->name.' Taxonomy Archive Title', stripslashes(theme_get_option('advance','taxonomy_'.$taxonomy->name.'_title')));
		wpml_register_string( THEME_NAME , $taxonomy->name.' Taxonomy Archive Text', stripslashes(theme_get_option('advance','taxonomy_'.$taxonomy->name.'_text')));
	}
}