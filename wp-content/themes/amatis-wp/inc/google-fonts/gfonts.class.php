<?php

/*
* 	Google Fonts Class for Theme Trust Google Font Picker
*   =====================================================
*	Contents:
*	1. Declare Class Variables
*	2. Construct Class, Decode JSON and Set Font Family and Weight Vairables; Add Actions
*	3. Theme Customizer Head CSS
*	4. Register Customizer Controls
*	5. Generate Styles and Link Google Fonts Sheets
*
*/

require_once('customizer_classes.php');

if (!class_exists('create_gfonts')) {

    class create_gfonts {
	
		// Class Variables
		/* @var $localizationDomain string for translation domain */
        private $localizationDomain 	= 'create';
        /* @var $gfonts_file string for the fonts JSON file name */
		private $gfonts_file 			= 'fonts.json';
		/* @var $thispluginurl string for the URL of the plugin (set in constructor) */
        private $thispluginurl 			= '';
        /* @var $thispluginpath string for the path of the plugin (set in constructor) */
        private $thispluginpath 		= '';
        /* @var $tags array that lists all the tag elements as keys and the descriptions as values */
		private $tags 					= array();
		/* @var $all_font_weights array that lists all the possible font weights as keys with the descriptions as values */
		private $all_font_weights 		= array();
		/* @var $list_fonts array for listing all GFont families pulled from the JSON, set in tt_gfp_get_fonts() */
		private $list_fonts        		= array();
		/* @var $font_weights array for listing all GFonts weights pulled from the JSON, set in tt_gfp_get_fonts() */
		private $font_weights 			= array();
		/* @var $fonts_decode string used to temporarily store decoded fonts.json data as string */
		private $fonts_decode			= array();
		/* @var $fonts_decode string used to temporarily store decoded fonts.json data as string */
		private $css_selectors_string 	= '';

		// Class Functions

        // function create_gfonts(){$this->__construct();} // PHP 4

        function __construct(){
            $this->thispluginurl = get_template_directory_uri() . '/' . dirname( plugin_basename(__FILE__) ) . '/';
            $this->thispluginpath = get_template_directory_uri() . '/' . dirname( plugin_basename(__FILE__) ) . '/';

			// Array Values
			$this->tags = array(
					'body'				=> __( 'All Text', $this->localizationDomain),
					'p'					=> __( 'Paragraph', $this->localizationDomain),
					'.site-main h1'				=> __( 'H1', $this->localizationDomain),
					'.site-main h2'				=> __( 'H2', $this->localizationDomain),
					'.site-main h3'				=> __( 'H3', $this->localizationDomain),
					'.site-main h4'				=> __( 'H4', $this->localizationDomain),
					'.site-main h5'				=> __( 'H5', $this->localizationDomain),
					'.site-main h6'				=> __( 'H6', $this->localizationDomain),
					'.body-wrap a' 	    => __( 'Links', $this->localizationDomain),
					'blockquote'		=> __( 'Blockquote', $this->localizationDomain),
					'li'				=> __( 'List Items', $this->localizationDomain),
					'#primary header.main h1.entry-title' => __( 'Page Title', $this->localizationDomain),
					'#primary header.main p.subtitle' => __( 'Page Subtitle', $this->localizationDomain),
					'.main-nav ul li a, .main-nav ul li span' => __( 'Main Menu', $this->localizationDomain),
				);
			$this->all_font_weights = array(
					'100'       => __( 'Ultra Light', $this->localizationDomain ),
					'100italic' => __( 'Ultra Light Italic', $this->localizationDomain ),
					'200'       => __( 'Light', $this->localizationDomain ),
					'200italic' => __( 'Light Italic', $this->localizationDomain ),
					'300'       => __( 'Book', $this->localizationDomain ),
					'300italic' => __( 'Book Italic', $this->localizationDomain ),
					'regular'   => __( 'Regular', $this->localizationDomain ),
					'italic' 	=> __( 'Regular Italic', $this->localizationDomain ),
					'500'       => __( 'Medium', $this->localizationDomain ),
					'500italic' => __( 'Medium Italic', $this->localizationDomain ),
					'600'       => __( 'Semi-Bold', $this->localizationDomain ),
					'600italic' => __( 'Semi-Bold Italic', $this->localizationDomain ),
					'700'       => __( 'Bold', $this->localizationDomain ),
					'700italic' => __( 'Bold Italic', $this->localizationDomain ),
					'800'       => __( 'Extra Bold', $this->localizationDomain ),
					'800italic' => __( 'Extra Bold Italic', $this->localizationDomain ),
					'900'       => __( 'Ultra Bold', $this->localizationDomain ),
					'900italic' => __( 'Ultra Bold Italic', $this->localizationDomain )
				);

            // Actions
			add_action( 'customize_controls_print_scripts', array($this, 'tt_gfp_customizer_head') );
			add_action( 'customize_register', array($this, 'tt_gfp_register_customizer_options') ); // Customizer API register
			add_action( 'wp_head', array($this, 'tt_gfp_generate_styles') ); // Add the GFonts Picker Styles to the Head

        }

		/* TODO: Add the jQuery UI and JS to control the auto-complete -- Menus appear, but something about the position: fixed of the customizer controls prevents it from being displayed. */
		function tt_gfp_customizer_head() {
			?>
			<?php /*<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
			<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
			<script>
			  $(function() {
				    $( ".font-family" ).autocomplete({
				      source: [<?php

						$gfonts_local = $this->list_fonts;

						foreach( $gfonts_local as $gfont ) {
							echo "\"$gfont\",";
						}?>]
				    });
				  });
			</script> */ ?>
		<?php
		}

		// Add the Customizer API settings and controllers
		function tt_gfp_register_customizer_options( $wp_customize ) {

			$json = file_get_contents( get_stylesheet_directory().'/inc/google-fonts/fonts.json' );
            $fonts_decode = json_decode( $json, TRUE );
            $this->list_fonts['default'] = 'Default';

            foreach ( $fonts_decode['items'] as $key => $value ) {
                $item_family 							= $value['family'];
                $this->list_fonts[$item_family]        	= $item_family;
                $this->list_font_weights[$item_family] 	= $value['variants'];
            }

			$wp_customize->add_panel( 'tt_gfp_customizer_section_typography', array(
				'priority'		 => 4,
			    'title'     	 => __( 'Typography', 'create' )
			) );

			$i = 1;

			$tags_local = $this->tags;

			foreach ($tags_local as $key => $value) {
				
				$key_name = strtr($key, array('.' => '', '#' => '', ' ' => '', ',' => ''));
				
				// Tag
				
				$wp_customize->add_section( 'tt_gfp_' . $key_name . '_section', array(
					'title'    => $value,
					'priority' => 1,
					'panel' => 'tt_gfp_customizer_section_typography'
				));
				

				// CSS Selectors
				
				if($key != '#primary header.main h1.entry-title' && $key != '#primary header.main p.subtitle' && $key != '.main-nav ul a') {

				$wp_customize->add_setting( 'tt_gfp_' . $key_name . '_css_selectors', array(
					'default' => '',
					'transport' => 'refresh'
				));

				$wp_customize->add_control( 'tt_gfp_' . $key_name . '_css_selectors',
				array(
					'type'	   => 'text',
					'label'    => __( 'CSS Selectors to Target (optional)', 'create' ),
					'section'  => 'tt_gfp_' . $key_name . '_section',
					'settings' => 'tt_gfp_' . $key_name . '_css_selectors',
					'priority' => "1".$i++,
				) );
				}

				// Font Family

				$wp_customize->add_setting( 'tt_gfp_' . $key_name . '_font_family', array(
					'default' => 'default',
					'transport' => 'refresh'
				));

				$wp_customize->add_control( 'tt_gfp_' . $key_name . '_font_family',
				array(
					'type'	   => 'select',
					'label'    => __( 'Font Family', 'create' ),
					'section'  => 'tt_gfp_' . $key_name . '_section',
					'settings' => 'tt_gfp_' . $key_name . '_font_family',
					'priority' => "1".$i++,
					'choices'  => $this->list_fonts
				) );
				
				// Size
				
				$wp_customize->add_setting( 'tt_gfp_' . $key_name . '_size' , array(
				    'type' 				=> 'theme_mod',
				    'transport' 		=> 'refresh',
				    'sanitize_callback'	=> 'esc_html'
				) );

				$wp_customize->add_control( 'tt_gfp_' . $key_name . '_size', array(
					'label'     => __( 'Font Size', 'create' ),
					'type'      => 'text',
					'section'  => 'tt_gfp_' . $key_name . '_section',
					'settings'  => 'tt_gfp_' . $key_name . '_size',
					'priority'   => "1".$i++,
				) );
				
				// Line Height
				
				$wp_customize->add_setting( 'tt_gfp_' . $key_name . '_line_height' , array(
				    'type' 				=> 'theme_mod',
				    'transport' 		=> 'refresh',
				    'sanitize_callback'	=> 'esc_html'
				) );

				$wp_customize->add_control( 'tt_gfp_' . $key_name . '_line_height', array(
					'label'     => __( 'Line Height', 'create' ),
					'type'      => 'text',
					'section'  => 'tt_gfp_' . $key_name . '_section',
					'settings'  => 'tt_gfp_' . $key_name . '_line_height',
					'priority'   => "1".$i++,
				) );

				// Weight

				$wp_customize->add_setting( 'tt_gfp_' . $key_name . '_font_weight', array(
					'default' => 'regular',
					'transport' => 'refresh'
				));

				$wp_customize->add_control( 'tt_gfp_' . $key_name . '_font_weight', array(
					'type'     => 'select',
					'label'    => __( 'Weight', 'create' ),
					'section'  => 'tt_gfp_' . $key_name . '_section',
					'priority' => "1".$i++,
					'choices'  => $this->all_font_weights
				));

			} // End foreach()

		} // End tt_gfp_register_customizer_options()

		function tt_gfp_generate_styles() {
			$tags_local = $this->tags;
			$font_css_register = array(); // Prevent loading the same stylesheet multiple times

			foreach($tags_local as $key => $value) {
				
				$key_name = strtr($key, array('.' => '', '#' => '', ' ' => '', ',' => ''));
				
				$font_family_temp = get_theme_mod("tt_gfp_" . $key_name . "_font_family");
				$font_size = get_theme_mod("tt_gfp_" . $key_name . "_size");
				$font_line_height = get_theme_mod("tt_gfp_" . $key_name . "_line_height");
				$font_weight_temp = get_theme_mod("tt_gfp_" . $key_name . "_font_weight");
				$font_family = str_replace(" ", "+", $font_family_temp);
				$css_selectors_string = preg_replace('/[^a-z0-9 \#\.\,\-]/i', '', strip_tags(get_theme_mod("tt_gfp_" . $key_name . "_css_selectors"))) ;
				
				if($font_family_temp || $font_size || $font_weight_temp || $font_line_height) {
				// Some string replacement to help with the string functions used in the style generation
				if ($font_weight_temp == 'regular' ) {
					$font_weight = '400';
				} elseif($font_weight_temp == 'italic') {
					$font_weight = '400italic';
				} else {
					$font_weight = $font_weight_temp;
				}
				$requested_font_weight = $font_weight;
				
				// Create array using the possible font weights for the font. Initialize an empty array if list_font_weights is NULL
				$possible_values = !empty($this->list_font_weights[$font_family_temp]) ? $this->list_font_weights[$font_family_temp] : array();

				// Check to see if the requested font style is available to avoid requesting CSS from Google that doesn't exist
				if(!in_array($font_weight, $possible_values)) { $requested_font_weight = ''; }

				$font_string = $font_weight != '400' ? $font_family .':'. $requested_font_weight : $font_family;

				if($font_family != 'default' && !empty($font_family) ) {?>
					<!-- ThemeTrust Google Font Picker -->
					<?php if(!array_key_exists($font_family_temp, $font_css_register)){ ?><link href='//fonts.googleapis.com/css?family=<?php
					if( substr($font_string, -1) == ":" ) {
						echo $font_family;
					} else {
						echo $font_string;
					}
					?>' rel='stylesheet' type='text/css'><?php } ?>
					<?php } // End if() ?>
					
					<style type="text/css"><?php
						if( empty($css_selectors_string) ){ echo $key; }
						elseif ( $key == "body" ) { echo $key . " " . $css_selectors_string; }
						else { echo $css_selectors_string . " " . $key; }
					?> { 
						<?php if($font_family != 'default' && !empty($font_family) ) { ?>
						font-family: <?php echo "'$font_family_temp'"; ?>; 
						<?php }
					if(substr($font_weight,0,3) != '' && substr($font_weight,0,3) != '400' && !empty($font_weight)) {
						?> font-weight: <?php echo substr($font_weight,0,3); ?>;<?php
					}
					if(substr($font_weight,3) == 'italic') {
						?> font-style: <?php echo substr($font_weight,3);?>;<?php
					}  
					if($font_size) {
						?> font-size: <?php echo $font_size; ?>px;<?php
					} 
					if($font_line_height) {
						?> line-height: <?php echo $font_line_height; ?>px;<?php
					}
					?>
					}</style>

				<?php 
				$font_css_register[$font_family_temp] = $font_family_temp; // Update the register to say we've included X.css
				}
			} // End foreach ()

		} // End tt_gfp_generate_css()

    } // End Class
} // End if()
?>