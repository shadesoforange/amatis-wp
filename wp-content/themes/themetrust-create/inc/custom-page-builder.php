<?php

//Add project post type to page builder defaults
function create_siteorgin_defaults($defaults){
	// Post types
	$defaults['post-types'] = array('page', 'post', 'project');
	return $defaults;
}
add_filter( 'siteorigin_panels_settings_defaults', 'create_siteorgin_defaults' );

//Remove the built-in posts carousel widget
function create_panels_widgets( $widgets ){
	unset($widgets['SiteOrigin_Widget_PostCarousel_Widget']);
	return $widgets;
}
add_filter( 'siteorigin_panels_widgets', 'create_panels_widgets', 11);



function create_filter_siteorigin_active_widgets($active){
    $active['so-price-table-widget'] = true;
	$active['so-headline-widget'] = true;
	$active['so-social-media-buttons-widget'] = true;
	$active['so-post-carousel-widget'] = false;
    return $active;
}
add_filter('siteorigin_widgets_active_widgets', 'create_filter_siteorigin_active_widgets');

function create_siteoriginpanels_row_attributes($attr, $row) {
  if(!empty($row['style']['class'])) {
    if(empty($attr['style'])) $attr['style'] = '';
    $attr['style'] .= 'margin-bottom: 0px;';
    $attr['style'] .= 'margin-left: 0px;';
    $attr['style'] .= 'margin-right: 0px;';
  }

  return $attr;
}
add_filter('siteorigin_panels_row_attributes', 'create_siteoriginpanels_row_attributes', 10, 2);


//Add custom row styles
function create_panels_row_background_styles($fields) {
 
  $fields['custom_row_id'] = array(
	     'name'      => __('Custom ID', 'create'),
	     'type'      => 'text',
	     'group'     => 'attributes',
	     'priority'  => 1,
  );

  $fields['equal_column_height'] = array(
        'name'      => __('Equal Column Height', 'create'),
        'type'      => 'select',
        'group'     => 'layout',
        'default'   => 'no',
        'priority'  => 10,
        'options'   => array(
             "no"      => __("No", "create"),
             "yes"   => __("Yes", "create"),
              ),
  );

  $fields['padding_top'] = array(
        'name'      => __('Padding Top', 'create'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 8,
  );
  $fields['padding_bottom'] = array(
        'name'      => __('Padding Bottom', 'create'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 8.5,
  );
  $fields['padding_left'] = array(
        'name'      => __('Padding Left', 'create'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 9,
      );
  $fields['padding_right'] = array(
        'name'      => __('Padding Right', 'create'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 9,
      );
  $fields['background_image'] = array(
        'name'      => __('Background Image', 'create'),
        'group'     => 'design',
        'type'      => 'image',
        'priority'  => 5,
      );
  $fields['background_image_position'] = array(
        'name'      => __('Background Image Position', 'create'),
        'type'      => 'select',
        'group'     => 'design',
        'default'   => 'center top',
        'priority'  => 6,
        'options'   => array(
               "left top"       => __("Left Top", "create"),
               "left center"    => __("Left Center", "create"),
               "left bottom"    => __("Left Bottom", "create"),
               "center top"     => __("Center Top", "create"),
               "center center"  => __("Center Center", "create"),
               "center bottom"  => __("Center Bottom", "create"),
               "right top"      => __("Right Top", "create"),
               "right center"   => __("Right Center", "create"),
               "right bottom"   => __("Right Bottom", "create")
                ),
      );
  $fields['background_image_style'] = array(
        'name'      => __('Background Image Style', 'create'),
        'type'      => 'select',
        'group'     => 'design',
        'default'   => 'cover',
        'priority'  => 6,
        'options'   => array(
             "cover"      => __("Cover", "create"),
             "parallax"   => __("Parallax", "create"),
             "no-repeat"  => __("No Repeat", "create"),
             "repeat"     => __("Repeat", "create"),
             "repeat-x"   => __("Repeat-X", "create"),
             "repeat-y"   => __("Repeat-y", "create"),
              ),
        );
  $fields['border_top'] = array(
        'name'      => __('Border Top Size', 'create'),
        'type'      => 'measurement',
        'group'     => 'design',
        'priority'  => 8,
  );
  $fields['border_top_color'] = array(
        'name'      => __('Border Top Color', 'create'),
        'type'      => 'color',
        'group'     => 'design',
        'priority'  => 8.5,
      );
  $fields['border_bottom'] = array(
        'name'      => __('Border Bottom Size', 'create'),
        'type'      => 'measurement',
        'group'     => 'design',
        'priority'  => 9,
  );
  $fields['border_bottom_color'] = array(
        'name' => __('Border Bottom Color', 'create'),
        'type' => 'color',
        'group' => 'design',
        'priority' => 9.5,
  );
  return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'create_panels_row_background_styles');

function create_panels_remove_row_background_styles($fields) {
 unset( $fields['background_image_attachment'] );
 unset( $fields['background_display'] );
 unset( $fields['padding'] );
 unset( $fields['border_color'] );
 return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'create_panels_remove_row_background_styles');

function create_panels_row_background_styles_attributes($attributes, $args) {

  if(!empty($args['background_image'])) {
    $url = wp_get_attachment_image_src( $args['background_image'], 'full' );
	$unique_class = 'row-'.uniqid();
    if(empty($url) || $url[0] == site_url() ) {
		$bg_img = $args['background_image'];
      } else {
		$bg_img = $url[0];
      }
	  $attributes['style'] .= 'background-image: url(' . $bg_img . ');';
      if(!empty($args['background_image_style'])) {
            switch( $args['background_image_style'] ) {
              case 'no-repeat':
                $attributes['style'] .= 'background-repeat: no-repeat;';
                break;
              case 'repeat':
                $attributes['style'] .= 'background-repeat: repeat;';
                break;
              case 'repeat-x':
                $attributes['style'] .= 'background-repeat: repeat-x;';
                break;
              case 'repeat-y':
                $attributes['style'] .= 'background-repeat: repeat-y;';
                break;
              case 'cover':
                $attributes['style'] .= 'background-size: cover;';
                break;
              case 'parallax':
				//$attributes['style'] = '';
                $attributes['class'][] .= 'parallax-section';
				$attributes['class'][] .= $unique_class;
				$attributes['data-smooth-scrolling'] = 'off';
				$attributes['data-scroll-speed'] = '1.5';
				$attributes['data-parallax-image'] = $bg_img;
				$attributes['data-parallax-id'] = '.'.$unique_class;
                break;
            }
        }
  }
  
  if(!empty($args['padding_top'])) {
    if( function_exists('is_numeric' ) ) {
      if (is_numeric($args['padding_top'])) {
        $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'px; ';
      } else {
         $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'; ';
      }
    } else {
       $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'; ';
    }
  }
  if(!empty($args['padding_bottom'])){
    if( function_exists('is_numeric' ) ) {
      if (is_numeric($args['padding_bottom'])) {
        $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'px; ';
      } else {
        $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'; ';
      }
    } else {
      $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'; ';
    }
 }
 
 if(!empty($args['padding_left'])){
   $attributes['style'] .= 'padding-left: '.esc_attr($args['padding_left']).'; ';
 }
 if(!empty($args['padding_right'])){
   $attributes['style'] .= 'padding-right: '.esc_attr($args['padding_right']).'; ';
 }
 if(!empty($args['border_top'])){
   $attributes['style'] .= 'border-top: '.esc_attr($args['border_top']).' solid; ';
 }
 if(!empty($args['border_top_color'])){
   $attributes['style'] .= 'border-top-color: '.$args['border_top_color'].'; ';
 }
 if(!empty($args['border_bottom'])){
   $attributes['style'] .= 'border-bottom: '.esc_attr($args['border_bottom']).' solid; ';
 }
  if(!empty($args['border_bottom_color'])){
   $attributes['style'] .= 'border-bottom-color: '.$args['border_bottom_color'].'; ';
 }

if(!empty($args['custom_row_id'])){
   $attributes['data-row-id'] = $args['custom_row_id'];
 }

if(!empty($args['equal_column_height'])){
	if($args['equal_column_height']=="yes"){
   		$attributes['class'][] = 'equal-column-height';
	}
 }

  return $attributes;
}
add_filter('siteorigin_panels_row_style_attributes', 'create_panels_row_background_styles_attributes', 10, 2);





//////////////////////////////////////////////
//Prebuilt Layouts
//////////////////////////////////////////////

function create_prebuilt_layouts($layouts){
    $layouts['home-agency'] = array(
        'name' => __('Home: Agency', 'create'),  
        'description' => __('Layout for demo Home: Agency page.', 'create'),
        'widgets' => 
		  array (
		    0 => 
		    array (
		      'title' => '',
		      'text' => '[rev_slider alias="home-slider-agency"]',
		      'filter' => false,
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Text',
		        'raw' => false,
		        'grid' => 0,
		        'cell' => 0,
		        'id' => 0,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    1 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h1 style="text-align: center;"><span style="color: #bb9f7c;">Create</span> is a multi-purpose WordPress theme that gives you the power to create many different styles of websites. </h1>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 1,
		        'cell' => 1,
		        'id' => 1,
		        'style' => 
		        array (
		          'widget_css' => 'line-height: 3.8em !important;',
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    2 => 
		    array (
		      'title' => '',
		      'show_filter' => 'no',
		      'filter_alignment' => 'center',
		      'count' => '4',
		      'thumb_proportions' => 'landscape',
		      'layout' => 'masonry without gutter',
		      'columns' => '4',
		      'skills' => 
		      array (
		        'illustration' => '',
		        'mobile' => '',
		        'motion' => '',
		        'photography' => '',
		        'web' => '',
		      ),
		      'orderby' => 'date',
		      'order' => 'DESC',
		      'hover_effect' => 'effect-1',
		      'hover_color' => '#1aafaf',
		      'hover_text_color' => '',
		      'show_skills' => 'yes',
		      'show_load_more' => 'no',
		      'enable_lightbox' => 'no',
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Portfolio',
		        'raw' => false,
		        'grid' => 2,
		        'cell' => 0,
		        'id' => 2,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    3 => 
		    array (
		      'features' => 
		      array (
		        0 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-list-alt',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Page Builder',
		          'text' => 'Create comes with a page builder that allows you to create pages exactly how you want. ',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        1 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-tablet',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Responsive Layout',
		          'text' => 'Create is a responsive theme. Its layout adjusts to look great on any screen size or device.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        2 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-eye',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Retina Ready',
		          'text' => 'Built with the latest technology in mind, rest assured that your site will look crisp on retina displays.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        3 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-tasks',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Multiple Headers',
		          'text' => 'Packed with 5 different header layouts, you can use this theme to create many different styles of websites.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        4 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-cog',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Powerful Options',
		          'text' => 'Create comes with tons of options built right into the WordPress Customizer. So you can give your site a unique look.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        5 => 
		        array (
		          'container_color' => '',
		          'icon' => 'fontawesome-th-list',
		          'icon_color' => '#bb9f7c',
		          'icon_image' => '0',
		          'title' => 'Built-in Mega Menu',
		          'text' => 'There is a mega menu built in for those sites that have a lot of pages. You can easily add icons to menu items.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		      ),
		      'container_shape' => 'round',
		      'container_size' => 25,
		      'icon_size' => 25,
		      'per_row' => 3,
		      'responsive' => true,
		      'title_link' => false,
		      'icon_link' => false,
		      'new_window' => false,
		      'panels_info' => 
		      array (
		        'class' => 'SiteOrigin_Widget_Features_Widget',
		        'raw' => false,
		        'grid' => 3,
		        'cell' => 0,
		        'id' => 3,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    4 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h1>Our <span style="color: #ffffff;">customers\'</span> happiness is what matters to us.</h1>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 4,
		        'cell' => 0,
		        'id' => 4,
		        'style' => 
		        array (
		          'class' => 'v-center',
		          'padding' => '50px',
		          'background' => '#232323',
		          'background_display' => 'tile',
		          'font_color' => '#9e9e9e',
		        ),
		      ),
		    ),
		    5 => 
		    array (
		      'title' => '',
		      'count' => '4',
		      'layout' => 'carousel',
		      'columns' => '1',
		      'alignment' => 'center',
		      'order' => 'rand',
		      'carousel-nav-color' => '#ffffff',
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Testimonials',
		        'raw' => false,
		        'grid' => 4,
		        'cell' => 1,
		        'id' => 5,
		        'style' => 
		        array (
		          'padding' => '50px',
		          'background' => '#1aafaf',
		          'background_display' => 'tile',
		          'font_color' => '#ffffff',
		        ),
		      ),
		    ),
		    6 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h1 style="text-align: center;">We\'ve been known to <span style="color: #000000;">share</span> our thoughts.</h1>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 5,
		        'cell' => 0,
		        'id' => 6,
		        'style' => 
		        array (
		          'class' => 'v-center',
		          'padding' => '0px',
		          'background' => '#ffffff',
		          'background_display' => 'tile',
		          'font_color' => '#878787',
		        ),
		      ),
		    ),
		    7 => 
		    array (
		      'title' => '',
		      'count' => '9',
		      'layout' => 'carousel',
		      'columns' => '3',
		      'alignment' => 'left',
		      'orderby' => 'date',
		      'order' => 'DESC',
		      'show_excerpt' => 'no',
		      'carousel-nav-color' => '#1aafaf',
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Blog',
		        'raw' => false,
		        'grid' => 6,
		        'cell' => 0,
		        'id' => 7,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    8 => 
		    array (
		      'type' => 'html',
		      'title' => '',
		      'text' => '<h3 style="text-align: center;">Unlimited Parallax Sections</h3>
		<p style="text-align: center;">Create unlimited parallax sections for your pages. It\'s as easy as adding a new page builder row, uploading an image, and choosing "parallax from the drop down.</p>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 7,
		        'cell' => 1,
		        'id' => 8,
		        'style' => 
		        array (
		          'padding' => '0px',
		          'background_display' => 'tile',
		          'font_color' => '#ffffff',
		        ),
		      ),
		    ),
		    9 => 
		    array (
		      'text' => 'LEARN MORE',
		      'url' => '#',
		      'new_window' => false,
		      'button_icon' => 
		      array (
		        'icon_selected' => '',
		        'icon_color' => '',
		        'icon' => '0',
		      ),
		      'design' => 
		      array (
		        'align' => 'center',
		        'theme' => 'flat',
		        'button_color' => '#bb9f7c',
		        'text_color' => '#ffffff',
		        'hover' => true,
		        'font_size' => '1',
		        'rounding' => '0.25',
		        'padding' => '1',
		      ),
		      'attributes' => 
		      array (
		        'id' => '',
		        'title' => '',
		        'onclick' => '',
		      ),
		      'panels_info' => 
		      array (
		        'class' => 'SiteOrigin_Widget_Button_Widget',
		        'raw' => false,
		        'grid' => 7,
		        'cell' => 1,
		        'id' => 9,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    10 => 
		    array (
		      'title' => 'Use Create to build your next site.',
		      'sub_title' => false,
		      'design' => 
		      array (
		        'background_color' => '#1aafaf',
		        'border_color' => '',
		        'button_align' => 'right',
		      ),
		      'button' => 
		      array (
		        'text' => 'BUY CREATE NOW',
		        'url' => 'http://themetrust.com/themes/create',
		        'button_icon' => 
		        array (
		          'icon_selected' => '',
		          'icon_color' => '',
		          'icon' => '0',
		        ),
		        'design' => 
		        array (
		          'theme' => 'wire',
		          'button_color' => '#ffffff',
		          'text_color' => '#1aafaf',
		          'hover' => true,
		          'font_size' => '1',
		          'rounding' => '0.25',
		          'padding' => '1',
		        ),
		        'attributes' => 
		        array (
		          'id' => '',
		          'title' => '',
		          'onclick' => '',
		        ),
		      ),
		      'panels_info' => 
		      array (
		        'class' => 'SiteOrigin_Widget_Cta_Widget',
		        'raw' => false,
		        'grid' => 8,
		        'cell' => 0,
		        'id' => 10,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		          'font_color' => '#ffffff',
		        ),
		      ),
		    ),
		  ),
		  'grids' => 
		  array (
		    0 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'row_stretch' => 'full-stretched',
		        'equal_column_height' => 'no',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    1 => 
		    array (
		      'cells' => 3,
		      'style' => 
		      array (
		        'row_stretch' => 'full',
		        'equal_column_height' => 'no',
		        'padding_top' => '70px',
		        'padding_bottom' => '70px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    2 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full-stretched',
		        'background' => '#f9f8f4',
		        'equal_column_height' => 'no',
		        'padding_top' => '0px',
		        'padding_bottom' => '0px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    3 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'equal_column_height' => 'no',
		        'padding_top' => '70px',
		        'padding_bottom' => '60px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    4 => 
		    array (
		      'cells' => 2,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'gutter' => '0px',
		        'row_stretch' => 'full-stretched',
		        'equal_column_height' => 'yes',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    5 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'gutter' => '0px',
		        'row_stretch' => 'full',
		        'equal_column_height' => 'no',
		        'padding_top' => '50px',
		        'padding_bottom' => '50px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    6 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'gutter' => '0px',
		        'row_stretch' => 'full',
		        'equal_column_height' => 'no',
		        'padding_top' => '0px',
		        'padding_bottom' => '50px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    7 => 
		    array (
		      'cells' => 3,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full',
		        'equal_column_height' => 'no',
		        'padding_top' => '140px',
		        'padding_bottom' => '140px',
		        'background_image' => 957,
		        'background_image_position' => 'left top',
		        'background_image_style' => 'parallax',
		      ),
		    ),
		    8 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full',
		        'background' => '#1aafaf',
		        'equal_column_height' => 'no',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		  ),
		  'grid_cells' => 
		  array (
		    0 => 
		    array (
		      'grid' => 0,
		      'weight' => 1,
		    ),
		    1 => 
		    array (
		      'grid' => 1,
		      'weight' => 0.10471092077087999772100346262959646992385387420654296875,
		    ),
		    2 => 
		    array (
		      'grid' => 1,
		      'weight' => 0.7745182012847899866159195880754850804805755615234375,
		    ),
		    3 => 
		    array (
		      'grid' => 1,
		      'weight' => 0.120770877944330001785289141480461694300174713134765625,
		    ),
		    4 => 
		    array (
		      'grid' => 2,
		      'weight' => 1,
		    ),
		    5 => 
		    array (
		      'grid' => 3,
		      'weight' => 1,
		    ),
		    6 => 
		    array (
		      'grid' => 4,
		      'weight' => 0.58580182951797998835985481491661630570888519287109375,
		    ),
		    7 => 
		    array (
		      'grid' => 4,
		      'weight' => 0.41419817048202001164014518508338369429111480712890625,
		    ),
		    8 => 
		    array (
		      'grid' => 5,
		      'weight' => 1,
		    ),
		    9 => 
		    array (
		      'grid' => 6,
		      'weight' => 1,
		    ),
		    10 => 
		    array (
		      'grid' => 7,
		      'weight' => 0.204186413902050001301091697314404882490634918212890625,
		    ),
		    11 => 
		    array (
		      'grid' => 7,
		      'weight' => 0.59162717219589999739781660537119023501873016357421875,
		    ),
		    12 => 
		    array (
		      'grid' => 7,
		      'weight' => 0.204186413902050001301091697314404882490634918212890625,
		    ),
		    13 => 
		    array (
		      'grid' => 8,
		      'weight' => 1,
		    ),
		  ),
    );

	$layouts['home-pro'] = array(
		'name' => __('Home: Professional', 'create'),
		'description' => __('Layout for demo Home: Professional page.', 'create'),
        'widgets' => 
		  array (
		    0 => 
		    array (
		      'title' => '',
		      'text' => '[rev_slider alias="home_slider_pro"]',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Text',
		        'grid' => 0,
		        'cell' => 0,
		        'id' => 0,
		        'style' => 
		        array (
		          'background_image_attachment' => false,
		          'background_display' => 'tile',
		        ),
		      ),
		      'filter' => false,
		    ),
		    1 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">THE ONLY THEME YOU NEED</span></h3><p style="text-align: center;">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 1,
		        'cell' => 0,
		        'id' => 1,
		        'style' => 
		        array (
		          'class' => 'v-center',
		          'widget_css' => '	',
		          'padding' => '70px',
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    2 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<p><img class="alignright size-full wp-image-520" src="http://create.themetrust.com/wp-content/uploads/2015/07/macbook-pro-cropped-right.jpg" alt="macbook-pro-cropped-right" width="1293" height="951" /></p>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 1,
		        'cell' => 1,
		        'id' => 2,
		        'style' => 
		        array (
		          'class' => 'v-center',
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    3 => 
		    array (
		      'features' => 
		      array (
		        0 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-layers',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Page Builder',
		          'text' => 'Create comes with a page builder that allows you to create pages exactly how you want. ',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        1 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-mobile',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Responsive Layout',
		          'text' => 'Create is a responsive theme. Its layout adjusts to look great on any screen size or device.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        2 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-laptop',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Retina Ready',
		          'text' => 'Built with the latest technology in mind, rest assured that your site will look crisp on retina displays.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        3 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-browser',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Multiple Headers',
		          'text' => 'Packed with 5 different header layouts, you can use this theme to create many different styles of websites.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        4 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-gears',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Powerful Options',
		          'text' => 'Create comes with tons of options built right into the WordPress Customizer. So you can give your site a unique look.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		        5 => 
		        array (
		          'container_color' => '',
		          'icon' => 'elegantline-genius',
		          'icon_color' => '#ffffff',
		          'icon_image' => '0',
		          'title' => 'Built-in Mega Menu',
		          'text' => 'There is a mega menu built in for those sites that have a lot of pages. You can easily add icons to menu items.',
		          'more_text' => '',
		          'more_url' => '',
		        ),
		      ),
		      'container_shape' => 'round',
		      'container_size' => 25,
		      'icon_size' => 25,
		      'per_row' => 3,
		      'responsive' => true,
		      'title_link' => false,
		      'icon_link' => false,
		      'new_window' => false,
		      'panels_info' => 
		      array (
		        'class' => 'SiteOrigin_Widget_Features_Widget',
		        'raw' => false,
		        'grid' => 2,
		        'cell' => 0,
		        'id' => 3,
		        'style' => 
		        array (
		          'class' => 'left',
		          'widget_css' => 'p{opacity: .5}',
		          'padding' => '50px',
		          'background_display' => 'tile',
		          'font_color' => '#ffffff',
		        ),
		      ),
		    ),
		    4 => 
		    array (
		      'title' => 'Some of our latest work.',
		      'sub_title' => '',
		      'design' => 
		      array (
		        'background_color' => '#ba9e78',
		        'border_color' => false,
		        'button_align' => 'right',
		      ),
		      'button' => 
		      array (
		        'text' => 'View More',
		        'url' => '',
		        'button_icon' => 
		        array (
		          'icon_selected' => '',
		          'icon_color' => false,
		          'icon' => 0,
		        ),
		        'design' => 
		        array (
		          'theme' => 'flat',
		          'button_color' => '#ffffff',
		          'text_color' => '#ba9e78',
		          'hover' => true,
		          'font_size' => '1',
		          'rounding' => '0.25',
		          'padding' => '1',
		          'align' => 'center',
		        ),
		        'attributes' => 
		        array (
		          'id' => '',
		          'title' => '',
		          'onclick' => '',
		        ),
		        'new_window' => false,
		      ),
		      'panels_info' => 
		      array (
		        'class' => 'SiteOrigin_Widget_Cta_Widget',
		        'raw' => false,
		        'grid' => 3,
		        'cell' => 0,
		        'id' => 4,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		          'font_color' => '#ffffff',
		        ),
		      ),
		    ),
		    5 => 
		    array (
		      'title' => '',
		      'show_filter' => 'no',
		      'filter_alignment' => 'center',
		      'count' => '3',
		      'thumb_proportions' => 'landscape',
		      'layout' => 'rows without gutter',
		      'columns' => '3',
		      'orderby' => 'date',
		      'order' => 'DESC',
		      'hover_effect' => 'effect-1',
		      'hover_color' => '',
		      'hover_text_color' => '',
		      'show_skills' => 'yes',
		      'show_load_more' => 'no',
		      'enable_lightbox' => 'no',
		      'skills' => 
		      array (
		      ),
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Portfolio',
		        'raw' => false,
		        'grid' => 4,
		        'cell' => 0,
		        'id' => 5,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    6 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">FROM THE BLOG</span></h3><p style="text-align: center;">This is a blog widget that you can add anywhere. Display recent posts as a grid or carousel.</p>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 5,
		        'cell' => 0,
		        'id' => 6,
		        'style' => 
		        array (
		          'padding' => '50px',
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    7 => 
		    array (
		      'title' => '',
		      'count' => '3',
		      'layout' => 'grid',
		      'columns' => '3',
		      'alignment' => 'left',
		      'orderby' => 'date',
		      'order' => 'DESC',
		      'show_excerpt' => 'yes',
		      'carousel-nav-color' => '',
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Blog',
		        'raw' => false,
		        'grid' => 5,
		        'cell' => 0,
		        'id' => 7,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    8 => 
		    array (
		      'type' => 'visual',
		      'title' => '',
		      'text' => '<h3 style="text-align: center;">OUR CUSTOMERS <span style="color: #ba9e78;"><strong>LOVE</strong></span> US</h3>',
		      'filter' => '1',
		      'panels_info' => 
		      array (
		        'class' => 'WP_Widget_Black_Studio_TinyMCE',
		        'raw' => false,
		        'grid' => 6,
		        'cell' => 1,
		        'id' => 8,
		        'style' => 
		        array (
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		    9 => 
		    array (
		      'title' => '',
		      'count' => '3',
		      'layout' => 'carousel',
		      'columns' => '1',
		      'alignment' => 'center',
		      'order' => 'menu_order',
		      'carousel-nav-color' => '#ba9e78',
		      'panels_info' => 
		      array (
		        'class' => 'TTrust_Testimonials',
		        'raw' => false,
		        'grid' => 6,
		        'cell' => 1,
		        'id' => 9,
		        'style' => 
		        array (
		          'padding' => '30pxpx',
		          'background_display' => 'tile',
		        ),
		      ),
		    ),
		  ),
		  'grids' => 
		  array (
		    0 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'row_stretch' => 'full-stretched',
		        'equal_column_height' => 'no',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    1 => 
		    array (
		      'cells' => 2,
		      'style' => 
		      array (
		        'gutter' => '0px',
		        'row_stretch' => 'full-stretched',
		        'equal_column_height' => 'yes',
		        'padding_top' => '60px',
		        'padding_bottom' => '60px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    2 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full',
		        'equal_column_height' => 'no',
		        'padding_top' => '140px',
		        'padding_bottom' => '140px',
		        'background_image' => 544,
		        'background_image_position' => 'left top',
		        'background_image_style' => 'parallax',
		      ),
		    ),
		    3 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full',
		        'background' => '#ba9e78',
		        'equal_column_height' => 'no',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    4 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'bottom_margin' => '0px',
		        'row_stretch' => 'full-stretched',
		        'background' => '#f9f8f4',
		        'equal_column_height' => 'no',
		        'padding_top' => '0px',
		        'padding_bottom' => '0px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    5 => 
		    array (
		      'cells' => 1,
		      'style' => 
		      array (
		        'equal_column_height' => 'no',
		        'padding_top' => '40px',
		        'padding_bottom' => '40px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'cover',
		      ),
		    ),
		    6 => 
		    array (
		      'cells' => 3,
		      'style' => 
		      array (
		        'row_stretch' => 'full',
		        'background' => '#f4f4f4',
		        'equal_column_height' => 'no',
		        'padding_top' => '100px',
		        'padding_bottom' => '100px',
		        'background_image_position' => 'left top',
		        'background_image_style' => 'parallax',
		      ),
		    ),
		  ),
		  'grid_cells' => 
		  array (
		    0 => 
		    array (
		      'grid' => 0,
		      'weight' => 1,
		    ),
		    1 => 
		    array (
		      'grid' => 1,
		      'weight' => 0.5,
		    ),
		    2 => 
		    array (
		      'grid' => 1,
		      'weight' => 0.5,
		    ),
		    3 => 
		    array (
		      'grid' => 2,
		      'weight' => 1,
		    ),
		    4 => 
		    array (
		      'grid' => 3,
		      'weight' => 1,
		    ),
		    5 => 
		    array (
		      'grid' => 4,
		      'weight' => 1,
		    ),
		    6 => 
		    array (
		      'grid' => 5,
		      'weight' => 1,
		    ),
		    7 => 
		    array (
		      'grid' => 6,
		      'weight' => 0.278206541712607224869913125075981952250003814697265625,
		    ),
		    8 => 
		    array (
		      'grid' => 6,
		      'weight' => 0.44358691657478555026017374984803609549999237060546875,
		    ),
		    9 => 
		    array (
		      'grid' => 6,
		      'weight' => 0.278206541712607224869913125075981952250003814697265625,
		    ),
		  ),
		);
		
		$layouts['home-full'] = array(
			'name' => __('Home: Fullscreen Slider', 'create'),
			'description' => __('Layout for demo Home: Fullscreen Slider page.', 'create'),
	        'widgets' => 
			  array (
			    0 => 
			    array (
			      'title' => '',
			      'text' => '[rev_slider alias="full-screen"]',
			      'panels_info' => 
			      array (
			        'class' => 'WP_Widget_Text',
			        'grid' => 0,
			        'cell' => 0,
			        'id' => 0,
			        'style' => 
			        array (
			          'background_image_attachment' => false,
			          'background_display' => 'tile',
			        ),
			      ),
			      'filter' => false,
			    ),
			    1 => 
			    array (
			      'features' => 
			      array (
			        0 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-layers',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Page Builder',
			          'text' => 'Create comes with a page builder that allows you to create pages exactly how you want. ',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        1 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-mobile',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Responsive Layout',
			          'text' => 'Create is a responsive theme. Its layout adjusts to look great on any screen size or device.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        2 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-laptop',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Retina Ready',
			          'text' => 'Built with the latest technology in mind, rest assured that your site will look crisp on retina displays.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			      ),
			      'container_shape' => 'round',
			      'container_size' => 25,
			      'icon_size' => 25,
			      'per_row' => 1,
			      'responsive' => true,
			      'title_link' => false,
			      'icon_link' => false,
			      'new_window' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Features_Widget',
			        'raw' => false,
			        'grid' => 1,
			        'cell' => 0,
			        'id' => 1,
			        'style' => 
			        array (
			          'class' => 'right v-center',
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    2 => 
			    array (
			      'image_fallback' => '',
			      'image' => 159,
			      'size' => 'full',
			      'title' => '',
			      'alt' => '',
			      'url' => '',
			      'bound' => true,
			      'new_window' => false,
			      'full_width' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Image_Widget',
			        'raw' => false,
			        'grid' => 1,
			        'cell' => 1,
			        'id' => 2,
			        'style' => 
			        array (
			          'class' => 'v-center',
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    3 => 
			    array (
			      'features' => 
			      array (
			        0 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-browser',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Multiple Headers',
			          'text' => 'Packed with 5 different header layouts, you can use this theme to create many different styles of websites.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        1 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-gears',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Powerful Options',
			          'text' => 'Create comes with tons of options built right into the WordPress Customizer. So you can give your site a unique look.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        2 => 
			        array (
			          'container_color' => false,
			          'icon' => 'elegantline-genius',
			          'icon_color' => '#98643c',
			          'icon_image' => 0,
			          'title' => 'Built-in Mega Menu',
			          'text' => 'There is a mega menu built in for those sites that have a lot of pages. You can easily add icons to menu items.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			      ),
			      'container_shape' => 'round',
			      'container_size' => 25,
			      'icon_size' => 25,
			      'per_row' => 1,
			      'responsive' => true,
			      'title_link' => false,
			      'icon_link' => false,
			      'new_window' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Features_Widget',
			        'raw' => false,
			        'grid' => 1,
			        'cell' => 2,
			        'id' => 3,
			        'style' => 
			        array (
			          'class' => 'left v-center',
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    4 => 
			    array (
			      'type' => 'visual',
			      'title' => '',
			      'text' => '<h3 style="text-align: center;">Our Latest Work</h3><p style="text-align: center;"><span style="color: #999999;">Create unlimited parallax sections for your pages. It\'s as easy as adding a new page build row, uploading and image, and choosing "parallax from the drop down.</span></p>',
			      'filter' => '1',
			      'panels_info' => 
			      array (
			        'class' => 'WP_Widget_Black_Studio_TinyMCE',
			        'raw' => false,
			        'grid' => 2,
			        'cell' => 1,
			        'id' => 4,
			        'style' => 
			        array (
			          'padding' => '0px',
			          'background_display' => 'tile',
			          'font_color' => '#ffffff',
			        ),
			      ),
			    ),
			    5 => 
			    array (
			      'text' => 'VIEW MORE',
			      'url' => '#',
			      'new_window' => false,
			      'button_icon' => 
			      array (
			        'icon_selected' => '',
			        'icon_color' => '',
			        'icon' => '0',
			      ),
			      'design' => 
			      array (
			        'align' => 'center',
			        'theme' => 'flat',
			        'button_color' => '#bb9f7c',
			        'text_color' => '#ffffff',
			        'hover' => true,
			        'font_size' => '1',
			        'rounding' => '0.25',
			        'padding' => '1',
			      ),
			      'attributes' => 
			      array (
			        'id' => '',
			        'title' => '',
			        'onclick' => '',
			      ),
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Button_Widget',
			        'raw' => false,
			        'grid' => 2,
			        'cell' => 1,
			        'id' => 5,
			        'style' => 
			        array (
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    6 => 
			    array (
			      'title' => '',
			      'show_filter' => 'no',
			      'filter_alignment' => 'center',
			      'count' => '3',
			      'thumb_proportions' => 'square',
			      'layout' => 'rows without gutter',
			      'columns' => '3',
			      'orderby' => 'date',
			      'order' => 'DESC',
			      'hover_effect' => 'effect-1',
			      'hover_color' => '',
			      'hover_text_color' => '',
			      'show_skills' => 'no',
			      'show_load_more' => 'no',
			      'enable_lightbox' => 'no',
			      'skills' => 
			      array (
			      ),
			      'panels_info' => 
			      array (
			        'class' => 'TTrust_Portfolio',
			        'raw' => false,
			        'grid' => 3,
			        'cell' => 0,
			        'id' => 6,
			        'style' => 
			        array (
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    7 => 
			    array (
			      'headline' => 
			      array (
			        'text' => 'What People Are Saying',
			        'font' => 'default',
			        'color' => '#000000',
			        'align' => 'center',
			      ),
			      'sub_headline' => 
			      array (
			        'text' => 'Display testimonials as a slider or grid.',
			        'font' => 'default',
			        'color' => '#aaaaaa',
			        'align' => 'center',
			      ),
			      'divider' => 
			      array (
			        'style' => 'solid',
			        'weight' => 'thin',
			        'color' => '#bc9f7a',
			      ),
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Headline_Widget',
			        'raw' => false,
			        'grid' => 4,
			        'cell' => 0,
			        'id' => 7,
			        'style' => 
			        array (
			          'padding' => '20px',
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    8 => 
			    array (
			      'title' => '',
			      'count' => '3',
			      'layout' => 'grid',
			      'columns' => '3',
			      'alignment' => 'center',
			      'order' => 'rand',
			      'carousel-nav-color' => '',
			      'panels_info' => 
			      array (
			        'class' => 'TTrust_Testimonials',
			        'raw' => false,
			        'grid' => 4,
			        'cell' => 0,
			        'id' => 8,
			        'style' => 
			        array (
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    9 => 
			    array (
			      'features' => 
			      array (
			        0 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-list-alt',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'Multiple Site Layouts',
			          'text' => 'Choose from full-width, boxed, side header, top header, and more.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        1 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-desktop',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'One-page Option',
			          'text' => 'Create a navigation that scrolls to different sections on a single page.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			      ),
			      'container_shape' => 'round',
			      'container_size' => 25,
			      'icon_size' => 25,
			      'per_row' => 1,
			      'responsive' => true,
			      'title_link' => false,
			      'icon_link' => false,
			      'new_window' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Features_Widget',
			        'raw' => false,
			        'grid' => 5,
			        'cell' => 0,
			        'id' => 9,
			        'style' => 
			        array (
			          'class' => 'left',
			          'padding' => '20%',
			          'background' => '#ba9e78',
			          'background_display' => 'tile',
			          'font_color' => '#ffffff',
			        ),
			      ),
			    ),
			    10 => 
			    array (
			      'features' => 
			      array (
			        0 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-th',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'Portfolio Options',
			          'text' => 'Packed with tons of portfolio options: masonry or grid layout, load more button, and more.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        1 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-star-o',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'Top-notch Support',
			          'text' => 'Read the documentation, but still have questions? We\'re here to help.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			      ),
			      'container_shape' => 'round',
			      'container_size' => 25,
			      'icon_size' => 25,
			      'per_row' => 1,
			      'responsive' => true,
			      'title_link' => false,
			      'icon_link' => false,
			      'new_window' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Features_Widget',
			        'raw' => false,
			        'grid' => 5,
			        'cell' => 1,
			        'id' => 10,
			        'style' => 
			        array (
			          'class' => 'left',
			          'padding' => '20%',
			          'background' => '#232323',
			          'background_image_attachment' => 200,
			          'background_display' => 'tile',
			          'font_color' => '#ffffff',
			        ),
			      ),
			    ),
			    11 => 
			    array (
			      'features' => 
			      array (
			        0 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-hand-o-up',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'One-click Demo Install',
			          'text' => 'We make it easy for you to get started with all of the layouts you see here in the demo.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			        1 => 
			        array (
			          'container_color' => false,
			          'icon' => 'fontawesome-file-text',
			          'icon_color' => '#ffffff',
			          'icon_image' => 0,
			          'title' => 'Detailed Documentation',
			          'text' => 'Create has a lot features. We\'ve taken the time to explain how to use them.',
			          'more_text' => '',
			          'more_url' => '',
			        ),
			      ),
			      'container_shape' => 'round',
			      'container_size' => 25,
			      'icon_size' => 25,
			      'per_row' => 1,
			      'responsive' => true,
			      'title_link' => false,
			      'icon_link' => false,
			      'new_window' => false,
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Features_Widget',
			        'raw' => false,
			        'grid' => 5,
			        'cell' => 2,
			        'id' => 11,
			        'style' => 
			        array (
			          'class' => 'left',
			          'padding' => '20%',
			          'background' => '#232323',
			          'background_display' => 'tile',
			          'font_color' => '#ffffff',
			        ),
			      ),
			    ),
			    12 => 
			    array (
			      'headline' => 
			      array (
			        'text' => 'Recent News',
			        'font' => 'default',
			        'color' => '#000000',
			        'align' => 'center',
			      ),
			      'sub_headline' => 
			      array (
			        'text' => 'Display recent posts as a carousel or grid.',
			        'font' => 'default',
			        'color' => '#aaaaaa',
			        'align' => 'center',
			      ),
			      'divider' => 
			      array (
			        'style' => 'solid',
			        'weight' => 'thin',
			        'color' => '#bc9f7a',
			      ),
			      'panels_info' => 
			      array (
			        'class' => 'SiteOrigin_Widget_Headline_Widget',
			        'raw' => false,
			        'grid' => 6,
			        'cell' => 0,
			        'id' => 12,
			        'style' => 
			        array (
			          'padding' => '20px',
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			    13 => 
			    array (
			      'title' => '',
			      'count' => '7',
			      'layout' => 'carousel',
			      'columns' => '3',
			      'alignment' => 'left',
			      'orderby' => 'date',
			      'order' => 'DESC',
			      'show_excerpt' => 'yes',
			      'carousel-nav-color' => '',
			      'panels_info' => 
			      array (
			        'class' => 'TTrust_Blog',
			        'raw' => false,
			        'grid' => 6,
			        'cell' => 0,
			        'id' => 13,
			        'style' => 
			        array (
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			  ),
			  'grids' => 
			  array (
			    0 => 
			    array (
			      'cells' => 1,
			      'style' => 
			      array (
			        'row_stretch' => 'full-stretched',
			        'equal_column_height' => 'no',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			    1 => 
			    array (
			      'cells' => 3,
			      'style' => 
			      array (
			        'bottom_margin' => '0px',
			        'gutter' => '0px',
			        'equal_column_height' => 'yes',
			        'padding_top' => '60px',
			        'padding_bottom' => '50px',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			    2 => 
			    array (
			      'cells' => 3,
			      'style' => 
			      array (
			        'bottom_margin' => '0px',
			        'row_stretch' => 'full',
			        'equal_column_height' => 'no',
			        'padding_top' => '100px',
			        'padding_bottom' => '100px',
			        'background_image' => 118,
			        'background_image_position' => 'left top',
			        'background_image_style' => 'parallax',
			      ),
			    ),
			    3 => 
			    array (
			      'cells' => 1,
			      'style' => 
			      array (
			        'bottom_margin' => '0px',
			        'gutter' => '0px',
			        'row_stretch' => 'full-stretched',
			        'equal_column_height' => 'no',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			    4 => 
			    array (
			      'cells' => 1,
			      'style' => 
			      array (
			        'bottom_margin' => '0px',
			        'gutter' => '0px',
			        'equal_column_height' => 'no',
			        'padding_top' => '50px',
			        'padding_bottom' => '50px',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			    5 => 
			    array (
			      'cells' => 3,
			      'style' => 
			      array (
			        'bottom_margin' => '0px',
			        'gutter' => '0px',
			        'row_stretch' => 'full-stretched',
			        'background' => '#eaeaea',
			        'equal_column_height' => 'yes',
			        'padding_top' => '0px',
			        'padding_bottom' => '0px',
			        'padding_left' => '0px',
			        'padding_right' => '0px',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			    6 => 
			    array (
			      'cells' => 1,
			      'style' => 
			      array (
			        'equal_column_height' => 'no',
			        'padding_top' => '50px',
			        'padding_bottom' => '50px',
			        'background_image_position' => 'left top',
			        'background_image_style' => 'cover',
			      ),
			    ),
			  ),
			  'grid_cells' => 
			  array (
			    0 => 
			    array (
			      'grid' => 0,
			      'weight' => 1,
			    ),
			    1 => 
			    array (
			      'grid' => 1,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    2 => 
			    array (
			      'grid' => 1,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    3 => 
			    array (
			      'grid' => 1,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    4 => 
			    array (
			      'grid' => 2,
			      'weight' => 0.204186413902050001301091697314404882490634918212890625,
			    ),
			    5 => 
			    array (
			      'grid' => 2,
			      'weight' => 0.59162717219589999739781660537119023501873016357421875,
			    ),
			    6 => 
			    array (
			      'grid' => 2,
			      'weight' => 0.204186413902050001301091697314404882490634918212890625,
			    ),
			    7 => 
			    array (
			      'grid' => 3,
			      'weight' => 1,
			    ),
			    8 => 
			    array (
			      'grid' => 4,
			      'weight' => 1,
			    ),
			    9 => 
			    array (
			      'grid' => 5,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    10 => 
			    array (
			      'grid' => 5,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    11 => 
			    array (
			      'grid' => 5,
			      'weight' => 0.333333333333333314829616256247390992939472198486328125,
			    ),
			    12 => 
			    array (
			      'grid' => 6,
			      'weight' => 1,
			    ),
			  ),
			);
			
			$layouts['home-one-page'] = array(
				'name' => __('Home: One Page', 'create'),
				'description' => __('Layout for demo Home: One Page page.', 'create'),
		        'widgets' => 
				  array (
				    0 => 
				    array (
				      'title' => '',
				      'text' => '[rev_slider alias="one-page-slider"]',
				      'filter' => false,
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Text',
				        'raw' => false,
				        'grid' => 0,
				        'cell' => 0,
				        'id' => 0,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    1 => 
				    array (
				      'type' => 'visual',
				      'title' => '',
				      'text' => '<h2 style="text-align: center;">MY <span style="color: #bb9f7c;">WORK</span></h2>',
				      'filter' => '1',
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Black_Studio_TinyMCE',
				        'raw' => false,
				        'grid' => 1,
				        'cell' => 0,
				        'id' => 1,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				          'font_color' => '#ffffff',
				        ),
				      ),
				    ),
				    2 => 
				    array (
				      'title' => '',
				      'show_filter' => 'yes',
				      'filter_alignment' => 'center',
				      'count' => '8',
				      'thumb_proportions' => 'square',
				      'layout' => 'rows with gutter',
				      'columns' => '4',
				      'skills' => 
				      array (
				        'illustration' => 'illustration',
				        'mobile' => 'mobile',
				        'motion' => '',
				        'photography' => 'photography',
				        'web' => 'web',
				      ),
				      'orderby' => 'date',
				      'order' => 'DESC',
				      'hover_effect' => 'effect-1',
				      'hover_color' => '#bb9f7c',
				      'hover_text_color' => '',
				      'show_skills' => 'no',
				      'show_load_more' => 'no',
				      'enable_lightbox' => 'yes',
				      'panels_info' => 
				      array (
				        'class' => 'TTrust_Portfolio',
				        'raw' => false,
				        'grid' => 1,
				        'cell' => 0,
				        'id' => 2,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				          'font_color' => '#ffffff',
				        ),
				      ),
				    ),
				    3 => 
				    array (
				      'type' => 'visual',
				      'title' => '',
				      'text' => '<h2><span style="color: #1f1f1f;">ABOUT <span style="color: #bb9f7c;">ME</span></span></h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc malesuada lectus libero, ac sagittis nisi dapibus ac. Nunc dictum imperdiet lorem. Quisque vehicula nec arcu nec rutrum. Duis sit amet mattis magna. In felis dui, elementum in tortor dictum, eleifend commodo purus. Nulla eget turpis purus. In ultrices est a pellentesque rutrum. Suspendisse egestas pharetra eros a commodo. Aenean ac ante id odio cursus tempor. Vivamus sit amet ante massa.</p>',
				      'filter' => '1',
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Black_Studio_TinyMCE',
				        'raw' => false,
				        'grid' => 2,
				        'cell' => 0,
				        'id' => 3,
				        'style' => 
				        array (
				          'padding' => '0px',
				          'background_display' => 'center',
				        ),
				      ),
				    ),
				    4 => 
				    array (
				      'networks' => 
				      array (
				        0 => 
				        array (
				          'name' => 'twitter',
				          'url' => 'https://twitter.com/',
				          'icon_color' => '#ffffff',
				          'button_color' => '#78bdf1',
				        ),
				        1 => 
				        array (
				          'name' => 'dribbble',
				          'url' => 'https://dribbble.com/',
				          'icon_color' => '#ffffff',
				          'button_color' => '#f26798',
				        ),
				        2 => 
				        array (
				          'name' => 'envelope',
				          'url' => 'mailto:fakeemail@themetrust.com',
				          'icon_color' => '#ffffff',
				          'button_color' => '#99c4e6',
				        ),
				      ),
				      'design' => 
				      array (
				        'new_window' => true,
				        'theme' => 'wire',
				        'hover' => true,
				        'icon_size' => '1',
				        'rounding' => '0.5',
				        'padding' => '1',
				        'align' => 'left',
				        'margin' => '0.1',
				      ),
				      'panels_info' => 
				      array (
				        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
				        'raw' => false,
				        'grid' => 2,
				        'cell' => 0,
				        'id' => 4,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    5 => 
				    array (
				      'type' => 'visual',
				      'title' => '',
				      'text' => '<h2 style="text-align: center;"><span style="color: #1f1f1f;">MY <span style="color: #bb9f7c;">CLIENTS</span></span></h2><p> </p>',
				      'filter' => '1',
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Black_Studio_TinyMCE',
				        'raw' => false,
				        'grid' => 3,
				        'cell' => 0,
				        'id' => 5,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    6 => 
				    array (
				      'title' => '',
				      'count' => '3',
				      'layout' => 'grid',
				      'columns' => '3',
				      'alignment' => 'center',
				      'order' => 'rand',
				      'carousel-nav-color' => '',
				      'panels_info' => 
				      array (
				        'class' => 'TTrust_Testimonials',
				        'grid' => 3,
				        'cell' => 0,
				        'id' => 6,
				        'style' => 
				        array (
				          'background_image_attachment' => false,
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    7 => 
				    array (
				      'type' => 'visual',
				      'title' => '',
				      'text' => '<p><img class="aligncenter size-full wp-image-998" src="http://create.themetrust.com/wp-content/uploads/2015/08/company_logos.png" alt="company_logos" width="3000" height="300" /></p>',
				      'filter' => '',
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Black_Studio_TinyMCE',
				        'raw' => false,
				        'grid' => 4,
				        'cell' => 0,
				        'id' => 7,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    8 => 
				    array (
				      'map_center' => '350 5th Ave, New York, NY 10118',
				      'settings' => 
				      array (
				        'map_type' => 'interactive',
				        'width' => '640',
				        'height' => '680',
				        'zoom' => 12,
				        'scroll_zoom' => true,
				        'draggable' => true,
				      ),
				      'markers' => 
				      array (
				        'marker_at_center' => true,
				        'marker_icon' => 1063,
				      ),
				      'styles' => 
				      array (
				        'style_method' => 'raw_json',
				        'styled_map_name' => '',
				        'raw_json_map_styles' => '[{"featureType":"landscape","elementType":"geometry","stylers":[{"hue":"#ededed"},{"saturation":-100},{"lightness":36},{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"on"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"hue":"#e0e0e0"},{"saturation":-100},{"lightness":-8},{"visibility":"off"}]}]',
				      ),
				      'directions' => 
				      array (
				        'origin' => '',
				        'destination' => '',
				        'travel_mode' => 'driving',
				      ),
				      'api_key_section' => 
				      array (
				        'api_key' => '',
				      ),
				      'panels_info' => 
				      array (
				        'class' => 'SiteOrigin_Widget_GoogleMap_Widget',
				        'raw' => false,
				        'grid' => 5,
				        'cell' => 0,
				        'id' => 8,
				        'style' => 
				        array (
				          'background_display' => 'tile',
				        ),
				      ),
				    ),
				    9 => 
				    array (
				      'type' => 'visual',
				      'title' => '',
				      'text' => '<h2><span style="color: #ffffff;">CONTACT</span><span style="color: #1f1f1f;"> <span style="color: #bb9f7c;">ME</span></span></h2><p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum viverra, eros nec luctus facilisis, nisi nisl tempus purus, vitae congue enim mi pulvinar orci. Quisque diam ex, faucibus sed tortor a, dignissim consequat risus.</p><p>1234 Main St.<br /> New York, NY 10021</p><p>T: 555-456-7892 <em>New York Office<br /> E: contact@create-digital.com</em></p>',
				      'filter' => '1',
				      'panels_info' => 
				      array (
				        'class' => 'WP_Widget_Black_Studio_TinyMCE',
				        'raw' => false,
				        'grid' => 5,
				        'cell' => 1,
				        'id' => 9,
				        'style' => 
				        array (
				          'padding' => '60px',
				          'background' => '#282828',
				          'background_display' => 'tile',
				          'font_color' => '#bababa',
				        ),
				      ),
				    ),
				  ),
				  'grids' => 
				  array (
				    0 => 
				    array (
				      'cells' => 1,
				      'style' => 
				      array (
				        'bottom_margin' => '0px',
				        'row_stretch' => 'full-stretched',
				        'custom_row_id' => 'hello',
				        'equal_column_height' => 'no',
				        'background_image_position' => 'left top',
				        'background_image_style' => 'cover',
				      ),
				    ),
				    1 => 
				    array (
				      'cells' => 1,
				      'style' => 
				      array (
				        'bottom_margin' => '0px',
				        'row_stretch' => 'full',
				        'background' => '#282828',
				        'custom_row_id' => 'my-work',
				        'equal_column_height' => 'no',
				        'padding_top' => '70px',
				        'padding_bottom' => '70px',
				        'background_image_position' => 'left top',
				        'background_image_style' => 'cover',
				      ),
				    ),
				    2 => 
				    array (
				      'cells' => 2,
				      'style' => 
				      array (
				        'row_stretch' => 'full',
				        'custom_row_id' => 'about-me',
				        'equal_column_height' => 'no',
				        'padding_top' => '150px',
				        'padding_bottom' => '150px',
				        'background_image' => 996,
				        'background_image_position' => 'left top',
				        'background_image_style' => 'parallax',
				      ),
				    ),
				    3 => 
				    array (
				      'cells' => 1,
				      'style' => 
				      array (
				        'row_stretch' => 'full',
				        'custom_row_id' => 'testimonials',
				        'equal_column_height' => 'no',
				        'padding_top' => '60px',
				        'padding_bottom' => '50px',
				        'background_image_position' => 'left top',
				        'background_image_style' => 'cover',
				      ),
				    ),
				    4 => 
				    array (
				      'cells' => 1,
				      'style' => 
				      array (
				        'bottom_margin' => '0px',
				        'row_stretch' => 'full',
				        'background' => '#bb9f7c',
				        'equal_column_height' => 'no',
				        'padding_top' => '40px',
				        'padding_bottom' => '10px',
				        'background_image_position' => 'left top',
				        'background_image_style' => 'cover',
				      ),
				    ),
				    5 => 
				    array (
				      'cells' => 2,
				      'style' => 
				      array (
				        'gutter' => '0px',
				        'row_stretch' => 'full-stretched',
				        'custom_row_id' => 'contact-me',
				        'equal_column_height' => 'yes',
				        'padding_top' => '0px',
				        'padding_bottom' => '0px',
				        'background_image_position' => 'center bottom',
				        'background_image_style' => 'cover',
				      ),
				    ),
				  ),
				  'grid_cells' => 
				  array (
				    0 => 
				    array (
				      'grid' => 0,
				      'weight' => 1,
				    ),
				    1 => 
				    array (
				      'grid' => 1,
				      'weight' => 1,
				    ),
				    2 => 
				    array (
				      'grid' => 2,
				      'weight' => 0.49622299651568002598622797449934296309947967529296875,
				    ),
				    3 => 
				    array (
				      'grid' => 2,
				      'weight' => 0.50377700348431997401377202550065703690052032470703125,
				    ),
				    4 => 
				    array (
				      'grid' => 3,
				      'weight' => 1,
				    ),
				    5 => 
				    array (
				      'grid' => 4,
				      'weight' => 1,
				    ),
				    6 => 
				    array (
				      'grid' => 5,
				      'weight' => 0.5,
				    ),
				    7 => 
				    array (
				      'grid' => 5,
				      'weight' => 0.5,
				    ),
				  ),
				);
				
				
				$layouts['home-portfolio'] = array(
					'name' => __('Home: Portfolio', 'create'),
					'description' => __('Layout for demo Home: Portfolio page.', 'create'),
			        'widgets' => 
					  array (
					    0 => 
					    array (
					      'title' => '',
					      'text' => '[rev_slider alias="home-portfolio"]',
					      'panels_info' => 
					      array (
					        'class' => 'WP_Widget_Text',
					        'grid' => 0,
					        'cell' => 0,
					        'id' => 0,
					        'style' => 
					        array (
					          'background_image_attachment' => false,
					          'background_display' => 'tile',
					        ),
					      ),
					      'filter' => false,
					    ),
					    1 => 
					    array (
					      'title' => '',
					      'show_filter' => 'yes',
					      'filter_alignment' => 'center',
					      'count' => '9',
					      'thumb_proportions' => 'square',
					      'layout' => 'rows with gutter',
					      'columns' => '3',
					      'skills' => 
					      array (
					        'illustration' => '',
					        'mobile' => '',
					        'motion' => '',
					        'photography' => '',
					        'web' => '',
					      ),
					      'orderby' => 'menu_order',
					      'order' => 'DESC',
					      'hover_effect' => 'effect-1',
					      'hover_color' => '',
					      'hover_text_color' => '',
					      'show_skills' => 'yes',
					      'show_load_more' => 'yes',
					      'enable_lightbox' => 'no',
					      'panels_info' => 
					      array (
					        'class' => 'TTrust_Portfolio',
					        'raw' => false,
					        'grid' => 1,
					        'cell' => 0,
					        'id' => 1,
					        'style' => 
					        array (
					          'background_display' => 'tile',
					        ),
					      ),
					    ),
					  ),
					  'grids' => 
					  array (
					    0 => 
					    array (
					      'cells' => 1,
					      'style' => 
					      array (
					        'bottom_margin' => '0px',
					        'row_stretch' => 'full-stretched',
					        'equal_column_height' => 'no',
					        'background_image_position' => 'left top',
					        'background_image_style' => 'cover',
					      ),
					    ),
					    1 => 
					    array (
					      'cells' => 1,
					      'style' => 
					      array (
					        'row_stretch' => 'full',
					        'equal_column_height' => 'no',
					        'padding_top' => '30px',
					        'padding_bottom' => '40px',
					        'background_image_position' => 'left top',
					        'background_image_style' => 'cover',
					      ),
					    ),
					  ),
					  'grid_cells' => 
					  array (
					    0 => 
					    array (
					      'grid' => 0,
					      'weight' => 1,
					    ),
					    1 => 
					    array (
					      'grid' => 1,
					      'weight' => 1,
					    ),
					  ),
					);
					
					$layouts['home-shop'] = array(
						'name' => __('Home: Shop', 'create'),
						'description' => __('Layout for demo Home: Shop page.', 'create'),
				        'widgets' => 
						  array (
						    0 => 
						    array (
						      'title' => '',
						      'text' => '[rev_slider alias="shop-slider"]',
						      'panels_info' => 
						      array (
						        'class' => 'WP_Widget_Text',
						        'grid' => 0,
						        'cell' => 0,
						        'id' => 0,
						        'style' => 
						        array (
						          'background_image_attachment' => false,
						          'background_display' => 'tile',
						        ),
						      ),
						      'filter' => false,
						    ),
						    1 => 
						    array (
						      'type' => 'html',
						      'title' => '',
						      'text' => '<span style="font-size: 36px;"><strong>Watches</strong></span>

						<span style="color: #cccccc;">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet.</span>

						<a href=\'http://create.themetrust.com/product-category/watches/\' class=\'tt-button\' style="background: #bb9f7c;">SHOP WATCHES</a>',
						      'filter' => '1',
						      'panels_info' => 
						      array (
						        'class' => 'WP_Widget_Black_Studio_TinyMCE',
						        'raw' => false,
						        'grid' => 1,
						        'cell' => 0,
						        'id' => 1,
						        'style' => 
						        array (
						          'padding' => '50px',
						          'background' => '#a0a0a0',
						          'background_image_attachment' => 844,
						          'background_display' => 'tile',
						          'font_color' => '#ffffff',
						        ),
						      ),
						    ),
						    2 => 
						    array (
						      'type' => 'html',
						      'title' => '',
						      'text' => '<span style="font-size: 36px;"><strong>Bags</strong></span>

						<span style="color: #cccccc;">Lorem ipsum dolor sit amet. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. </span>

						<a href=\'http://create.themetrust.com/product-category/bags/\' class=\'tt-button\' style="background: #bb9f7c;">SHOP BAGS</a>',
						      'filter' => '1',
						      'panels_info' => 
						      array (
						        'class' => 'WP_Widget_Black_Studio_TinyMCE',
						        'raw' => false,
						        'grid' => 1,
						        'cell' => 1,
						        'id' => 2,
						        'style' => 
						        array (
						          'padding' => '50px',
						          'background' => '#444444',
						          'background_image_attachment' => 842,
						          'background_display' => 'cover',
						          'font_color' => '#ffffff',
						        ),
						      ),
						    ),
						    3 => 
						    array (
						      'type' => 'visual',
						      'title' => '',
						      'text' => '<p><span style="font-size: 36px;"><strong>Shoes</strong></span></p><p><span style="color: #999999;">Imperdiet doming id quod mazim placerat facer am liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. </span></p><p><a class="tt-button" style="background: #bb9f7c;" href="http://create.themetrust.com/product-category/shoes/">SHOP SHOES</a></p>',
						      'filter' => '1',
						      'panels_info' => 
						      array (
						        'class' => 'WP_Widget_Black_Studio_TinyMCE',
						        'raw' => false,
						        'grid' => 1,
						        'cell' => 2,
						        'id' => 3,
						        'style' => 
						        array (
						          'padding' => '50px',
						          'background' => '#515151',
						          'background_image_attachment' => 840,
						          'background_display' => 'cover',
						          'font_color' => '#ffffff',
						        ),
						      ),
						    ),
						    4 => 
						    array (
						      'headline' => 
						      array (
						        'text' => 'Featured Items from Our Shop',
						        'font' => 'default',
						        'color' => '#000000',
						        'align' => 'center',
						      ),
						      'sub_headline' => 
						      array (
						        'text' => 'Lorem ipsum dolor sit amet, consec tetuer adipis elit, aliquam eget nibh etlibura. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.',
						        'font' => 'default',
						        'color' => '#7c7c7c',
						        'align' => 'center',
						      ),
						      'divider' => 
						      array (
						        'style' => 'solid',
						        'weight' => 'thin',
						        'color' => '#bb9f7c',
						      ),
						      'panels_info' => 
						      array (
						        'class' => 'SiteOrigin_Widget_Headline_Widget',
						        'raw' => false,
						        'grid' => 2,
						        'cell' => 1,
						        'id' => 4,
						        'style' => 
						        array (
						          'background_display' => 'tile',
						        ),
						      ),
						    ),
						    5 => 
						    array (
						      'title' => '',
						      'count' => '4',
						      'layout' => 'grid',
						      'columns' => '4',
						      'orderby' => 'date',
						      'order' => 'DESC',
						      'categories' => 
						      array (
						        'bags' => '',
						        'jewelry' => '',
						        'notebooks' => '',
						        'shoes' => '',
						        'watches' => '',
						      ),
						      'show_featured' => 'no',
						      'alignment' => 'left',
						      'carousel-nav-color' => '',
						      'panels_info' => 
						      array (
						        'class' => 'TTrust_Products',
						        'raw' => false,
						        'grid' => 3,
						        'cell' => 0,
						        'id' => 5,
						        'style' => 
						        array (
						          'background_display' => 'tile',
						        ),
						      ),
						    ),
						    6 => 
						    array (
						      'title' => 'Newsletter',
						      'sub_title' => 'Lorem ipsum dolor sit amet, consec tetuer adipis elit, aliquam eget nibh etlibura.',
						      'design' => 
						      array (
						        'background_color' => '#bb9f7c',
						        'border_color' => '',
						        'button_align' => 'right',
						      ),
						      'button' => 
						      array (
						        'text' => 'SIGN UP',
						        'url' => '#',
						        'new_window' => '',
						        'button_icon' => 
						        array (
						          'icon_selected' => 'fontawesome-envelope-o',
						          'icon_color' => '#bb9f7c',
						          'icon' => '',
						        ),
						        'design' => 
						        array (
						          'theme' => 'flat',
						          'button_color' => '#ffffff',
						          'text_color' => '#bb9f7c',
						          'hover' => true,
						          'font_size' => '1',
						          'rounding' => '0.25',
						          'padding' => '1',
						        ),
						        'attributes' => 
						        array (
						          'id' => '',
						          'title' => '',
						          'onclick' => '',
						        ),
						      ),
						      'panels_info' => 
						      array (
						        'class' => 'SiteOrigin_Widget_Cta_Widget',
						        'raw' => false,
						        'grid' => 4,
						        'cell' => 0,
						        'id' => 6,
						        'style' => 
						        array (
						          'background_display' => 'tile',
						          'font_color' => '#ffffff',
						        ),
						      ),
						    ),
						  ),
						  'grids' => 
						  array (
						    0 => 
						    array (
						      'cells' => 1,
						      'style' => 
						      array (
						        'row_stretch' => 'full-stretched',
						        'equal_column_height' => 'no',
						        'background_image_position' => 'left top',
						        'background_image_style' => 'cover',
						      ),
						    ),
						    1 => 
						    array (
						      'cells' => 3,
						      'style' => 
						      array (
						        'bottom_margin' => '50px',
						        'gutter' => '30px',
						        'equal_column_height' => 'yes',
						        'padding_top' => '30px',
						        'padding_bottom' => '50px',
						        'background_image_position' => 'left top',
						        'background_image_style' => 'cover',
						      ),
						    ),
						    2 => 
						    array (
						      'cells' => 3,
						      'style' => 
						      array (
						        'bottom_margin' => '60px',
						        'equal_column_height' => 'no',
						        'background_image_position' => 'left top',
						        'background_image_style' => 'cover',
						      ),
						    ),
						    3 => 
						    array (
						      'cells' => 1,
						      'style' => 
						      array (
						        'bottom_margin' => '50px',
						        'equal_column_height' => 'no',
						        'background_image_position' => 'left top',
						        'background_image_style' => 'cover',
						      ),
						    ),
						    4 => 
						    array (
						      'cells' => 1,
						      'style' => 
						      array (
						        'bottom_margin' => '80px',
						        'equal_column_height' => 'no',
						        'padding_bottom' => '80px',
						        'background_image_position' => 'left top',
						        'background_image_style' => 'cover',
						      ),
						    ),
						  ),
						  'grid_cells' => 
						  array (
						    0 => 
						    array (
						      'grid' => 0,
						      'weight' => 1,
						    ),
						    1 => 
						    array (
						      'grid' => 1,
						      'weight' => 0.333333333333333314829616256247390992939472198486328125,
						    ),
						    2 => 
						    array (
						      'grid' => 1,
						      'weight' => 0.333333333333333314829616256247390992939472198486328125,
						    ),
						    3 => 
						    array (
						      'grid' => 1,
						      'weight' => 0.333333333333333314829616256247390992939472198486328125,
						    ),
						    4 => 
						    array (
						      'grid' => 2,
						      'weight' => 0.25020678246485006379629112416296266019344329833984375,
						    ),
						    5 => 
						    array (
						      'grid' => 2,
						      'weight' => 0.50020678246484007178906949775409884750843048095703125,
						    ),
						    6 => 
						    array (
						      'grid' => 2,
						      'weight' => 0.249586435070310030948093071856419555842876434326171875,
						    ),
						    7 => 
						    array (
						      'grid' => 3,
						      'weight' => 1,
						    ),
						    8 => 
						    array (
						      'grid' => 4,
						      'weight' => 1,
						    ),
						  ),
						);
				
						$layouts['about-us'] = array(
							'name' => __('About Us', 'create'),
							'description' => __('Layout for demo About Us page.', 'create'),
					        'widgets' => 
							  array (
							    0 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<h2 style="text-align: right;">WE <span style="color: #ba9e78;">CREATE</span> AMAZING THINGS</h2><p style="text-align: right;"><span style="font-size: 21px; color: #737373;">The Create WordPress theme will change the way you build websites.</span></p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 0,
							        'cell' => 0,
							        'id' => 0,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							          'font_color' => '#ffffff',
							        ),
							      ),
							    ),
							    1 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">ABOUT</span></h3>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 1,
							        'cell' => 1,
							        'id' => 1,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    2 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat. Suspendisse in dolor vitae risus maximus feugiat quis nec nisi. Phasellus ut auctor ante, scelerisque scelerisque eros. Fusce vitae orci eu nisl pellentesque faucibus lacinia sed erat. Nunc consequat erat sit amet felis gravida venenatis. Duis in neque in mi consequat aliquet in id eros. Aliquam vel lorem ut tellus eleifend luctus.</p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 1,
							        'cell' => 1,
							        'id' => 2,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    3 => 
							    array (
							      'features' => 
							      array (
							        0 => 
							        array (
							          'container_color' => '#ffffff',
							          'icon' => 'fontawesome-desktop',
							          'icon_color' => '#a58c6a',
							          'icon_image' => 0,
							          'title' => 'Web Design',
							          'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.',
							          'more_text' => '',
							          'more_url' => '',
							        ),
							        1 => 
							        array (
							          'container_color' => '#ffffff',
							          'icon' => 'fontawesome-tablet',
							          'icon_color' => '#a58c6a',
							          'icon_image' => 0,
							          'title' => 'Mobile',
							          'text' => 'Donec sed dolor maximus nunc sagittis vehicula. Phasellus at ornare arcu, eu elementum risus. Mauris varius semper purus, non eleifend quam convallis ac. Vivamus sodales justo id sapien aliquam tempus. ',
							          'more_text' => '',
							          'more_url' => '',
							        ),
							        2 => 
							        array (
							          'container_color' => '#ffffff',
							          'icon' => 'fontawesome-camera',
							          'icon_color' => '#a58c6a',
							          'icon_image' => 0,
							          'title' => 'Photography',
							          'text' => 'Nam sit amet faucibus sapien. Vivamus quis mollis orci, eu sollicitudin urna. Donec turpis justo, iaculis sit amet lorem eu, rhoncus cursus ipsum. Ut laoreet accumsan arcu consequat consectetur.',
							          'more_text' => '',
							          'more_url' => '',
							        ),
							      ),
							      'container_shape' => 'rounded-hex',
							      'container_size' => 84,
							      'icon_size' => 24,
							      'per_row' => 3,
							      'responsive' => true,
							      'title_link' => false,
							      'icon_link' => false,
							      'new_window' => false,
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_Features_Widget',
							        'raw' => false,
							        'grid' => 2,
							        'cell' => 0,
							        'id' => 3,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							          'font_color' => '#ffffff',
							        ),
							      ),
							    ),
							    4 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">OUR TEAM</span></h3><p style="text-align: center;">Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat. Suspendisse in dolor vitae risus maximus feugiat quis nec nisi.</p><p style="text-align: center;"> </p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 3,
							        'cell' => 1,
							        'id' => 4,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    5 => 
							    array (
							      'image_fallback' => '',
							      'image' => 261,
							      'size' => 'full',
							      'title' => '',
							      'alt' => '',
							      'url' => '',
							      'bound' => true,
							      'new_window' => false,
							      'full_width' => false,
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_Image_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 0,
							        'id' => 5,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    6 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Frank Thompson</strong></span><br />CEO</p><p class="member-role">Suspendisse lobortis pulvinar mauris eget placerat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. </p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 0,
							        'id' => 6,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    7 => 
							    array (
							      'networks' => 
							      array (
							        0 => 
							        array (
							          'name' => 'twitter',
							          'url' => 'https://twitter.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#78bdf1',
							        ),
							        1 => 
							        array (
							          'name' => 'linkedin',
							          'url' => 'https://www.linkedin.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#0177b4',
							        ),
							        2 => 
							        array (
							          'name' => 'google-plus',
							          'url' => 'https://plus.google.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#dd4b39',
							        ),
							        3 => 
							        array (
							          'name' => 'envelope',
							          'url' => 'mailto:',
							          'icon_color' => '#ffffff',
							          'button_color' => '#99c4e6',
							        ),
							      ),
							      'design' => 
							      array (
							        'new_window' => true,
							        'theme' => 'flat',
							        'hover' => true,
							        'icon_size' => '1',
							        'rounding' => '0.25',
							        'padding' => '0.5',
							        'align' => 'left',
							        'margin' => '0.1',
							      ),
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 0,
							        'id' => 7,
							        'style' => 
							        array (
							          'padding' => '0px',
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    8 => 
							    array (
							      'image_fallback' => '',
							      'image' => 246,
							      'size' => 'full',
							      'title' => '',
							      'alt' => '',
							      'url' => '',
							      'bound' => true,
							      'new_window' => false,
							      'full_width' => false,
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_Image_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 1,
							        'id' => 8,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    9 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Abby Smith</strong></span><br />Lead Designer</p><p class="member-role">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.</p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 1,
							        'id' => 9,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    10 => 
							    array (
							      'networks' => 
							      array (
							        0 => 
							        array (
							          'name' => 'twitter',
							          'url' => 'https://twitter.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#78bdf1',
							        ),
							        1 => 
							        array (
							          'name' => 'linkedin',
							          'url' => 'https://www.linkedin.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#0177b4',
							        ),
							        2 => 
							        array (
							          'name' => 'google-plus',
							          'url' => 'https://plus.google.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#dd4b39',
							        ),
							        3 => 
							        array (
							          'name' => 'envelope',
							          'url' => 'mailto:',
							          'icon_color' => '#ffffff',
							          'button_color' => '#99c4e6',
							        ),
							      ),
							      'design' => 
							      array (
							        'new_window' => true,
							        'theme' => 'flat',
							        'hover' => true,
							        'icon_size' => '1',
							        'rounding' => '0.25',
							        'padding' => '0.5',
							        'align' => 'left',
							        'margin' => '0.1',
							      ),
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 1,
							        'id' => 10,
							        'style' => 
							        array (
							          'padding' => '0px',
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    11 => 
							    array (
							      'image_fallback' => '',
							      'image' => 262,
							      'size' => 'full',
							      'title' => '',
							      'alt' => '',
							      'url' => '',
							      'bound' => true,
							      'new_window' => false,
							      'full_width' => false,
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_Image_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 2,
							        'id' => 11,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    12 => 
							    array (
							      'type' => 'visual',
							      'title' => '',
							      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Brian Carson</strong></span><br />Lead Developer</p><p class="member-role">Suspendisse in dolor vitae risus maximus feugiat quis nec nisi. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.</p>',
							      'filter' => '1',
							      'panels_info' => 
							      array (
							        'class' => 'WP_Widget_Black_Studio_TinyMCE',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 2,
							        'id' => 12,
							        'style' => 
							        array (
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    13 => 
							    array (
							      'networks' => 
							      array (
							        0 => 
							        array (
							          'name' => 'twitter',
							          'url' => 'https://twitter.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#78bdf1',
							        ),
							        1 => 
							        array (
							          'name' => 'linkedin',
							          'url' => 'https://www.linkedin.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#0177b4',
							        ),
							        2 => 
							        array (
							          'name' => 'google-plus',
							          'url' => 'https://plus.google.com/',
							          'icon_color' => '#ffffff',
							          'button_color' => '#dd4b39',
							        ),
							        3 => 
							        array (
							          'name' => 'envelope',
							          'url' => 'mailto:',
							          'icon_color' => '#ffffff',
							          'button_color' => '#99c4e6',
							        ),
							      ),
							      'design' => 
							      array (
							        'new_window' => true,
							        'theme' => 'flat',
							        'hover' => true,
							        'icon_size' => '1',
							        'rounding' => '0.25',
							        'padding' => '0.5',
							        'align' => 'left',
							        'margin' => '0.1',
							      ),
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
							        'raw' => false,
							        'grid' => 4,
							        'cell' => 2,
							        'id' => 13,
							        'style' => 
							        array (
							          'padding' => '0px',
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							    14 => 
							    array (
							      'map_center' => '350 5th Avenue, New York, NY 10118',
							      'settings' => 
							      array (
							        'map_type' => 'interactive',
							        'width' => '640',
							        'height' => '480',
							        'zoom' => 12,
							        'scroll_zoom' => true,
							        'draggable' => true,
							      ),
							      'markers' => 
							      array (
							        'marker_at_center' => true,
							        'marker_icon' => 1063,
							      ),
							      'styles' => 
							      array (
							        'style_method' => 'raw_json',
							        'styled_map_name' => '',
							        'raw_json_map_styles' => '[{"featureType":"all","elementType":"all","stylers":[{"hue":"#ffaa00"},{"saturation":"-33"},{"lightness":"10"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"visibility":"on"}]}]',
							      ),
							      'directions' => 
							      array (
							        'origin' => '',
							        'destination' => '',
							        'travel_mode' => 'driving',
							      ),
							      'api_key_section' => 
							      array (
							        'api_key' => '',
							      ),
							      'panels_info' => 
							      array (
							        'class' => 'SiteOrigin_Widget_GoogleMap_Widget',
							        'grid' => 5,
							        'cell' => 0,
							        'id' => 14,
							        'style' => 
							        array (
							          'background_image_attachment' => false,
							          'background_display' => 'tile',
							        ),
							      ),
							    ),
							  ),
							  'grids' => 
							  array (
							    0 => 
							    array (
							      'cells' => 1,
							      'style' => 
							      array (
							        'bottom_margin' => '0px',
							        'row_stretch' => 'full',
							        'equal_column_height' => 'no',
							        'padding_top' => '250px',
							        'padding_bottom' => '250px',
							        'background_image' => 264,
							        'background_image_position' => 'left top',
							        'background_image_style' => 'parallax',
							      ),
							    ),
							    1 => 
							    array (
							      'cells' => 3,
							      'style' => 
							      array (
							        'bottom_margin' => '0px',
							        'row_stretch' => 'full',
							        'background' => '#f4f4f4',
							        'equal_column_height' => 'no',
							        'padding_top' => '60px',
							        'padding_bottom' => '50px',
							        'background_image_position' => 'left top',
							        'background_image_style' => 'cover',
							      ),
							    ),
							    2 => 
							    array (
							      'cells' => 1,
							      'style' => 
							      array (
							        'bottom_margin' => '0px',
							        'row_stretch' => 'full',
							        'equal_column_height' => 'no',
							        'padding_top' => '70px',
							        'padding_bottom' => '50px',
							        'background_image' => 302,
							        'background_image_position' => 'left top',
							        'background_image_style' => 'parallax',
							      ),
							    ),
							    3 => 
							    array (
							      'cells' => 3,
							      'style' => 
							      array (
							        'bottom_margin' => '0px',
							        'row_stretch' => 'full',
							        'equal_column_height' => 'no',
							        'padding_top' => '50px',
							        'padding_bottom' => '0px',
							        'background_image_position' => 'left top',
							        'background_image_style' => 'cover',
							      ),
							    ),
							    4 => 
							    array (
							      'cells' => 3,
							      'style' => 
							      array (
							        'bottom_margin' => '50px',
							        'gutter' => '30px',
							        'equal_column_height' => 'no',
							        'padding_top' => '0px',
							        'padding_bottom' => '0px',
							        'background_image_position' => 'left top',
							        'background_image_style' => 'cover',
							      ),
							    ),
							    5 => 
							    array (
							      'cells' => 1,
							      'style' => 
							      array (
							        'row_stretch' => 'full-stretched',
							        'equal_column_height' => 'no',
							        'background_image_position' => 'left top',
							        'background_image_style' => 'cover',
							      ),
							    ),
							  ),
							  'grid_cells' => 
							  array (
							    0 => 
							    array (
							      'grid' => 0,
							      'weight' => 1,
							    ),
							    1 => 
							    array (
							      'grid' => 1,
							      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
							    ),
							    2 => 
							    array (
							      'grid' => 1,
							      'weight' => 0.5994930291508200337347034292179159820079803466796875,
							    ),
							    3 => 
							    array (
							      'grid' => 1,
							      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
							    ),
							    4 => 
							    array (
							      'grid' => 2,
							      'weight' => 1,
							    ),
							    5 => 
							    array (
							      'grid' => 3,
							      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
							    ),
							    6 => 
							    array (
							      'grid' => 3,
							      'weight' => 0.5994930291508200337347034292179159820079803466796875,
							    ),
							    7 => 
							    array (
							      'grid' => 3,
							      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
							    ),
							    8 => 
							    array (
							      'grid' => 4,
							      'weight' => 0.333333333333333314829616256247390992939472198486328125,
							    ),
							    9 => 
							    array (
							      'grid' => 4,
							      'weight' => 0.333333333333333314829616256247390992939472198486328125,
							    ),
							    10 => 
							    array (
							      'grid' => 4,
							      'weight' => 0.333333333333333314829616256247390992939472198486328125,
							    ),
							    11 => 
							    array (
							      'grid' => 5,
							      'weight' => 1,
							    ),
							  ),
							);
							
							$layouts['about-us-blocks'] = array(
								'name' => __('About Us Blocks', 'create'),
								'description' => __('Layout for demo About Us: Blocks page.', 'create'),
						        'widgets' => 
								  array (
								    0 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h1 style="text-align: center;">ABOUT US</h1><p style="text-align: center;"><span style="font-size: 21px; color: #737373;">The Create WordPress theme will change the way you build websites.</span></p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 0,
								        'cell' => 0,
								        'id' => 0,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								          'font_color' => '#0a0a0a',
								        ),
								      ),
								    ),
								    1 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h3>HOW WE WORK</h3><p><span style="color: #808080;">Donec sed dolor maximus nunc sagittis vehicula. Phasellus at ornare arcu, eu elementum risus. Mauris varius semper purus, non eleifend quam convallis ac. Vivamus sodales justo id sapien aliquam tempus.</span></p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 1,
								        'cell' => 0,
								        'id' => 1,
								        'style' => 
								        array (
								          'padding' => '50px',
								          'background' => '#262626',
								          'background_display' => 'tile',
								          'font_color' => '#ffffff',
								        ),
								      ),
								    ),
								    2 => 
								    array (
								      'min_height' => '250',
								      'panels_info' => 
								      array (
								        'class' => 'TTrust_Spacer',
								        'raw' => false,
								        'grid' => 1,
								        'cell' => 1,
								        'id' => 2,
								        'style' => 
								        array (
								          'background_image_attachment' => 343,
								          'background_display' => 'cover',
								        ),
								      ),
								    ),
								    3 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h3>OUR PROCESS</h3><p><span style="color: #808080;">Vivamus sodales justo id sapien aliquam tempus. Donec sed dolor maximus nunc sagittis vehicula. Phasellus at ornare arcu, eu elementum risus. Mauris varius semper purus, non eleifend quam convallis ac. </span></p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 1,
								        'cell' => 2,
								        'id' => 3,
								        'style' => 
								        array (
								          'padding' => '50px',
								          'background' => '#262626',
								          'background_display' => 'tile',
								          'font_color' => '#ffffff',
								        ),
								      ),
								    ),
								    4 => 
								    array (
								      'min_height' => '250',
								      'panels_info' => 
								      array (
								        'class' => 'TTrust_Spacer',
								        'raw' => false,
								        'grid' => 2,
								        'cell' => 0,
								        'id' => 4,
								        'style' => 
								        array (
								          'background_image_attachment' => 351,
								          'background_display' => 'cover',
								        ),
								      ),
								    ),
								    5 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h3>WHAT WE DO</h3><p><span style="color: #e5b89c;">Donec sed dolor maximus nunc sagittis vehicula. Phasellus at ornare arcu, eu elementum risus. Mauris varius semper purus, non eleifend quam convallis ac. Vivamus sodales justo id sapien aliquam tempus.</span></p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 2,
								        'cell' => 1,
								        'id' => 5,
								        'style' => 
								        array (
								          'padding' => '50px',
								          'background' => '#e56934',
								          'background_display' => 'tile',
								          'font_color' => '#ffffff',
								        ),
								      ),
								    ),
								    6 => 
								    array (
								      'min_height' => '250',
								      'panels_info' => 
								      array (
								        'class' => 'TTrust_Spacer',
								        'raw' => false,
								        'grid' => 2,
								        'cell' => 2,
								        'id' => 6,
								        'style' => 
								        array (
								          'background_image_attachment' => 359,
								          'background_display' => 'cover',
								        ),
								      ),
								    ),
								    7 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">OUR SERVICES</span></h3><p style="text-align: center;">Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar. </p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 3,
								        'cell' => 1,
								        'id' => 7,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    8 => 
								    array (
								      'features' => 
								      array (
								        0 => 
								        array (
								          'container_color' => false,
								          'icon' => 'fontawesome-desktop',
								          'icon_color' => '#ea6035',
								          'icon_image' => 0,
								          'title' => 'Web Design',
								          'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.',
								          'more_text' => '',
								          'more_url' => '',
								        ),
								        1 => 
								        array (
								          'container_color' => false,
								          'icon' => 'fontawesome-tablet',
								          'icon_color' => '#ea6035',
								          'icon_image' => 0,
								          'title' => 'Mobile',
								          'text' => 'Donec sed dolor maximus nunc sagittis vehicula. Phasellus at ornare arcu, eu elementum risus. Mauris varius semper purus, non eleifend quam convallis ac. Vivamus sodales justo id sapien aliquam tempus. ',
								          'more_text' => '',
								          'more_url' => '',
								        ),
								        2 => 
								        array (
								          'container_color' => false,
								          'icon' => 'fontawesome-camera',
								          'icon_color' => '#ea6035',
								          'icon_image' => 0,
								          'title' => 'Photography',
								          'text' => 'Nam sit amet faucibus sapien. Vivamus quis mollis orci, eu sollicitudin urna. Donec turpis justo, iaculis sit amet lorem eu, rhoncus cursus ipsum. Ut laoreet accumsan arcu consequat consectetur.',
								          'more_text' => '',
								          'more_url' => '',
								        ),
								      ),
								      'container_shape' => 'round',
								      'container_size' => 30,
								      'icon_size' => 30,
								      'per_row' => 3,
								      'responsive' => true,
								      'title_link' => false,
								      'icon_link' => false,
								      'new_window' => false,
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_Features_Widget',
								        'raw' => false,
								        'grid' => 4,
								        'cell' => 0,
								        'id' => 8,
								        'style' => 
								        array (
								          'padding' => '0px',
								          'background_display' => 'tile',
								          'font_color' => '#242424',
								        ),
								      ),
								    ),
								    9 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<h3 style="text-align: center;"><span style="color: #242424;">OUR TEAM</span></h3><p style="text-align: center;">Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat. Suspendisse in dolor vitae risus maximus feugiat quis nec nisi.</p><p style="text-align: center;"> </p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 5,
								        'cell' => 1,
								        'id' => 9,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    10 => 
								    array (
								      'image_fallback' => '',
								      'image' => 261,
								      'size' => 'full',
								      'title' => '',
								      'alt' => '',
								      'url' => '',
								      'bound' => true,
								      'new_window' => false,
								      'full_width' => false,
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_Image_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 0,
								        'id' => 10,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    11 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Frank Thompson</strong></span><br />CEO</p><p class="member-role">Suspendisse lobortis pulvinar mauris eget placerat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. </p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 0,
								        'id' => 11,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    12 => 
								    array (
								      'networks' => 
								      array (
								        0 => 
								        array (
								          'name' => 'twitter',
								          'url' => 'https://twitter.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        1 => 
								        array (
								          'name' => 'linkedin',
								          'url' => 'https://www.linkedin.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        2 => 
								        array (
								          'name' => 'google-plus',
								          'url' => 'https://plus.google.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        3 => 
								        array (
								          'name' => 'envelope',
								          'url' => 'mailto:',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								      ),
								      'design' => 
								      array (
								        'new_window' => true,
								        'theme' => 'flat',
								        'hover' => true,
								        'icon_size' => '1',
								        'rounding' => '0.25',
								        'padding' => '0.5',
								        'align' => 'left',
								        'margin' => '0.1',
								      ),
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 0,
								        'id' => 12,
								        'style' => 
								        array (
								          'padding' => '0px',
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    13 => 
								    array (
								      'image_fallback' => '',
								      'image' => 246,
								      'size' => 'full',
								      'title' => '',
								      'alt' => '',
								      'url' => '',
								      'bound' => true,
								      'new_window' => false,
								      'full_width' => false,
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_Image_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 1,
								        'id' => 13,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    14 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Abby Smith</strong></span><br />Lead Designer</p><p class="member-role">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis molestie leo, sed efficitur dui. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.</p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 1,
								        'id' => 14,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    15 => 
								    array (
								      'networks' => 
								      array (
								        0 => 
								        array (
								          'name' => 'twitter',
								          'url' => 'https://twitter.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        1 => 
								        array (
								          'name' => 'linkedin',
								          'url' => 'https://www.linkedin.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        2 => 
								        array (
								          'name' => 'google-plus',
								          'url' => 'https://plus.google.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        3 => 
								        array (
								          'name' => 'envelope',
								          'url' => 'mailto:',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								      ),
								      'design' => 
								      array (
								        'new_window' => true,
								        'theme' => 'flat',
								        'hover' => true,
								        'icon_size' => '1',
								        'rounding' => '0.25',
								        'padding' => '0.5',
								        'align' => 'left',
								        'margin' => '0.1',
								      ),
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 1,
								        'id' => 15,
								        'style' => 
								        array (
								          'padding' => '0px',
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    16 => 
								    array (
								      'image_fallback' => '',
								      'image' => 262,
								      'size' => 'full',
								      'title' => '',
								      'alt' => '',
								      'url' => '',
								      'bound' => true,
								      'new_window' => false,
								      'full_width' => false,
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_Image_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 2,
								        'id' => 16,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    17 => 
								    array (
								      'type' => 'visual',
								      'title' => '',
								      'text' => '<p class="member-role"><span style="color: #242424;"><strong>Brian Carson</strong></span><br />Lead Developer</p><p class="member-role">Suspendisse in dolor vitae risus maximus feugiat quis nec nisi. Nulla cursus turpis quis mattis rutrum. Suspendisse lobortis pulvinar mauris eget placerat.</p>',
								      'filter' => '1',
								      'panels_info' => 
								      array (
								        'class' => 'WP_Widget_Black_Studio_TinyMCE',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 2,
								        'id' => 17,
								        'style' => 
								        array (
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								    18 => 
								    array (
								      'networks' => 
								      array (
								        0 => 
								        array (
								          'name' => 'twitter',
								          'url' => 'https://twitter.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        1 => 
								        array (
								          'name' => 'linkedin',
								          'url' => 'https://www.linkedin.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        2 => 
								        array (
								          'name' => 'google-plus',
								          'url' => 'https://plus.google.com/',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								        3 => 
								        array (
								          'name' => 'envelope',
								          'url' => 'mailto:',
								          'icon_color' => '#ffffff',
								          'button_color' => '#cccccc',
								        ),
								      ),
								      'design' => 
								      array (
								        'new_window' => true,
								        'theme' => 'flat',
								        'hover' => true,
								        'icon_size' => '1',
								        'rounding' => '0.25',
								        'padding' => '0.5',
								        'align' => 'left',
								        'margin' => '0.1',
								      ),
								      'panels_info' => 
								      array (
								        'class' => 'SiteOrigin_Widget_SocialMediaButtons_Widget',
								        'raw' => false,
								        'grid' => 6,
								        'cell' => 2,
								        'id' => 18,
								        'style' => 
								        array (
								          'padding' => '0px',
								          'background_display' => 'tile',
								        ),
								      ),
								    ),
								  ),
								  'grids' => 
								  array (
								    0 => 
								    array (
								      'cells' => 1,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'row_stretch' => 'full',
								        'equal_column_height' => 'no',
								        'padding_top' => '180px',
								        'padding_bottom' => '180px',
								        'background_image' => 340,
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								    1 => 
								    array (
								      'cells' => 3,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'gutter' => '0px',
								        'row_stretch' => 'full-stretched',
								        'equal_column_height' => 'yes',
								        'padding_top' => '0px',
								        'padding_bottom' => '0px',
								        'padding_left' => '0px',
								        'padding_right' => '0px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								    2 => 
								    array (
								      'cells' => 3,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'gutter' => '0px',
								        'row_stretch' => 'full-stretched',
								        'equal_column_height' => 'yes',
								        'padding_top' => '0px',
								        'padding_bottom' => '0px',
								        'padding_left' => '0px',
								        'padding_right' => '0px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								    3 => 
								    array (
								      'cells' => 3,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'gutter' => '0px',
								        'row_stretch' => 'full',
								        'equal_column_height' => 'no',
								        'padding_top' => '50px',
								        'padding_bottom' => '0px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								    4 => 
								    array (
								      'cells' => 1,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'gutter' => '0px',
								        'row_stretch' => 'full',
								        'equal_column_height' => 'no',
								        'padding_top' => '50px',
								        'padding_bottom' => '50px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'parallax',
								      ),
								    ),
								    5 => 
								    array (
								      'cells' => 3,
								      'style' => 
								      array (
								        'bottom_margin' => '0px',
								        'row_stretch' => 'full',
								        'background' => '#f2f2f2',
								        'equal_column_height' => 'no',
								        'padding_top' => '50px',
								        'padding_bottom' => '0px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								    6 => 
								    array (
								      'cells' => 3,
								      'style' => 
								      array (
								        'bottom_margin' => '50px',
								        'gutter' => '30px',
								        'row_stretch' => 'full',
								        'background' => '#f2f2f2',
								        'equal_column_height' => 'no',
								        'padding_top' => '0px',
								        'padding_bottom' => '50px',
								        'background_image_position' => 'left top',
								        'background_image_style' => 'cover',
								      ),
								    ),
								  ),
								  'grid_cells' => 
								  array (
								    0 => 
								    array (
								      'grid' => 0,
								      'weight' => 1,
								    ),
								    1 => 
								    array (
								      'grid' => 1,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    2 => 
								    array (
								      'grid' => 1,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    3 => 
								    array (
								      'grid' => 1,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    4 => 
								    array (
								      'grid' => 2,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    5 => 
								    array (
								      'grid' => 2,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    6 => 
								    array (
								      'grid' => 2,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    7 => 
								    array (
								      'grid' => 3,
								      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
								    ),
								    8 => 
								    array (
								      'grid' => 3,
								      'weight' => 0.5994930291508200337347034292179159820079803466796875,
								    ),
								    9 => 
								    array (
								      'grid' => 3,
								      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
								    ),
								    10 => 
								    array (
								      'grid' => 4,
								      'weight' => 1,
								    ),
								    11 => 
								    array (
								      'grid' => 5,
								      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
								    ),
								    12 => 
								    array (
								      'grid' => 5,
								      'weight' => 0.5994930291508200337347034292179159820079803466796875,
								    ),
								    13 => 
								    array (
								      'grid' => 5,
								      'weight' => 0.2002534854245900108882239010199555195868015289306640625,
								    ),
								    14 => 
								    array (
								      'grid' => 6,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    15 => 
								    array (
								      'grid' => 6,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								    16 => 
								    array (
								      'grid' => 6,
								      'weight' => 0.333333333333333314829616256247390992939472198486328125,
								    ),
								  ),
								);
								
								$layouts['contact-us'] = array(
									'name' => __('Contact Us', 'create'),
									'description' => __('Layout for demo Contact Us page.', 'create'),
							        'widgets' => 
									  array (
									    0 => 
									    array (
									      'map_center' => '350 5th Ave, New York, NY 10118',
									      'settings' => 
									      array (
									        'map_type' => 'interactive',
									        'width' => '640',
									        'height' => '480',
									        'zoom' => 12,
									        'scroll_zoom' => true,
									        'draggable' => true,
									      ),
									      'markers' => 
									      array (
									        'marker_at_center' => true,
									        'marker_icon' => 1065,
									      ),
									      'styles' => 
									      array (
									        'style_method' => 'raw_json',
									        'styled_map_name' => '',
									        'raw_json_map_styles' => '[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]',
									      ),
									      'directions' => 
									      array (
									        'origin' => '',
									        'destination' => '',
									        'travel_mode' => 'driving',
									      ),
									      'api_key_section' => 
									      array (
									        'api_key' => '',
									      ),
									      'panels_info' => 
									      array (
									        'class' => 'SiteOrigin_Widget_GoogleMap_Widget',
									        'grid' => 0,
									        'cell' => 0,
									        'id' => 0,
									        'style' => 
									        array (
									          'background_image_attachment' => false,
									          'background_display' => 'tile',
									        ),
									      ),
									    ),
									    1 => 
									    array (
									      'type' => 'visual',
									      'title' => '',
									      'text' => '<h3><span style="color: #242424;">Contact Us</span></h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus posuere interdum diam eget semper. Pellentesque purus turpis, vehicula et posuere ultrices, dictum vitae turpis. Cras porta enim justo, a tempus arcu ullamcorper in.</p><p>[contact-form-7 id="732" title="Contact form 1"]</p>',
									      'filter' => '1',
									      'panels_info' => 
									      array (
									        'class' => 'WP_Widget_Black_Studio_TinyMCE',
									        'raw' => false,
									        'grid' => 1,
									        'cell' => 0,
									        'id' => 1,
									        'style' => 
									        array (
									          'background_display' => 'tile',
									        ),
									      ),
									    ),
									    2 => 
									    array (
									      'type' => 'visual',
									      'title' => '',
									      'text' => '<h4><span style="color: #242424;">Address</span></h4><p>1234 Main St.<br />New York, NY 10021</p><h4><span style="color: #242424;">Phone</span></h4><p>555-456-7892 <em>New York Office<br /></em>555-376-4532 Los Angeles<em> Office</em></p><h4><span style="color: #242424;">Email</span></h4><p>contact@create-digital.com</p>',
									      'filter' => '1',
									      'panels_info' => 
									      array (
									        'class' => 'WP_Widget_Black_Studio_TinyMCE',
									        'raw' => false,
									        'grid' => 1,
									        'cell' => 1,
									        'id' => 2,
									        'style' => 
									        array (
									          'padding' => '50px',
									          'background' => '#f7f7f7',
									          'background_display' => 'tile',
									        ),
									      ),
									    ),
									  ),
									  'grids' => 
									  array (
									    0 => 
									    array (
									      'cells' => 1,
									      'style' => 
									      array (
									        'bottom_margin' => '60px',
									        'row_stretch' => 'full-stretched',
									        'equal_column_height' => 'no',
									        'background_image_position' => 'left top',
									        'background_image_style' => 'cover',
									      ),
									    ),
									    1 => 
									    array (
									      'cells' => 2,
									      'style' => 
									      array (
									        'bottom_margin' => '60px',
									        'equal_column_height' => 'no',
									        'padding_bottom' => '60px',
									        'background_image_position' => 'left top',
									        'background_image_style' => 'cover',
									      ),
									    ),
									  ),
									  'grid_cells' => 
									  array (
									    0 => 
									    array (
									      'grid' => 0,
									      'weight' => 1,
									    ),
									    1 => 
									    array (
									      'grid' => 1,
									      'weight' => 0.63971539456661996592146124385180883109569549560546875,
									    ),
									    2 => 
									    array (
									      'grid' => 1,
									      'weight' => 0.360284605433379978567387524890364147722721099853515625,
									    ),
									  ),
									);
									
									$layouts['pricing'] = array(
										'name' => __('Pricing', 'create'),
										'description' => __('Layout for demo Pricing page.', 'create'),
								        'widgets' => 
										  array (
										    0 => 
										    array (
										      'type' => 'visual',
										      'title' => '',
										      'text' => '<h2 style="text-align: center;"><span style="color: #333333;">We Give You the <span style="color: #bb9f7c;">Best</span> Value</span></h2><p style="text-align: center;"><span style="font-size: 21px;">This is an example pricing page. It\'s super easy to create beautiful pricing tables. You have options to set colors, features, icons and more.</span></p>',
										      'filter' => '1',
										      'panels_info' => 
										      array (
										        'class' => 'WP_Widget_Black_Studio_TinyMCE',
										        'raw' => false,
										        'grid' => 0,
										        'cell' => 1,
										        'id' => 0,
										        'style' => 
										        array (
										          'background_display' => 'tile',
										        ),
										      ),
										    ),
										    1 => 
										    array (
										      'title' => false,
										      'columns' => 
										      array (
										        0 => 
										        array (
										          'featured' => '',
										          'title' => 'Silver',
										          'subtitle' => 'Budget Plan',
										          'image' => '',
										          'price' => '$59',
										          'per' => 'per month',
										          'button' => 'BUY NOW',
										          'url' => '#',
										          'features' => 
										          array (
										            0 => 
										            array (
										              'text' => '1 GB Storage',
										              'hover' => '',
										              'icon_new' => 'fontawesome-database',
										              'icon_color' => '',
										            ),
										            1 => 
										            array (
										              'text' => '2 Domain Names',
										              'hover' => '',
										              'icon_new' => 'fontawesome-globe',
										              'icon_color' => '',
										            ),
										            2 => 
										            array (
										              'text' => '3 FTP Users',
										              'hover' => '',
										              'icon_new' => 'fontawesome-user',
										              'icon_color' => '',
										            ),
										            3 => 
										            array (
										              'text' => '100 GB Bandwidth',
										              'hover' => '',
										              'icon_new' => 'fontawesome-exchange',
										              'icon_color' => '',
										            ),
										          ),
										        ),
										        1 => 
										        array (
										          'featured' => 'on',
										          'title' => 'Gold',
										          'subtitle' => 'Best Value',
										          'image' => '',
										          'price' => '$99',
										          'per' => 'per month',
										          'button' => 'BUY NOW',
										          'url' => '#',
										          'features' => 
										          array (
										            0 => 
										            array (
										              'text' => '3 GB Storage',
										              'hover' => '',
										              'icon_new' => 'fontawesome-database',
										              'icon_color' => '',
										            ),
										            1 => 
										            array (
										              'text' => '5 Domain Names',
										              'hover' => '',
										              'icon_new' => 'fontawesome-globe',
										              'icon_color' => '',
										            ),
										            2 => 
										            array (
										              'text' => '5 FTP Users',
										              'hover' => '',
										              'icon_new' => 'fontawesome-user',
										              'icon_color' => '',
										            ),
										            3 => 
										            array (
										              'text' => '1000 GB Bandwidth',
										              'hover' => '',
										              'icon_new' => 'fontawesome-exchange',
										              'icon_color' => '',
										            ),
										          ),
										        ),
										        2 => 
										        array (
										          'featured' => '',
										          'title' => 'Platinum',
										          'subtitle' => 'Business & Enterpise',
										          'image' => '',
										          'price' => '$129',
										          'per' => 'per month',
										          'button' => 'BUY NOW',
										          'url' => '#',
										          'features' => 
										          array (
										            0 => 
										            array (
										              'text' => '10 GB Storage',
										              'hover' => '',
										              'icon_new' => 'fontawesome-database',
										              'icon_color' => '',
										            ),
										            1 => 
										            array (
										              'text' => '10 Domain Names',
										              'hover' => '',
										              'icon_new' => 'fontawesome-globe',
										              'icon_color' => '',
										            ),
										            2 => 
										            array (
										              'text' => '10 FTP Users',
										              'hover' => '',
										              'icon_new' => 'fontawesome-user',
										              'icon_color' => '',
										            ),
										            3 => 
										            array (
										              'text' => '50000 GB Bandwidth',
										              'hover' => '',
										              'icon_new' => 'fontawesome-exchange',
										              'icon_color' => '',
										            ),
										          ),
										        ),
										      ),
										      'theme' => 'flat',
										      'header_color' => '#5e5e5e',
										      'featured_header_color' => '#bb9f7c',
										      'button_color' => '#bb9f7c',
										      'featured_button_color' => '#bb9f7c',
										      'button_new_window' => false,
										      'panels_info' => 
										      array (
										        'class' => 'SiteOrigin_Widget_PriceTable_Widget',
										        'raw' => false,
										        'grid' => 1,
										        'cell' => 0,
										        'id' => 1,
										        'style' => 
										        array (
										          'background_display' => 'tile',
										        ),
										      ),
										    ),
										  ),
										  'grids' => 
										  array (
										    0 => 
										    array (
										      'cells' => 3,
										      'style' => 
										      array (
										        'equal_column_height' => 'no',
										        'padding_top' => '70px',
										        'padding_bottom' => '0px',
										        'background_image_position' => 'left top',
										        'background_image_style' => 'cover',
										      ),
										    ),
										    1 => 
										    array (
										      'cells' => 1,
										      'style' => 
										      array (
										        'equal_column_height' => 'no',
										        'padding_top' => '20px',
										        'padding_bottom' => '60px',
										        'background_image_position' => 'left top',
										        'background_image_style' => 'cover',
										      ),
										    ),
										  ),
										  'grid_cells' => 
										  array (
										    0 => 
										    array (
										      'grid' => 0,
										      'weight' => 0.14912461852642000526003585036960430443286895751953125,
										    ),
										    1 => 
										    array (
										      'grid' => 0,
										      'weight' => 0.69952473554990002302389484611921943724155426025390625,
										    ),
										    2 => 
										    array (
										      'grid' => 0,
										      'weight' => 0.1513506459236799994716449191400897689163684844970703125,
										    ),
										    3 => 
										    array (
										      'grid' => 1,
										      'weight' => 1,
										    ),
										  ),
										);
										
										$layouts['testimonials'] = array(
											'name' => __('Testimonials', 'create'),
											'description' => __('Layout for demo Testimonials page.', 'create'),
									        'widgets' => 
											  array (
											    0 => 
											    array (
											      'type' => 'visual',
											      'title' => '',
											      'text' => '<h2 style="text-align: center;">TESTIMONIALS</h2><p style="text-align: center;"><span style="color: #82acb8;"><span style="font-size: 21px; line-height: 31.5px;">Read what our customers have to say about us.</span></span></p>',
											      'filter' => '1',
											      'panels_info' => 
											      array (
											        'class' => 'WP_Widget_Black_Studio_TinyMCE',
											        'grid' => 0,
											        'cell' => 0,
											        'id' => 0,
											        'style' => 
											        array (
											          'background_image_attachment' => false,
											          'background_display' => 'tile',
											          'font_color' => '#ffffff',
											        ),
											      ),
											    ),
											    1 => 
											    array (
											      'title' => '',
											      'count' => '9',
											      'layout' => 'grid',
											      'columns' => '3',
											      'alignment' => 'center',
											      'order' => 'rand',
											      'carousel-nav-color' => '',
											      'panels_info' => 
											      array (
											        'class' => 'TTrust_Testimonials',
											        'raw' => false,
											        'grid' => 1,
											        'cell' => 0,
											        'id' => 1,
											        'style' => 
											        array (
											          'background_display' => 'tile',
											        ),
											      ),
											    ),
											  ),
											  'grids' => 
											  array (
											    0 => 
											    array (
											      'cells' => 1,
											      'style' => 
											      array (
											        'bottom_margin' => '0px',
											        'row_stretch' => 'full',
											        'equal_column_height' => 'no',
											        'padding_top' => '250px',
											        'padding_bottom' => '250px',
											        'background_image' => 1098,
											        'background_image_position' => 'left top',
											        'background_image_style' => 'parallax',
											      ),
											    ),
											    1 => 
											    array (
											      'cells' => 1,
											      'style' => 
											      array (
											        'equal_column_height' => 'no',
											        'padding_top' => '60px',
											        'padding_bottom' => '60px',
											        'background_image_position' => 'left top',
											        'background_image_style' => 'cover',
											      ),
											    ),
											  ),
											  'grid_cells' => 
											  array (
											    0 => 
											    array (
											      'grid' => 0,
											      'weight' => 1,
											    ),
											    1 => 
											    array (
											      'grid' => 1,
											      'weight' => 1,
											    ),
											  ),
											);
								
								
				
			
	
    return $layouts;

}
add_filter('siteorigin_panels_prebuilt_layouts','create_prebuilt_layouts');



?>