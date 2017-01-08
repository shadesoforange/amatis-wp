<?php
/**
 * Create Theme Customizer
 *
 * @package create
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function create_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	// remove controls
	$wp_customize->remove_control('blogdescription');

	// -- Sanitization Callbacks

	/**
	 * Sanitize boolean
	 *
	 * @param $input
	 *
	 * @return bool
	 */
	function create_sanitize_checkbox( $input ) {
	    if ( $input == 1 ) {
	        return 1;
	    } else {
	        return 0;
	    }
	}

	/**
	 * Sanitize numbers
	 *
	 * @param int $input
	 *
	 * @return int
	 */
	function create_sanitize_number( $input ) {
		if ( is_numeric( $input ) ) {
			return $input;
		} else {
			return '';
		}
	}

	/**
	 * Sanitize banner type
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	function create_sanitize_banner_type( $input ){

		if( 'static' == $input || 'campaign' == $input )
			return $input;
		else
			return '';

	} // create_sanitize_banner_type()

	if ( ! class_exists( 'WP_Customize_Control' ) )
		return NULL;

	/**
	 * Class to add a text area for page and section descriptions
	 */

	class Create_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	} // Create_Textarea_Control

	/**
	 * Class to add a header to certain sections
	 */
	class Create_Header_Control extends WP_Customize_Control {
		public $type = 'tag';
		public function render_content() {
			?>
			<h3 class="customize-control-title"><?php echo esc_html( $this->label ); ?></h3>
		<?php
		}
	}

	//Text Area Control
	class TTrust_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}

	
	
	// -- General --------------------------------------------------------------------------------------------------

	$wp_customize->add_panel( 'create_general', array(
		'priority'		 => 1,
	    'title'     	 => __( 'General', 'create' )
	) );
	
	// -- Loader
	
	$wp_customize->add_section( 'create_loader', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Loader', 'create' ),
		'panel'			=> 'create_general'
	) );
	
	$wp_customize->add_setting( 'create_loader_enabled' , array(
	    'default'     		=> __( 'yes', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_loader_enabled', array(
		'type'      => 'select',
		'label'     => __( 'Enable Loader', 'create' ),
		'section'   => 'create_loader',
		'settings'  => 'create_loader_enabled',
		'choices'   => array(
		            'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_loader_animation' , array(
	    'default'     		=> __( 'rotating-plane', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_loader_animation', array(
		'type'      => 'select',
		'label'     => __( 'Loader Animation', 'create' ),
		'section'   => 'create_loader',
		'settings'  => 'create_loader_animation',
		'choices'   => array(
		            'rotating-plane' => 'Rotating Plane',
		            'double-bounce' => 'Double Bounce',
					'wave' => 'Wave',
		            'wandering-cubes' => 'Wandering Cubes',
					'pulse' => 'Pulse'
		        ),
		'priority'   => 2
	) );
	
	$wp_customize->add_setting( 'create_loader_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_loader_color', array(
			'label'      => __( 'Loader Icon Color', 'create' ),
			'section'    => 'create_loader',
			'settings'   => 'create_loader_color',
			'priority'   => 3
		) )
	);
	
	$wp_customize->add_setting( 'create_loader_bkg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_loader_bkg_color', array(
			'label'      => __( 'Loader Background Color', 'create' ),
			'section'    => 'create_loader',
			'settings'   => 'create_loader_bkg_color',
			'priority'   => 4
		) )
	);
	
	
	
	// -- Layout --------------------------------------------------------------------------------------------------
	
	$wp_customize->add_section( 'create_layout', array(
		'priority'		 => 3,
	    'title'     	 => __( 'Layout', 'create' )
	) );
	
	$wp_customize->add_setting( 'create_site_width' , array(
	    'default'     		=> __( 'full-width', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_site_width', array(
		'type'      => 'select',
		'label'     => __( 'Site Container', 'create' ),
		'section'   => 'create_layout',
		'settings'  => 'create_site_width',
		'choices'   => array(
		            'full-width' => 'Full Width',
		            'boxed' => 'Boxed'
		        ),
		'priority'   => 31
	) );
	
	
	
	// -- Header & Navigation --------------------------------------------------------------------------------------------------

	$wp_customize->add_panel( 'create_header_navigation', array(
		'priority'		 => 4,
	    'title'     	 => __( 'Header & Navigation', 'create' )
	) );
	
	// -- Logos
	
	$wp_customize->add_section( 'create_logos', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Logos', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_logo_top' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_top_logo', array(
		'label'      => __('Header Logo - Dark', 'create'),
		'section'    => 'create_logos',
		'settings'   => 'create_logo_top',
	    'priority'   => 1
	) ) );
	
	$wp_customize->add_setting( 'create_logo_top_width' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_logo_top_width', array(
		'label'     => __( 'Header Logo Width - Dark', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the actual width of your logo image in pixels. Used for retina displays.', 'create' ),
		'section'   => 'create_logos',
		'settings'  => 'create_logo_top_width',
		'priority'   => 2
	) );
	
	$wp_customize->add_setting( 'create_logo_top_light' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_logo_top_light', array(
		'label'      => __('Header Logo - Light', 'create'),
		'section'    => 'create_logos',
		'settings'   => 'create_logo_top_light',
	    'priority'   => 2.5
	) ) );
	
	$wp_customize->add_setting( 'create_logo_top_width_light' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_logo_top_width_light', array(
		'label'     => __( 'Header Logo Width - Light', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the actual width of your logo image in pixels. Used for retina displays.', 'create' ),
		'section'   => 'create_logos',
		'settings'  => 'create_logo_top_width_light',
		'priority'   => 2.7
	) );
	
	$wp_customize->add_setting( 'create_logo_sticky' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_top_sticky', array(
		'label'      => __('Sticky Header Logo', 'create'),
		'section'    => 'create_logos',
		'settings'   => 'create_logo_sticky',
	    'priority'   => 3
	) ) );
	
	$wp_customize->add_setting( 'create_logo_sticky_width' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_logo_sticky_width', array(
		'label'     => __( 'Sticky Header Logo Width', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the actual width of your logo image in pixels. Used for retina displays.', 'create' ),
		'section'   => 'create_logos',
		'settings'  => 'create_logo_sticky_width',
		'priority'   => 4
	) );
	
	$wp_customize->add_setting( 'create_logo_mobile_width' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_logo_mobile_width', array(
		'label'     => __( 'Mobile Logo Max Width ', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Set the max width of logo for mobile.', 'create' ),
		'section'   => 'create_logos',
		'settings'  => 'create_logo_mobile_width',
		'priority'   => 5
	) );
	
	/*
	$wp_customize->add_setting( 'create_logo_side' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_top_side', array(
		'label'      => __('Side Header Logo', 'create'),
		'section'    => 'create_logos',
		'settings'   => 'create_logo_side',
	    'priority'   => 5
	) ) );
	
	$wp_customize->add_setting( 'create_logo_side_width' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_logo_side_width', array(
		'label'     => __( 'Side Header Logo Width', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the actual width of your logo image in pixels. Used for retina displays.', 'create' ),
		'section'   => 'create_logos',
		'settings'  => 'create_logo_side_width',
		'priority'   => 6
	) );
	*/
	
	$wp_customize->add_setting( 'create_favicon' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_favicon', array(
		'label'      => __('Favicon', 'create'),
		'section'    => 'create_logos',
		'settings'   => 'create_favicon',
	    'priority'   => 7
	) ) );
	
	
	
	// -- Position & Style
	
	$wp_customize->add_section( 'create_header', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Position & Style', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_header_top_layout' , array(
	    'default'     		=> __( 'full-width', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_header_top_layout', array(
		'type'      => 'select',
		'label'     => __( 'Header Layout', 'create' ),
		'section'   => 'create_header',
		'settings'  => 'create_header_top_layout',
		'choices'   => array(
		            'inline-header' => 'Inline',
					'stacked-header' => 'Stacked',
					'split-header inline-header' => 'Split'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_sticky_header' , array(
	    'default'     		=> __( 'full-width', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_sticky_header', array(
		'type'      => 'select',
		'label'     => __( 'Enable Sticky Header', 'create' ),
		'section'   => 'create_header',
		'settings'  => 'create_sticky_header',
		'choices'   => array(
		            '' => 'No',
					'sticky-header' => 'Yes'
		        ),
		'priority'   => 2
	) );
	
	$wp_customize->add_setting( 'create_top_header_height' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_top_header_height', array(
		'label'     => __( 'Top Header Height', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the height of your top header in pixels.', 'create' ),
		'section'   => 'create_header',
		'settings'  => 'create_top_header_height',
		'priority'   => 2.7
	) );
	
	$wp_customize->add_setting( 'create_sticky_header_height' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_sticky_header_height', array(
		'label'     => __( 'Sticky Header Height', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter the height of your sticky header in pixels.', 'create' ),
		'section'   => 'create_header',
		'settings'  => 'create_sticky_header_height',
		'priority'   => 2.8
	) );
	
	$wp_customize->add_setting( 'create_header_bkg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_header_bkg_color', array(
			'label'      => __( 'Header Background Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_header_bkg_color',
			'priority'   => 3
		) )
	);
	
	$wp_customize->add_setting( 'create_sticky_header_bkg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_sticky_header_bkg_color', array(
			'label'      => __( 'Sticky Header Background Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_sticky_header_bkg_color',
			'priority'   => 4
		) )
	);
	
	
	$wp_customize->add_setting( 'create_main_menu_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_main_menu_color', array(
			'label'      => __( 'Main Menu Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_main_menu_color',
			'priority'   => 5
		) )
	);
	
	$wp_customize->add_setting( 'create_main_menu_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_main_menu_hover_color', array(
			'label'      => __( 'Main Menu Hover Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_main_menu_hover_color',
			'priority'   => 6
		) )
	);
	
	$wp_customize->add_setting( 'create_sticky_main_menu_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_sticky_main_menu_color', array(
			'label'      => __( 'Sticky Main Menu Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_sticky_main_menu_color',
			'priority'   => 7
		) )
	);
	
	$wp_customize->add_setting( 'create_sticky_main_menu_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_sticky_main_menu_hover_color', array(
			'label'      => __( 'Sticky Main Menu Hover Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_sticky_main_menu_hover_color',
			'priority'   => 8
		) )
	);
	
	$wp_customize->add_setting( 'create_site_title_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_site_title_color', array(
			'label'      => __( 'Site Title Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_site_title_color',
			'priority'   => 9
		) )
	);
	
	$wp_customize->add_setting( 'create_sticky_site_title_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_sticky_site_title_color', array(
			'label'      => __( 'Sticky Site Title Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_sticky_site_title_color',
			'priority'   => 10
		) )
	);
	
	$wp_customize->add_setting( 'create_drop_down_bg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_drop_down_bg_color', array(
			'label'      => __( 'Drop Down Background Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_drop_down_bg_color',
			'priority'   => 11
		) )
	);
	
	$wp_customize->add_setting( 'create_drop_down_link_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_drop_down_link_color', array(
			'label'      => __( 'Drop Down Link Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_drop_down_link_color',
			'priority'   => 12
		) )
	);
	
	$wp_customize->add_setting( 'create_drop_down_link_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_drop_down_link_hover_color', array(
			'label'      => __( 'Drop Down Link Hover Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_drop_down_link_hover_color',
			'priority'   => 13
		) )
	);
	
	$wp_customize->add_setting( 'create_drop_down_divider_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_drop_down_divider_color', array(
			'label'      => __( 'Drop Down Divider Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_drop_down_divider_color',
			'priority'   => 14
		) )
	);
	
	$wp_customize->add_setting( 'create_header_accent_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_header_accent_color', array(
			'label'      => __( 'Accent Color', 'create' ),
			'section'    => 'create_header',
			'settings'   => 'create_header_accent_color',
			'priority'   => 15
		) )
	);
	
	// -- Slide Panel
	
	$wp_customize->add_section( 'create_slide_panel', array(
		'priority'		 => 2,
	    'title'     	 => __( 'Slide Panel', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_enable_slide_panel' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_enable_slide_panel', array(
		'type'      => 'select',
		'label'     => __( 'Enable Slide Panel', 'create' ),
		'description'     => __( 'Shows the toggle for the slide out panel at all times.', 'create' ),
		'section'   => 'create_slide_panel',
		'settings'  => 'create_enable_slide_panel',
		'choices'   => array(
		            'no' => 'No',
					'yes' => 'Yes'
		        ),
		'priority'   => 2
	) );
	
	$wp_customize->add_setting( 'create_slide_panel_background', array(
		'default'     		=> '',
		'type'				=> 'theme_mod',
		'transport'			=> 'refresh',
		'sanitize_callback'	=> 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'create_slide_panel_background', array(
		'label'      => __( 'Slide Panel Background Image', 'create' ),
		'section'    => 'create_slide_panel',
		'settings'   => 'create_slide_panel_background',
		'priority'   => 3
	) ) );
	
	$wp_customize->add_setting( 'create_slide_panel_bg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_slide_panel_bg_color', array(
			'label'      => __( 'Slide Panel Background Color', 'create' ),
			'section'    => 'create_slide_panel',
			'settings'   => 'create_slide_panel_bg_color',
			'priority'   => 4
		) )
	);
	
	$wp_customize->add_setting( 'create_slide_panel_text_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_slide_panel_text_color', array(
			'label'      => __( 'Slide Panel Text Color', 'create' ),
			'section'    => 'create_slide_panel',
			'settings'   => 'create_slide_panel_text_color',
			'priority'   => 5
		) )
	);
	
	$wp_customize->add_setting( 'create_slide_panel_link_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_slide_panel_link_color', array(
			'label'      => __( 'Slide Panel Link Color', 'create' ),
			'section'    => 'create_slide_panel',
			'settings'   => 'create_slide_panel_link_color',
			'priority'   => 6
		) )
	);
	
	$wp_customize->add_setting( 'create_slide_panel_link_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_slide_panel_link_hover_color', array(
			'label'      => __( 'Slide Panel Link Hover Color', 'create' ),
			'section'    => 'create_slide_panel',
			'settings'   => 'create_slide_panel_link_hover_color',
			'priority'   => 7
		) )
	);
	
	$wp_customize->add_setting( 'create_slide_panel_divider_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_slide_panel_divider_color', array(
			'label'      => __( 'Slide Panel Divider Color', 'create' ),
			'section'    => 'create_slide_panel',
			'settings'   => 'create_slide_panel_divider_color',
			'priority'   => 8
		) )
	);
	
	
	// -- Search
	
	$wp_customize->add_section( 'create_header_search', array(
		'priority'		 => 3,
	    'title'     	 => __( 'Search', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_enable_header_search' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_enable_header_search', array(
		'type'      => 'select',
		'label'     => __( 'Enable Search Icon', 'create' ),
		'description'     => __( 'Shows the search icon in the top header.', 'create' ),
		'section'   => 'create_header_search',
		'settings'  => 'create_enable_header_search',
		'choices'   => array(
		            'no' => 'No',
					'yes' => 'Yes'
		        ),
		'priority'   => 1
	) );
	
	// -- Scroll To Top
	
	$wp_customize->add_section( 'create_header_scroll_to_top', array(
		'priority'		 => 3.5,
	    'title'     	 => __( 'Scroll to Top Button', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_enable_header_scroll_to_top' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_enable_header_scroll_to_top', array(
		'type'      => 'select',
		'label'     => __( 'Enable Scroll to Top Button', 'create' ),
		'description'     => __( 'Shows a button in the lower right that allows the user to scroll to the top.', 'create' ),
		'section'   => 'create_header_scroll_to_top',
		'settings'  => 'create_enable_header_scroll_to_top',
		'choices'   => array(
		            'yes' => 'Yes',
					'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_scroll_to_top_bg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_scroll_to_top_bg_color', array(
			'label'      => __( 'Scroll to Top Background Color', 'create' ),
			'section'    => 'create_header_scroll_to_top',
			'settings'   => 'create_scroll_to_top_bg_color',
			'priority'   => 2
		) )
	);
	
	$wp_customize->add_setting( 'create_scroll_to_top_arrow_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_scroll_to_top_arrow_color', array(
			'label'      => __( 'Scroll to Top Arrow Color', 'create' ),
			'section'    => 'create_header_scroll_to_top',
			'settings'   => 'create_scroll_to_top_arrow_color',
			'priority'   => 3
		) )
	);
	
	// -- Mobile
	
	$wp_customize->add_section( 'create_mobile_header', array(
		'priority'		 => 4,
	    'title'     	 => __( 'Mobile', 'create' ),
		'panel'			=> 'create_header_navigation'
	) );
	
	$wp_customize->add_setting( 'create_mobile_header_breakpoint' , array(
	    'default'   		=> '',
	    'type'				=> 'theme_mod',
	    'transport'			=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_mobile_header_breakpoint', array(
		'label'     => __( 'Custom Mobile Breakpoint', 'create' ),
		'type'      => 'text',
		'description'     => __( 'Enter a custom mobile break point in pixels. (ex. 590)', 'create' ),
		'section'   => 'create_mobile_header',
		'settings'  => 'create_mobile_header_breakpoint',
		'priority'   => 1
	) );
	
	// -- Page Titles --------------------------------------------------------------------------------------------------
	
	$wp_customize->add_section( 'create_page_titles', array(
		'priority'		 => 4.2,
	    'title'     	 => __( 'Page Titles', 'create' )
	) );
	
	$wp_customize->add_setting( 'create_page_title_alignment' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_page_title_alignment', array(
		'type'      => 'select',
		'label'     => __( 'Text Alignment', 'create' ),
		'description'     => __( 'Set the text alignment of all page titles.', 'create' ),
		'section'   => 'create_page_titles',
		'settings'  => 'create_page_title_alignment',
		'choices'   => array(
					'left' => 'Left',
		            'center' => 'Center',
					'right' => 'Right'
		        ),
		'priority'   => 1
	) );
	
	// Page Title Text Color
	$wp_customize->add_setting( 'create_page_title_text_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_page_title_text_color', array(
			'label'      => __( 'Text Color', 'create' ),
			'section'    => 'create_page_titles',
			'settings'   => 'create_page_title_text_color',
			'priority'   => 2
		) )
	);
	
	// Page Title Area Background Color
	$wp_customize->add_setting( 'create_page_title_bg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_page_title_bg_color', array(
			'label'      => __( 'Background Color', 'create' ),
			'section'    => 'create_page_titles',
			'settings'   => 'create_page_title_bg_color',
			'priority'   => 3
		) )
	);
	
	// -- Blog --------------------------------------------------------------------------------------------------

	$wp_customize->add_panel( 'create_blog', array(
		'priority'		 => 4.5,
	    'title'     	 => __( 'Blog', 'create' )
	) );
	
	// -- General
	
	$wp_customize->add_section( 'create_blog_general', array(
		'priority'		 => .5,
	    'title'     	 => __( 'General', 'create' ),
		'panel'			=> 'create_blog'
	) );
	
	$wp_customize->add_setting( 'create_show_full_posts' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_full_posts', array(
		'type'      => 'select',
		'label'     => __( 'Show Full Posts', 'create' ),
		'description'     => __( 'Show full posts on blog pages.', 'create' ),
		'section'   => 'create_blog_general',
		'settings'  => 'create_show_full_posts',
		'choices'   => array(
					'no' => 'No',
		            'yes' => 'Yes'
		        ),
		'priority'   => 1
	) );
	
	// -- Meta
	
	$wp_customize->add_section( 'create_post_meta', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Post Meta', 'create' ),
		'panel'			=> 'create_blog'
	) );
	
	$wp_customize->add_setting( 'create_show_meta_date' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_meta_date', array(
		'type'      => 'select',
		'label'     => __( 'Show Date', 'create' ),
		'description'     => __( 'Show the date on each post', 'create' ),
		'section'   => 'create_post_meta',
		'settings'  => 'create_show_meta_date',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_show_meta_author' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_meta_author', array(
		'type'      => 'select',
		'label'     => __( 'Show Author', 'create' ),
		'description'     => __( 'Show the author on each post.', 'create' ),
		'section'   => 'create_post_meta',
		'settings'  => 'create_show_meta_author',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_show_meta_categories' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_meta_categories', array(
		'type'      => 'select',
		'label'     => __( 'Show Categories', 'create' ),
		'description'     => __( 'Show the categories on each post.', 'create' ),
		'section'   => 'create_post_meta',
		'settings'  => 'create_show_meta_categories',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_show_meta_comments' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_meta_comments', array(
		'type'      => 'select',
		'label'     => __( 'Show Comment Count', 'create' ),
		'description'     => __( 'Show the comment count on each post.', 'create' ),
		'section'   => 'create_post_meta',
		'settings'  => 'create_show_meta_comments',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	
	// -- Archive Layout
	
	$wp_customize->add_section( 'create_archives', array(
		'priority'		 => 2,
	    'title'     	 => __( 'Archives', 'create' ),
		'panel'			=> 'create_blog'
	) );	
	
	$wp_customize->add_setting( 'create_archive_layout' , array(
	    'default'     		=> __( 'full-width', 'create' ),
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_archive_layout', array(
		'type'      => 'select',
		'label'     => __( 'Archive Layout', 'create' ),
		'section'   => 'create_archives',
		'settings'  => 'create_archive_layout',
		'choices'   => array(
		            'standard' => 'Standard',
					'full-width' => 'Full Width',
		            'masonry' => 'Masonry',
					'masonry-full-width' => 'Masonry Full Width',
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_archive_show_excerpt' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_archive_show_excerpt', array(
		'type'      => 'select',
		'label'     => __( 'Show Excerpts', 'create' ),
		'section'   => 'create_archives',
		'settings'  => 'create_archive_show_excerpt',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 2
	) );
	
	
	// -- Shop --------------------------------------------------------------------------------------------------

	$wp_customize->add_panel( 'create_shop', array(
		'priority'		 => 4.7,
	    'title'     	 => __( 'Shop', 'create' )
	) );
	
	// -- Layout
	
	$wp_customize->add_section( 'create_shop_layout_section', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Layout', 'create' ),
		'panel'			=> 'create_shop'
	) );
	
	$wp_customize->add_setting( 'create_shop_layout' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_shop_layout', array(
		'type'      => 'select',
		'label'     => __( 'Layout', 'create' ),
		'description'     => __( 'Choose the layout of your shop pages.', 'create' ),
		'section'   => 'create_shop_layout_section',
		'settings'  => 'create_shop_layout',
		'choices'   => array(
					'full-width' => 'Full Width',
		            'has-sidebar' => 'With Sidebar'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_shop_product_count' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control('create_shop_product_count', array(
	        'label' => __( 'Products Per Page', 'create' ),
	        'section' => 'create_shop_layout_section',
	        'type' => 'text',
	    )
	);
	
	// -- Style
	
	$wp_customize->add_section( 'create_shop_style_section', array(
		'priority'		 => 2,
	    'title'     	 => __( 'Style', 'create' ),
		'panel'			=> 'create_shop'
	) );
	
	// Product Hover Color
	$wp_customize->add_setting( 'create_product_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_product_hover_color', array(
			'label'      => __( 'Product Hover Color', 'create' ),
			'section'    => 'create_shop_style_section',
			'settings'   => 'create_product_hover_color',
			'priority'   => 1
		) )
	);
	
	// Shop Accent Color
	$wp_customize->add_setting( 'create_shop_accent_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_shop_accent_color', array(
			'label'      => __( 'Accent Color', 'create' ),
			'section'    => 'create_shop_style_section',
			'settings'   => 'create_shop_accent_color',
			'priority'   => 2
		) )
	);
	
	
	
	// -- Social Sharing --------------------------------------------------------------------------------------------------
	
	$wp_customize->add_section( 'create_social_sharing', array(
		'priority'		 => 5,
	    'title'     	 => __( 'Social Sharing', 'create' )
	) );
	
	$wp_customize->add_setting( 'create_show_social_on_posts' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_social_on_posts', array(
		'type'      => 'select',
		'label'     => __( 'Show Sharing Links on Posts', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_social_on_posts',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_setting( 'create_show_social_on_projects' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_social_on_projects', array(
		'type'      => 'select',
		'label'     => __( 'Show Sharing Links on Projects', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_social_on_projects',
		'choices'   => array(
					'no' => 'No',
					'yes' => 'Yes'
		        ),
		'priority'   => 1.1
	) );
	
	$wp_customize->add_setting( 'create_show_social_on_pages' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_social_on_pages', array(
		'type'      => 'select',
		'label'     => __( 'Show Sharing Links on Pages', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_social_on_pages',
		'choices'   => array(
					'no' => 'No',
					'yes' => 'Yes'
		        ),
		'priority'   => 1.2
	) );
	
	$wp_customize->add_setting( 'create_show_facebook' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_facebook', array(
		'type'      => 'select',
		'label'     => __( 'Show Facebook', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_facebook',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 2
	) );
	
	$wp_customize->add_setting( 'create_show_twitter' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_twitter', array(
		'type'      => 'select',
		'label'     => __( 'Show Twitter', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_twitter',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 3
	) );
	
	$wp_customize->add_setting( 'create_show_google' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_google', array(
		'type'      => 'select',
		'label'     => __( 'Show Google Plus', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_google',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 4
	) );
	
	$wp_customize->add_setting( 'create_show_linkedin' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_linkedin', array(
		'type'      => 'select',
		'label'     => __( 'Show LinkedIn', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_linkedin',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 5
	) );
	
	$wp_customize->add_setting( 'create_show_pinterest' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_pinterest', array(
		'type'      => 'select',
		'label'     => __( 'Show Pinterest', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_pinterest',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 6
	) );
	
	$wp_customize->add_setting( 'create_show_tumblr' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_show_tumblr', array(
		'type'      => 'select',
		'label'     => __( 'Show Tumblr', 'create' ),
		'section'   => 'create_social_sharing',
		'settings'  => 'create_show_tumblr',
		'choices'   => array(
					'yes' => 'Yes',
		            'no' => 'No'
		        ),
		'priority'   => 7
	) );
	
	// -- Other Styles Panel
	
	$wp_customize->add_panel( 'create_other_styles', array(
		'priority'		 => 5.5,
	    'title'     	 => __( 'Other Styles', 'create' )
	) );
	
	// Global Text
	$wp_customize->add_section( 'create_base_text', array(
		'priority'		 => 1,
	    'title'     	 => __( 'Text', 'create' ),
		'panel'          => 'create_other_styles'
	) );
	
	// Link Color
	$wp_customize->add_setting( 'create_base_text_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_base_text_color', array(
			'label'      => __( 'Color', 'create' ),
			'section'    => 'create_base_text',
			'settings'   => 'create_base_text_color',
			'description'     	 => __( 'Set the base text color of your site.', 'create' ),
			'priority'   => 1
		) )
	);

	// Links
	$wp_customize->add_section( 'create_links', array(
		'priority'		 => 2,
	    'title'     	 => __( 'Links', 'create' ),
		'description'     	 => __( 'Set the color of links that appear in the content text.', 'create' ),
		'panel'          => 'create_other_styles'
	) );
	
	// Link Color
	$wp_customize->add_setting( 'create_link_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_link_color', array(
			'label'      => __( 'Link Color', 'create' ),
			'section'    => 'create_links',
			'settings'   => 'create_link_color',
			'priority'   => 1
		) )
	);

	// Link Hover Color (Incl. Active)
	$wp_customize->add_setting( 'create_link_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_link_hover_color', array(
			'label'      => __( 'Link Hover Color', 'create' ),
			'section'    => 'create_links',
			'settings'   => 'create_link_hover_color',
			'priority'   => 2
		) )
	);
	
	// Buttons
	$wp_customize->add_section( 'create_buttons', array(
		'priority'		 => 3,
	    'title'     	 => __( 'Buttons', 'create' ),
		'description'     	 => __( 'Set the color of buttons that appear on the site. This includes form and pagination buttons.', 'create' ),
		'panel'          => 'create_other_styles'
	) );
	
	// Button Color
	$wp_customize->add_setting( 'create_button_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_button_color', array(
			'label'      => __( 'Button Color', 'create' ),
			'section'    => 'create_buttons',
			'settings'   => 'create_button_color',
			'priority'   => 1
		) )
	);
	
	// Button Text Color
	$wp_customize->add_setting( 'create_button_text_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_button_text_color', array(
			'label'      => __( 'Button Text Color', 'create' ),
			'section'    => 'create_buttons',
			'settings'   => 'create_button_text_color',
			'priority'   => 2
		) )
	);
	
	
	// -- Custom  CSS Section

	$wp_customize->add_section( 'create_css' , array(
	    'title'     	=> __( 'Custom CSS', 'create' ),
	    'description'	=> __('Add your own custom CSS.', 'create'),
	    'priority'   	=> 59,
	) );
	
	$wp_customize->add_setting( 'create_custom_css' , array(
	    'default'     => __('', 'create'),
	    'type' => 'theme_mod',
	    'transport' => 'refresh',
	    'sanitize_callback'	 => 'wp_kses_post'
	) );

	$wp_customize->add_control( new TTrust_Textarea_Control( $wp_customize, 'create_custom_css', array(
		'label'        => __('CSS', 'create'),
		'section'    => 'create_css',
		'settings'   => 'create_custom_css',
		'priority'   => 62
	) ) );

	// -- Footer Section -----------------------------------

	$wp_customize->add_panel( 'create_footer' , array(
	    'title'      => __( 'Footer', 'port' ),
	    'priority'   => 62.5,
	) );
	
	$wp_customize->add_section( 'create_footer_layout', array(
		'priority'		 => 1,
		'panel'		 => 'create_footer',
	    'title'     	 => __( 'Layout', 'create' )
	) );
	
	$wp_customize->add_setting( 'create_footer_columns' , array(
	    'type' 				=> 'theme_mod',
	    'transport' 		=> 'refresh',
	    'sanitize_callback'	=> 'esc_html'
	) );
	
	$wp_customize->add_control( 'create_footer_columns', array(
		'type'      => 'select',
		'label'     => __( 'Widget Columns', 'create' ),
		'section'   => 'create_footer_layout',
		'settings'  => 'create_footer_columns',
		'choices'   => array(
					'3' => '3',
					'4' => '4',
					'1' => '1',
		            '2' => '2',
					'5' => '5'
		        ),
		'priority'   => 1
	) );
	
	$wp_customize->add_section( 'create_footer_style', array(
		'priority'		 => 2,
		'panel'		 => 'create_footer',
	    'title'     	 => __( 'Style', 'create' )
	) );
	
	// Footer Background Color
	$wp_customize->add_setting( 'create_footer_bg_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_footer_bg_color', array(
			'label'      => __( 'Background Color', 'create' ),
			'section'    => 'create_footer_style',
			'settings'   => 'create_footer_bg_color',
			'priority'   => 1
		) )
	);
	
	// Footer Widget Title Color
	$wp_customize->add_setting( 'create_footer_widget_title_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_footer_widget_title_color', array(
			'label'      => __( 'Widget Title Color', 'create' ),
			'section'    => 'create_footer_style',
			'settings'   => 'create_footer_widget_title_color',
			'priority'   => 1.5
		) )
	);
	
	// Footer Text Color
	$wp_customize->add_setting( 'create_footer_text_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_footer_text_color', array(
			'label'      => __( 'Text Color', 'create' ),
			'section'    => 'create_footer_style',
			'settings'   => 'create_footer_text_color',
			'priority'   => 2
		) )
	);
	
	// Footer Link Color
	$wp_customize->add_setting( 'create_footer_link_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_footer_link_color', array(
			'label'      => __( 'Link Color', 'create' ),
			'section'    => 'create_footer_style',
			'settings'   => 'create_footer_link_color',
			'priority'   => 3
		) )
	);

	// Footer Link Hover Color
	$wp_customize->add_setting( 'create_footer_link_hover_color' );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'create_footer_link_hover_color', array(
			'label'      => __( 'Link Hover Color', 'create' ),
			'section'    => 'create_footer_style',
			'settings'   => 'create_footer_link_hover_color',
			'priority'   => 4
		) )
	);
	
	// Footer Content
	$wp_customize->add_section( 'create_footer_text', array(
		'priority'		 => 2,
		'panel'		 => 'create_footer',
	    'title'     	 => __( 'Content', 'create' )
	) );

	// Left Footer Text (Custom Control)
	$wp_customize->add_setting( 'create_footer_left' , array(
	    'default'     => '',
	    'type' => 'theme_mod',
	    'transport' => 'refresh',
	    'sanitize_callback'	 => 'wp_kses_post'
	) );

	$wp_customize->add_control( new Create_Textarea_Control( $wp_customize, 'footer_left', array(
	    'label'   => __('Primary Footer Text', 'port'),
	    'section' => 'create_footer_text',
	    'settings'   => 'create_footer_left',
	    'priority'   => 71
	) ) );

	// Right Footer Text (Custom Control)
	$wp_customize->add_setting( 'create_footer_right' , array(
	    'default'     => '',
	    'type' => 'theme_mod',
	    'transport' => 'refresh',
	    'sanitize_callback'	 => 'wp_kses_post'
	) );

	$wp_customize->add_control( new Create_Textarea_Control( $wp_customize, 'footer_right', array(
	    'label'   => __('Secondary Footer Text', 'port'),
	    'section' => 'create_footer_text',
	    'settings'   => 'create_footer_right',
	    'priority'   => 72
	) ) );

}
add_action( 'customize_register', 'create_customize_register' );

// Require the gfonts picker class
require_once('google-fonts/gfonts.class.php');


// Instantiate the class
$tt_gfp = new create_gfonts();


