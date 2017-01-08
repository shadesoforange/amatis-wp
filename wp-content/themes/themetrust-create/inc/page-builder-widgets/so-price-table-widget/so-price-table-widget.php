<?php
/*
Extend the SO Price Table Widget
*/

function create_extend_price_table_form( $form_options, $widget ){
    if( !empty($form_options['theme']['options']) ) {
        $form_options['theme']['options']['flat'] = __('Flat', 'create');
    }
    return $form_options;
}
add_filter('siteorigin_widgets_form_options_sow-price-table', 'create_extend_price_table_form', 10, 2);


function create_price_table_template_file( $filename, $instance, $widget ){
    if( !empty($instance['theme']) && $instance['theme'] == 'flat' ) {
	//echo $instance['theme'];
        $filename = get_stylesheet_directory() . '/inc/page-builder-widgets/so-price-table-widget/tpl/flat.php'; 
    }
    return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-price-table', 'create_price_table_template_file', 10, 3 );


function create_price_table_less_file( $filename, $instance, $widget ){
    if( !empty($instance['theme']) && $instance['theme'] == 'flat' ) {
        $filename = get_stylesheet_directory() . '/inc/page-builder-widgets/so-price-table-widget/styles/flat.less'; 
    }
    return $filename;
}
add_filter( 'siteorigin_widgets_less_file_sow-price-table', 'create_price_table_less_file', 10, 3 );