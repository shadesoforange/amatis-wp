<?php
/**
 * @category Create
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}


//Header Options

add_action( 'cmb2_init', 'create_register_header_metabox' );
function create_register_header_metabox() {
	
	$prefix = '_create_header_';

	$cmb_header = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Header Options', 'create' ),
		'object_types'  => array( 'project','page','post', 'product' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Hide Header', 'create' ),
		'desc'             => __( 'Choose to hide or show the header on this page.', 'create' ),
		'id'               => $prefix . 'hide',
		'type'             => 'select',
		'options'          => array(
			'no'   => __( 'No', 'create' ),
			'yes' => __( 'Yes', 'create' )
		),
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Transparent Background', 'create' ),
		'desc'             => __( 'Make the site header background transparent on this page.', 'create' ),
		'id'               => $prefix . 'transparent_bg',
		'type'             => 'select',
		'options'          => array(
			'no' => __( 'No', 'create' ),
			'yes'   => __( 'Yes', 'create' )
		),
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Color Scheme', 'create' ),
		'desc'             => __( 'Make the contents of the header light or dark for this page. Only applies if Transparent Background is enabled.', 'create' ),
		'id'               => $prefix . 'color_scheme',
		'type'             => 'select',
		'options'          => array(
			'dark' => __( 'Dark', 'create' ),
			'light'   => __( 'Light', 'create' )
		),
	) );
	
	$cmb_header->add_field( array(
	    'name' => __( 'Menu Color', 'create' ),
	    'id'   => $prefix . 'menu_color',
	    'type' => 'colorpicker',
	    'default'  => ''
	) );
	
	$cmb_header->add_field( array(
	    'name' => __( 'Menu Color Hover', 'create' ),
	    'id'   => $prefix . 'menu_hover_color',
	    'type' => 'colorpicker',
	    'default'  => ''
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Main Menu', 'create' ),
		'desc'             => __( 'Select a different main menu to show on this page.', 'create' ),
		'id'               => $prefix . 'menu_main',
		'type'             => 'select',
		'options'          => customMenus(),
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Mobile Menu', 'create' ),
		'desc'             => __( 'Select a different mobile menu to show on this page.', 'create' ),
		'id'               => $prefix . 'menu_mobile',
		'type'             => 'select',
		'options'          => customMenus(),
	) );
	
	$cmb_header->add_field( array(
		'name'             => __( 'Left Menu (Split Header)', 'create' ),
		'desc'             => __( 'Select a different left menu to show on this page. For use with Split Header layout.', 'create' ),
		'id'               => $prefix . 'menu_left',
		'type'             => 'select',
		'options'          => customMenus(),
	) );
}

//Title Options

add_action( 'cmb2_init', 'create_register_title_metabox' );
function create_register_title_metabox() {

	$prefix = '_create_title_';

	$cmb_title = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Title Area Options', 'create' ),
		'object_types'  => array( 'page', 'project', 'post', 'product'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_title->add_field( array(
		'name'             => __( 'Show Title Area', 'create' ),
		'desc'             => __( 'Hide or show the page title area.', 'create' ),
		'id'               => $prefix . 'show',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'yes' => __( 'Yes', 'create' ),
			'no'   => __( 'No', 'create' ),
		),
	) );
	
	$cmb_title->add_field( array(
		'name'       => __( 'Subtitle Text', 'create' ),
		'desc'       => __( 'This text will be displayed below the title on pages and projects.', 'create' ),
		'id'         => $prefix . 'subtitle',
		'type'       => 'text'
	) );
	
	$cmb_title->add_field( array(
		'name'             => __( 'Hide Text', 'create' ),
		'id'               => $prefix . 'hide_text',
		'type'             => 'select',
		'options'          => array(
			'no'     => __( 'No', 'create' ),
			'yes'   => __( 'Yes', 'create' ),
		),
	) );
	
	$cmb_title->add_field( array(
		'name' => __( 'Title Image', 'create' ),
		'desc' => __( 'This image will appear above the title text.', 'create' ),
		'id'   => $prefix . 'img',
		'type' => 'file',
	) );
	
	$cmb_title->add_field( array(
		'name'             => __( 'Title Alignment', 'create' ),
		'id'               => $prefix . 'alignment',
		'type'             => 'select',
		'options'          => array(
			''     => __( 'Default', 'create' ),
			'center'     => __( 'Center', 'create' ),
			'left'   => __( 'Left', 'create' ),
			'right'   => __( 'Right', 'create' )
		),
	) );

	$cmb_title->add_field( array(
		'name' => __( 'Title Area Height', 'create' ),
		'desc' => __( 'Set the height of the title area in pixels. (ex. 400)', 'create' ),
		'id'   => $prefix . 'area_height',
		'type' => 'text_small'
	) );
	
	$cmb_title->add_field( array(
		'name' => __( 'Title Background Image', 'create' ),
		'desc' => __( 'Upload an image or enter a URL.', 'create' ),
		'id'   => $prefix . 'bg_img',
		'type' => 'file',
	) );
	
	$cmb_title->add_field( array(
		'name'             => __( 'Enable Background Parallax', 'create' ),
		'id'               => $prefix . 'parallax',
		'type'             => 'select',
		'options'          => array(
			'no'     => __( 'No', 'create' ),
			'yes'   => __( 'Yes', 'create' ),
		),
	) );
	
	$cmb_title->add_field( array(
	    'name' => __( 'Title Text Color', 'create' ),
	    'id'   => $prefix . 'color',
	    'type' => 'colorpicker',
	    'default'  => '#191919'
	) );
	
}

//Project Options

add_action( 'cmb2_init', 'create_register_project_metabox' );
function create_register_project_metabox() {
	
	$prefix = '_create_project_';

	$cmb_project = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Project Options', 'create' ),
		'object_types'  => array( 'project', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_project->add_field( array(
		'name'             => __( 'Featured Image Size', 'create' ),
		'desc'             => __( 'Select the size of the featured image for this project.', 'create' ),
		'id'               => $prefix . 'featured_image_size',
		'type'             => 'select',
		'options'          => array(
			'square' => __( 'Default', 'create' ),
			'wide'   => __( 'Wide', 'create' ),
			'tall'     => __( 'Tall', 'create' ),
			'wide_tall'     => __( 'Wide and Tall', 'create' ),
		),
	) );
	
	$cmb_project->add_field( array(
		'name' => __( 'Lightbox Image', 'create' ),
		'desc' => __( 'This image will open if lightbox mode is enabled.', 'create' ),
		'id'   => $prefix . 'lightbox_img',
		'type' => 'file',
	) );
	
	$cmb_project->add_field( array(
		'name' => __( 'Lightbox Video', 'create' ),
		'desc' => __( 'Enter the URL of your video. This video will open if lightbox mode is enabled.', 'create' ),
		'id'   => $prefix . 'lightbox_video',
		'type' => 'text',
	) );
}

//Blog Options

add_action( 'cmb2_init', 'create_register_blog_metabox' );
function create_register_blog_metabox() {
	
	$prefix = '_create_blog_';

	$cmb_blog = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Blog Options', 'create' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_blog->add_field( array(
		'name'             => __( 'Posts per Page', 'create' ),
		'desc'             => __( 'Select the number of posts per page.', 'create' ),
		'id'               => $prefix . 'posts_per_page',
		'type'             => 'text_small',
		'default' => '10'
	) );
	
	$cmb_blog->add_field( array(
		'name'             => __( 'Show Excerpt', 'create' ),
		'desc'             => __( 'Show an excerpt for each post.', 'create' ),
		'id'               => $prefix . 'show_excerpt',
		'type'             => 'select',
		'options'          => array(
			'yes' => __( 'Yes', 'create' ),
			'no'   => __( 'No', 'create' )
		),
	) );
	
	$cmb_blog->add_field( array(
		'name'             => __( 'Featured Image Size', 'create' ),
		'desc'             => __( 'Select the size of the featured image for standard blog layouts.', 'create' ),
		'id'               => $prefix . 'featured_img_size',
		'type'             => 'select',
		'options'          => array(
			'large' => __( 'Large', 'create' ),
			'small'   => __( 'Small', 'create' )
		),
	) );
}

//Post Options

add_action( 'cmb2_init', 'create_register_post_metabox' );
function create_register_post_metabox() {
	
	$prefix = '_create_post_';

	$cmb_post = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Post Options', 'create' ),
		'object_types'  => array( 'post', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_post->add_field( array(
		'name'             => __( 'Featured Image Size', 'create' ),
		'desc'             => __( 'Select the size of the featured image for this post on masonry layouts.', 'create' ),
		'id'               => $prefix . 'featured_image_size',
		'type'             => 'select',
		'options'          => array(
			'landscape' => __( 'Landscape', 'create' ),
			'portrait'   => __( 'Portrait', 'create' ),
			'square'     => __( 'Square', 'create' )
		),
	) );
	
	$cmb_post->add_field( array(
		'name'             => __( 'Show Featured Image', 'create' ),
		'desc'             => __( 'Show featured image on single post.', 'create' ),
		'id'               => $prefix . 'show_featured_img',
		'type'             => 'select',
		'options'          => array(
			'yes' => __( 'Yes', 'create' ),
			'no'   => __( 'No', 'create' )
		),
	) );
	
	$cmb_post->add_field( array(
		'name'             => __( 'Full Width Content', 'create' ),
		'desc'             => __( 'Make the content full width with no sidebar.', 'create' ),
		'id'               => $prefix . 'full_width',
		'type'             => 'select',
		'options'          => array(
			'no'   => __( 'No', 'create' ),
			'yes' => __( 'Yes', 'create' )
		),
	) );
		
}

//Sidebar Options

add_action( 'cmb2_init', 'create_register_sidebar_metabox' );
function create_register_sidebar_metabox() {

	$prefix = '_create_sidebar_';

	$cmb_title = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Sidebar Options', 'create' ),
		'object_types'  => array( 'page', 'post'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_title->add_field( array(
		'name'             => __( 'Custom Widget Area', 'create' ),
		'desc'             => __( 'Select a custom widget area to show in this sidebar.', 'create' ),
		'id'               => $prefix . 'custom_widget_area',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array_merge(array('' => ''), get_custom_sidebars()),
	) );
	
}

//Footer Options

add_action( 'cmb2_init', 'create_register_footer_metabox' );
function create_register_footer_metabox() {

	$prefix = '_create_footer_';

	$cmb_footer = new_cmb2_box( array(
		'id'            => $prefix . 'options',
		'title'         => __( 'Footer Options', 'create' ),
		'object_types'  => array( 'page', 'post', 'project'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true
	) );
	
	$cmb_footer->add_field( array(
		'name'             => __( 'Hide Footer', 'create' ),
		'desc'             => __( 'Choose to hide or show the footer on this page.', 'create' ),
		'id'               => $prefix . 'hide',
		'type'             => 'select',
		'options'          => array(
			'no'   => __( 'No', 'create' ),
			'yes' => __( 'Yes', 'create' )
		),
	) );
	
	$cmb_footer->add_field( array(
		'name'             => __( 'Custom Widget Area', 'create' ),
		'desc'             => __( 'Select a custom widget area to show in this footer.', 'create' ),
		'id'               => $prefix . 'custom_widget_area',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array_merge(array('' => ''), get_custom_sidebars()),
	) );
	
}

