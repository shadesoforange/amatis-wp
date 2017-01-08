<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package create
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function create_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'create_page_menu_args' );


/**
 * Gets the id of the page using the portfolio page template.
 *
 * @return string id
 */
function create_get_portfolio_id() {

    $portfolio_ID = "";
	$pages = get_pages( array(
        'meta_key'      =>  '_wp_page_template',
        'meta_value'    =>  'template-portfolio.php',
        'hierarchical'  =>  0,
        'post-type'     =>  'page',
        'number'        =>  1
    ) );

    foreach($pages as $page){
        $portfolio_ID = $page->ID;
    }

    return $portfolio_ID;

}

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function create_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'create' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'create_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function create_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'create_setup_author' );


/**
 *
 * Get Bootstrap Column
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cs_get_bootstrap' ) ) {
  function create_get_bootstrap( $columns = 1, $device = 'md', $force = false ) {

    global $cs_blog_column;

    $columns  = ( ! empty( $cs_blog_column ) && ! $force ) ? $cs_blog_column : $columns;
    $device   =  $device;

    $bootstrap_columns = array(
      1   => 'col-'. $device .'-12',
      2   => 'col-'. $device .'-6',
      3   => 'col-'. $device .'-4',
      4   => 'col-'. $device .'-3',
      5   => 'col-'. $device .'-five',
      6   => 'col-'. $device .'-2',
      7   => 'col-'. $device .'-seven',
      8   => 'col-'. $device .'-eight',
      9   => 'col-'. $device .'-nine',
      10  => 'col-'. $device .'-ten',
      11  => 'col-'. $device .'-eleven',
      12  => 'col-'. $device .'-1',
    );

    $bootstrap_columns = apply_filters( 'cs_get_bootstrap_columns', $bootstrap_columns );

    return  $bootstrap_columns[$columns];

  }
}

/**
 *
 * Get Bootstrap Col
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cs_get_bootstrap_col' ) ) {
  function cs_get_bootstrap_col( $width = '' ) {
    $width = explode('/', $width);
    $width = ( $width[0] != '1' ) ? $width[0] * floor(12 / $width[1]) : floor(12 / $width[1]);
    return  $width;
  }
}

/**
 *
 * Get Icon
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'cs_icon_class' ) ) {
  function create_icon_class( $icon, $before = false ) {
    if( empty( $icon ) ){ return null; }
    $icon = 'in ' . substr( $icon, 0, 2 ) . ' ' . $icon;
    return $icon;
  }
}

/**
 *
 * Tinymce Modifications
 * @since 1.0.0
 * @version 1.0.0
 *
 */

// Enable font size & font family selects in the editor
if ( ! function_exists( 'create_mce_buttons' ) ) {
	function create_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontselect' ); // Add Font Select
		array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'create_mce_buttons' );

// Customize mce editor font sizes
if ( ! function_exists( 'create_mce_text_sizes' ) ) {
	function create_mce_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'create_mce_text_sizes' );

/**
 *
 * Comments off by default on pages
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'default_comments_off' ) ) {
	function default_comments_off( $data ) {
    	if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
        	$data['comment_status'] = 0;
    	}
    	return $data;
	}
}
add_filter( 'wp_insert_post_data', 'default_comments_off' );

/**
 *
 * Return array of all custom menus
 * @since 1.0.0
 * @version 1.0.0
 *
 */

function customMenus() {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	global $menu_rray;
	$menu_array = array('Select a menu');

	foreach ( $menus as $menu ) {
		$menu_array[$menu->term_id] = $menu->name;
	}
	return $menu_array;
}



?>