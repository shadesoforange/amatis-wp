<?php

/* Demo Import Page CSS -------------------------------*/

function create_demo_importer_css() {
	wp_enqueue_style( 'demo-importer-admin-css', get_template_directory_uri() . '/inc/demo-import/demo-import.css' );
}
add_action( 'admin_enqueue_scripts', 'create_demo_importer_css' );


/* Demo Import Admin Page -----------------------------*/

if (!function_exists('create_demo_import_page')) {
	function create_demo_import_page() {
		add_theme_page('Demo Import', 'Demo Import', 'manage_options', 'create_demo_import','create_demo_import');
	}
}
add_action('admin_menu', 'create_demo_import_page');


if (!function_exists('create_demo_import')) {
	function create_demo_import() {
		?>
		<div class="wrap">
			<div class="importing-message" style="display:none;">
				<h2 class="demo-import-message-title"><?php _e('Importing demo content...', 'create'); ?></h2>
				<img class="import-spinner" src="<?php echo get_template_directory_uri(); ?>/inc/demo-import/spinner.gif" alt="spinner">
				<p><?php _e('This might take several minutes. Do not leave this page before the import is completed.', 'create'); ?></p>
			</div>

			<div class="import-success" style="display:none;">
				<h2 class="demo-import-message-title"><?php _e('Import Complete!', 'create'); ?></h2>
				<p><?php _e('The demo content was successfully imported.', 'create'); ?></p>
			</div>

			<form class="demo-import" action="?page=create_demo_import" method="post">

				<div class="demo-import-options">
					
					<h2>Demo Import</h2>
					<p><?php _e('Make sure to activate all required and recommended plugins before running the demo import. If you do not plan on using WooCommerce, there is no need to install the WooCommerce plugin.', 'create'); ?></p>

					<?php if ( class_exists('RevSlider')) { ?>
					<input id="rev_slider" type="checkbox" value="ON" name="rev_slider" checked style="visibility: hidden;"><br/>
					<?php } ?>
					
					<input type="hidden" name="action" value="perform_import">
					<input class="button-primary size_big" type="submit" value="Import" id="import_demo_data">

				</div>

			</form>
		</div>
		
		
		<script>
		
			function loadSliders() {
				if (jQuery('#rev_slider').is(':checked')) {
					// Import Sliders
					jQuery('.demo-import-message.sliders').slideDown();
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: {
							action: 'create_demo_import_sliders'
						},
						success: function(data, textStatus, XMLHttpRequest){
							loadOptions();
						},
						error: function(MLHttpRequest, textStatus, errorThrown){

						}
					});

				} else {
					jQuery('.demo-import-message.content').slideUp();
					jQuery('.updated').slideDown();
					import_running = false;
				}
			}
			
			function loadOptions(){
				// Import Customizer Settings
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'create_demo_import_customizer'
					},
					success: function(data, textStatus, XMLHttpRequest){
						loadContent();
					},
					error: function(MLHttpRequest, textStatus, errorThrown){

					}
				});
			}

			
			function loadContent(){
				// Import Content
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'create_demo_import_content'
					},
					success: function(data, textStatus, XMLHttpRequest){
						loadWidgets();
					},
					error: function(MLHttpRequest, textStatus, errorThrown){

					}
				});
			}
			
			function loadWidgets(){
				// Import Content
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'create_demo_import_widgets'
					},
					success: function(data, textStatus, XMLHttpRequest){
						jQuery('.importing-message').slideUp();
						jQuery('.import-success').slideDown();
						import_running = false;
					},
					error: function(MLHttpRequest, textStatus, errorThrown){

					}
				});
			}
			
		
			jQuery(document).ready(function() {
				var import_running = false;
				jQuery('#import_demo_data').click(function() {
					if ( !import_running) {
						import_running = true;
						jQuery("html, body").animate({ scrollTop: 0 }, { duration: 300 });
						jQuery('.demo-import').slideUp(null, function(){
							jQuery('.importing-message').slideDown();
						});
						
						if (jQuery('#rev_slider').is(':checked')) {
							loadSliders();
						}else{
							loadOptions();
						}
						
					}

					return false;
				});
			});
		</script>
		<?php
	}


	/* Import xml File with WordPress Importer -----------------------------*/

	function create_demo_import_content() {
		set_time_limit(0);

		if (!defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);

		require_once(get_template_directory().'/inc/demo-import/wordpress-importer/wordpress-importer.php');

		$wp_import = new WP_Import();
		$wp_import->fetch_attachments = true;

		ob_start();
		$wp_import->import(get_template_directory().'/inc/demo-import/demo-content/create-content.xml');
		ob_end_clean();

		// Set Menu Locations
		$locations = get_theme_mod('nav_menu_locations');
		$menus  = wp_get_nav_menus();

		if(!empty($menus)) {
			foreach($menus as $menu) {
				if(is_object($menu) && $menu->name == 'Main Menu') {
					$locations['primary'] = $menu->term_id;
					$locations['slide_panel_mobile'] = $menu->term_id;
				}
			}
		}
		set_theme_mod('nav_menu_locations', $locations);

		// Set Front Page
		$front_page = get_page_by_title('Home: Agency');

		if(isset($front_page->ID)) {
			update_option('show_on_front', 'page');
			update_option('page_on_front',  $front_page->ID);
		}

		echo 'ok';
		die();

	}
	add_action('wp_ajax_create_demo_import_content', 'create_demo_import_content');



	/* Import Slider Revolution Content --------------------------------------------- */

	function create_demo_import_sliders() {

		if (!class_exists('RevSlider')) { return false; }

		ob_start();
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/home_slider_pro.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/surf.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/full-screen.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/shop-slider.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/home-portfolio.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);

		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/home-slider-agency.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
		
		// Import Slider
		$_FILES["import_file"]["tmp_name"] = get_template_directory().'/inc/demo-import/demo-content/one-page-slider.zip';
		$slider = new RevSlider();
		$response = $slider->importSliderFromPost();
		unset($slider);
	
		ob_end_clean();

		echo 'ok';
		die();

	}
	add_action('wp_ajax_create_demo_import_sliders', 'create_demo_import_sliders');
	
	
	function create_demo_import_customizer() {
		global $wp_customize;
			
		$template	 = get_template();		
		$file = "http://create-theme.s3.amazonaws.com/demo-import/create-options.json";
		$result = wp_remote_get( $file );
		$raw = $result['body'];
		$data = @unserialize( $raw );
		
		// Call the customize_save action.
		do_action( 'customize_save', $wp_customize );
		
		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {
			
			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );
			
			// Save the mod.
			set_theme_mod( $key, $val );
		}
		
		// Call the customize_save_after action.
		do_action( 'customize_save_after', $wp_customize );

		echo 'ok';
		die();
	}
	add_action('wp_ajax_create_demo_import_customizer', 'create_demo_import_customizer');
	
	
	function create_demo_import_widgets() {
		global $wp_registered_sidebars;
		global $wp_registered_widget_controls;
		
		$file = "http://create-theme.s3.amazonaws.com/demo-import/create-widgets.json";
		$result = wp_remote_get( $file );
		$raw = $result['body'];
		$data = json_decode( $raw );
		
		//Get available widgets
		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();
		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes
				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];
			}
		}
		
		// Get all existing widget instances
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		// Begin results
		$results = array();

		// Loop import data's sidebars
		foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets
			// (should not be in export file)
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message = '';
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				$sidebar_message_type = 'error';
				$sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'create' );
			}

			// Result for sidebar
			$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$results[$sidebar_id]['message'] = $sidebar_message;
			$results[$sidebar_id]['widgets'] = array();

			// Loop widgets
			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail = true;
					$widget_message_type = 'error';
					$widget_message = __( 'Site does not support widget', 'create' ); // explain why widget not imported
				}

				$widget = apply_filters( 'widget_settings', $widget ); // object

				$widget = json_decode( json_encode( $widget ), true );

				$widget = apply_filters( 'widget_settings_array', $widget );

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail = true;
							$widget_message_type = 'warning';
							$widget_message = __( 'Widget already exists', 'create' ); // explain why widget not imported

							break;

						}
					}
				}

				// No failure
				if ( ! $fail ) {

					// Add widget instance
					$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

					// Success message
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = __( 'Imported', 'create' );
					} else {
						$widget_message_type = 'warning';
						$widget_message = __( 'Imported to Inactive', 'create' );
					}

				}

				// Result for widget instance
				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget['title'] ) ? $widget['title'] : __( 'No Title', 'widget-importer-exporter' ); // show "No Title" if widget instance is untitled
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

			}
		}
	}
	add_action('wp_ajax_create_demo_import_widgets', 'create_demo_import_widgets');
}