<?php

// ThemeTrust Portfolio CPT Class v1.0 //

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Portfolio_CPT {

	protected $textdomain;
	protected $posts;
	protected $taxonomies;
	protected $version;

	public function __construct( $textdomain )	{
		// Initialize variables
		global $wp_version;

		$this->version		= $wp_version;
		$this->textdomain	= $textdomain;
		$this->posts		= array();
		$this->taxonomies 	= array();

		// Add the action hooks
		add_action( 'init', array( &$this, 'register_projects' ) ); // Register Custom Post Type
		add_action( 'init', array( &$this, 'register_skills' ) );	// Register Associated Taxonomy

		if( $this->version >= 3.8 ) {
			add_action( 'admin_head', array( &$this, 'add_menu_icons_styles' ) ); // Add icon if WP =< 3.8
		}

		add_action( 'after_switch_theme', array( &$this, 'custom_flush_rules' ) );	// Flush rewrite rules
		
		//Show Skills meta box only on page using portfolio-page.php page template		
		if(isset($_GET['post']) || isset($_GET['post_ID'])) {
			$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];		
			$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);		
		  	// check for a template type
			if ($template_file == 'page-portfolio.php') {
				add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );				
			}
		}
		add_action( 'save_post', array( &$this, 'save' ) );
				
	}

	public function project_init() {

		// Define the settings
		$settings = array(
			'labels'			 => array(
	      		'name'				 => __( 'Projects', $this->textdomain ),
	      		'singular_name'		 => __( 'Project', $this->textdomain ),
	      		'add_new'			 => __( 'Add New', $this->textdomain ),
	      		'add_new_item'		 => __( 'Add New Project', $this->textdomain ),
	      		'edit'				 => __( 'Edit', $this->textdomain ),
	      		'edit_item'			 => __( 'Edit Project', $this->textdomain ),
	      		'new_item'			 => __( 'New Project', $this->textdomain ),
	      		'view'				 => __( 'View Project', $this->textdomain ),
	      		'view_item'			 => __( 'View Project', $this->textdomain ),
	      		'search_items'		 => __( 'Search Projects', $this->textdomain ),
	      		'not_found'			 => __( 'No projects found', $this->textdomain ),
	      		'not_found_in_trash' => __( 'No projects found in Trash', $this->textdomain ),
	      		'parent'			 => __( 'Parent Project', $this->textdomain ),
			),
			'public'				 =>true,
			'publicly_queryable'	 => true,
			'show_ui'				 => true,
			'query_var'				 => true,
			'capability_type'		 => 'post',
			'hierarchical'	 		 => false,
			'menu_position'			 => null,
			'menu_icon' 			 => get_template_directory_uri(). '/images/blue-folder-stand.png',
			'taxonomies'			 => array( 'skills' ),
			'supports'				 => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'excerpt', 'page-attributes' ),
			'rewrite'				 => array(
				'slug' => 'project'
			)
		); // End $settings


		// Conditional to set the icon if WP 3.8 <
		if( $this->version >= 3.8 ) {

			$settings['menu_icon'] = '';

		}

		// Store the settings in the post array
		$this->posts['project'] = $settings;

	}

	public function skills_init() {

		$settings = array(
			'labels' 			=> array(
				'name' 				=> __( 'Skills', $this->textdomain ),
				'singular_name' 	=> __( 'Skill', $this->textdomain ),
				'search_items' 		=> __( 'Search Skills', $this->textdomain ),
				'all_items' 		=> __( 'All Skills', $this->textdomain ),
				'parent_item' 		=> __( 'Parent Skill', $this->textdomain ),
				'parent_item_colon'	=> __( 'Parent Skill:', $this->textdomain ),
				'edit_item' 		=> __( 'Edit Skill', $this->textdomain ),
				'update_item' 		=> __( 'Update Skill', $this->textdomain ),
				'add_new_item' 		=> __( 'Add New Skill', $this->textdomain ),
				'new_item_name' 	=> __( 'New Skill Name', $this->textdomain )
			),
			'post_types'  		=> 'project',
			'public'			=> true,
			'hierarchical' 		=> false,
			'rewrite' 			=> array(
				'slug' 	=> 'skill'
			)
		); // End $settings

		$this->taxonomies['skill']['args'] =  $settings;
	}


	public function register_projects() {
		// Loop through the registered posts
		// and register all posts stored in the array
		foreach($this->posts as $key=>$value) {
			register_post_type($key, $value);
		}
	}

	public function register_skills() {
		foreach( $this->taxonomies as $key => $value ) {
			register_taxonomy( $key, $value['args']['post_types'], $value['args'] );
		}
	}

	public function add_menu_icons_styles() {
	?>

		<style>
			#adminmenu .menu-icon-project div.wp-menu-image:before { content: '\f322'; }
		</style>

	<?php
	}

	// Add skills metabox to pages
	public function add_meta_box( $post_type ) {

        $post_types = array( 'page' );     //limit meta box to certain post types

        if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'create_page_skills',
				__( 'Associated Skills', $this->textdomain ),
				array( &$this, 'render_meta_box_content' ),
				$post_type,
				'side'
			);
        } // if()

	} // add_meta_box()

	// Save skills metabox
	public function save( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['portfolio_cpt_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['portfolio_cpt_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'portfolio_cpt_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['create_page_skills'] );

		// Update the meta field.
		update_post_meta( $post_id, 'create_page_skills', $mydata );

	} // save()

	// Render Metabox
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'portfolio_cpt_inner_custom_box', 'portfolio_cpt_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'create_page_skills', true );

		// Display the form, using the current value.
		echo '<label for="create_page_skills">';
		_e( 'Skills', $this->textdomain );
		echo '</label> ';
		echo '<input type="text" id="page_skills" name="create_page_skills"';
                echo ' value="' . esc_attr( $value ) . '" size="25" />';
	}

	// Flush Rules
	public function custom_flush_rules(){

		//defines the post type so the rules can be flushed.
		$this->register_projects();
		$this->register_skills();

		//and flush the rules.
		flush_rewrite_rules();
	}

} // End Portfolio_CPT
?>