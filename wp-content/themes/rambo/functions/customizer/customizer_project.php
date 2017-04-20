<?php
function rambo_project_customizer( $wp_customize ) {

//Project panel
	$wp_customize->add_panel( 'project_panel' , array(
	'title'      => __('Project section', 'rambo'),
	'capability'     => 'edit_theme_options',
	'priority'   => 540,
   	) );

		//Project panel
				$wp_customize->add_section( 'project_settings' , array(
				'title'      => __('Settings', 'rambo'),
				'panel'  => 'project_panel',
				'priority'   => 2,
				) );
			
			// enable project section
			$wp_customize->add_setting('rambo_pro_theme_options[project_protfolio_enabled]',array(
			'default' => false,
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'option'
			) );
			
			$wp_customize->add_control('rambo_pro_theme_options[project_protfolio_enabled]',array(
			'label' => __('Hide project section','rambo'),
			'section' => 'project_settings',
			'type' => 'checkbox',
			) );
			
			// Number of services
			$wp_customize->add_setting('rambo_pro_theme_options[project_column_layout]',array(
			'default' => 4,
			'type' => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control('rambo_pro_theme_options[project_column_layout]',array(
			'type' => 'select',
			'label' => __('Select column layout','rambo'),
			'section' => 'project_settings',
			'choices' => array(1=>'1',2=> '2',3=>'3',4=>'4'),
			) );


		//Portfolio setting
		$wp_customize->add_section( 'project_section_settings' , array(
				'title'      => __('Section Header','rambo'),
				'panel'  => 'project_panel',
				'priority'   => 3,
				) );
	
				//Project Title
				$wp_customize->add_setting(
				'rambo_pro_theme_options[project_heading_one]',
				array(
					'default' =>'',
					'capability'     => 'edit_theme_options',
					'sanitize_callback' => 'rambo_project_sanitize_html',
					'type' => 'option',
					)
				);	
				$wp_customize->add_control('rambo_pro_theme_options[project_heading_one]',array(
				'label'   => __('Title','rambo'),
				'section' => 'project_section_settings',
				 'type' => 'text',)  );	
				 
				//Project Description 
				 $wp_customize->add_setting(
				'rambo_pro_theme_options[project_tagline]',
				array(
					'default' => '',
					'capability'     => 'edit_theme_options',
					'sanitize_callback' => 'rambo_project_sanitize_html',
					'type' => 'option',
					)
				);	
				$wp_customize->add_control( 'rambo_pro_theme_options[project_tagline]',array(
				'label'   => __('Description','rambo'),
				'section' => 'project_section_settings',
				 'type' => 'textarea',)  );
				 
				 function rambo_project_sanitize_html( $input ) {
					return force_balance_tags( $input );
				}

}
add_action( 'customize_register', 'rambo_project_customizer' );
?>