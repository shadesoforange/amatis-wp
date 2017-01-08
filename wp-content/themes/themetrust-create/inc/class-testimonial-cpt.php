<?php

// ThemeTrust Testimonial CPT Class v1.0 //

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Testimonial_CPT {

	protected $textdomain;
	protected $posts;
	protected $version;

	public function __construct( $textdomain )	{
		// Initialize variables
		global $wp_version;
		$this->version		= $wp_version;
		$this->textdomain	= $textdomain;
		$this->posts		= array();


		// Add the action hooks
		add_action( 'init', array( &$this, 'register_testimonials' ) );	// Register Associated Taxonomy

		if( $this->version >= 3.8 ) {
			add_action( 'admin_head', array( &$this, 'add_menu_icons_styles' ) ); // Add icon if WP =< 3.8
		}

		add_action( 'after_switch_theme', array( &$this, 'custom_flush_rules' ) );		// Flush rewrite rules
	}

	public function testimonial_init() {

		// Define the settings
		$settings = array(
			'labels' 		=> array(
				'name' 					=> __( 'Testimonials', $this->textdomain),
				'menu_name' 			=> __( 'Testimonials', $this->textdomain),
				'singular_name' 		=> __( 'Testimonial', $this->textdomain),
				'all_items' 			=> __( 'All Testimonials', $this->textdomain),
		        'add_new' 				=> __( 'Add New', $this->textdomain ),
				'add_new_item' 			=> __( 'Add New Testimonial', $this->textdomain ),
				'edit_item' 			=> __( 'Edit Testimonial', $this->textdomain ),
				'new_item' 				=> __( 'New Testimonial', $this->textdomain ),
				'view_item' 			=> __( 'View Testimonial', $this->textdomain ),
				'search_items' 			=> __( 'Search Testimonials', $this->textdomain ),
				'not_found' 			=> __( 'No testimonials found', $this->textdomain ),
				'not_found_in_trash'	=> __( 'No testimonials found in Trash', $this->textdomain )
			),
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menus' 	=> false,
			'menu_position ' 		=> null,
			'menu_icon' 			=> get_template_directory_uri(). '/images/user-icon.png',
			'supports' 				=> array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
			'hierarchical' 			=> false,
			'has_archive' 			=> true,
			'rewrite'				=> array(
				'slug' => 'testimonial'
			)
		); // End $settings

		// Conditional to set the icon if WP 3.8 <
		if( $this->version >= 3.8 ) {

			$settings['menu_icon'] = '';

		}

		// Store the settings in the post array
		$this->posts['testimonial'] = $settings;

	}

	public function register_testimonials() {
		// Loop through the registered posts
		// and register all posts stored in the array
		foreach( $this->posts as $key=>$value ) {
			register_post_type( $key, $value );
		}
	}

	public function add_menu_icons_styles() {
	?>

		<style>
			#adminmenu .menu-icon-testimonial div.wp-menu-image:before { content: '\f110'; }
		</style>

	<?php
	}

	// Flush Rules
	public function custom_flush_rules(){

		//defines the post type so the rules can be flushed.
		$this->register_testimonials();

		//and flush the rules.
		flush_rewrite_rules();
	}

} // End Testimonial_CPT
?>