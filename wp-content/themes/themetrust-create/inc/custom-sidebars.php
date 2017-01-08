<?php
/**
 * Custom Sidebar
 * Adds custom widget areas and the ability to select them on pages and posts.
 * 
 */
 
if( ! class_exists( 'tt_sidebar' ) ){
	
	class tt_sidebar{
	
		var $sidebars  = array();
		var $stored    = "";
	    
		// load needed stuff on widget page
		function __construct(){
			$this->stored	= 'tt_sidebars';
			$this->title = __('Custom Widget Area','create');
		    
			add_action('load-widgets.php', array(&$this, 'load_assets') , 5 );
			add_action('widgets_init', array(&$this, 'register_custom_sidebars') , 1000 );
			add_action('wp_ajax_tt_ajax_delete_custom_sidebar', array(&$this, 'delete_sidebar_area') , 1000 );
		}
		
		//load css, js and add hooks to the widget page
		function load_assets(){
			add_action('admin_print_scripts', array(&$this, 'template_add_widget_field') );
			add_action('load-widgets.php', array(&$this, 'add_sidebar_area'), 100);
			
			wp_enqueue_script('tt_sidebar' , get_template_directory_uri() . '/js/sidebar.js');
			wp_enqueue_style( 'tt_sidebar' , get_template_directory_uri() . '/css/sidebar.css');
		}
		
		//widget form template
		function template_add_widget_field(){
			$nonce =  wp_create_nonce ('tt-delete-sidebar');
			$nonce = '<input type="hidden" name="tt-delete-sidebar" value="'.$nonce.'" />';

			echo "\n<script type='text/html' id='tt-add-widget'>";
			echo "\n  <form class='tt-add-widget' method='POST'>";
			echo "\n  <h3>". esc_html($this->title) ."</h3>";
			echo "\n    <span class='input_wrap'><input type='text' value='' placeholder = '".__('Name of new widget area')."' name='tt-add-widget' /></span>";
			echo "\n    <input class='button' type='submit' value='".__('Add Widget Area')."' />";
			echo "\n    ".$nonce;
			echo "\n  </form>";
			echo "\n</script>\n";
		}

		//add sidebar area to the db
		function add_sidebar_area(){
			if(!empty($_POST['tt-add-widget'])){
				$this->sidebars = get_option($this->stored);
				$name = $this->get_name($_POST['tt-add-widget']);

				if(empty($this->sidebars)){
					$this->sidebars = array($name);
				}
				else{
					$this->sidebars = array_merge($this->sidebars, array($name));
				}

				update_option($this->stored, $this->sidebars);
				wp_redirect( admin_url('widgets.php') );
				die();
			}
		}
		
		//delete sidebar area from the db
		function delete_sidebar_area(){
			check_ajax_referer('tt-delete-sidebar');

			if(!empty($_POST['name'])){
				$name = stripslashes($_POST['name']);
				$this->sidebars = get_option($this->stored);

				if(($key = array_search($name, $this->sidebars)) !== false){
					unset($this->sidebars[$key]);
					update_option($this->stored, $this->sidebars);
					echo "sidebar-deleted";
				}
			}

			die();
		}
		
		
		//checks the user submitted name and makes sure that there are no colitions
		function get_name($name){
			if(empty($GLOBALS['wp_registered_sidebars'])) return $name;

			$taken = array();
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ){
				$taken[] = $sidebar['name'];
			}

			if(empty($this->sidebars)) $this->sidebars = array();
			$taken = array_merge($taken, $this->sidebars);

			if(in_array($name, $taken)){
				$counter  = substr($name, -1);  
				$new_name = "";

				if(!is_numeric($counter)){
					$new_name = $name . " 1";
				}
				else{
					$new_name = substr($name, 0, -1) . ((int) $counter + 1);
				}

				$name = $this->get_name($new_name);
			}

			return $name;
		}
		
		
		
		//register custom sidebar areas
		function register_custom_sidebars(){
		
			if(empty($this->sidebars)) $this->sidebars = get_option($this->stored);

			$args = array(
				'before_widget' 	=> '<div id="%1$s" class="widget-box widget %2$s"><div class="inside">',
				'after_widget' 		=> '</div></div>',
				'before_title'  => '<h3 class="widget-title">', 
				'after_title'   => '</h3>'
			);
				
			$args = apply_filters('tt_custom_widget_args', $args);

			if(is_array($this->sidebars)){
				foreach ($this->sidebars as $sidebar){	
					$args['name']  = $sidebar;
					$args['id']  = $sidebar;
					$args['class'] = 'tt-custom';
					register_sidebar($args);
				}
			}
		}
		
	}
}

/**
 * Function that returns all custom made sidebars.
 *
 * @uses get_option()
 * @return array of custom sidebars where key and value are sidebar name
 */

if(!function_exists('get_custom_sidebars')) {
	
	function get_custom_sidebars() {
		$custom_sidebars = get_option('tt_sidebars');
		$formatted_array = array();

		if(is_array($custom_sidebars) && count($custom_sidebars)) {
			foreach ($custom_sidebars as $custom_sidebar) {
				$formatted_array[$custom_sidebar] = $custom_sidebar;
			}
		}

		return $formatted_array;
	}
}

?>