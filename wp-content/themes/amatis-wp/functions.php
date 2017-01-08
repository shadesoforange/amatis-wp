<?php

@ini_set( 'upload_max_size' , '64M' );

@ini_set( 'post_max_size', '64M');

@ini_set( 'max_execution_time', '300' );

add_action( 'wp_enqueue_scripts', 'amatis_enqueue_styles' );
function amatis_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'amatis-custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '20151215', true );
}

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'medium-square', 600, 600, true ); // cropped
}
add_filter('image_size_names_choose', 'my_image_sizes');
function my_image_sizes($sizes) {
$addsizes = array(
"medium-square" => __( "Medium Square")
);
$newsizes = array_merge($sizes, $addsizes);
return $newsizes;
}



/* Hide prices unless user is logged in */

add_action('after_setup_theme','activate_filter') ;
 
function activate_filter(){
add_filter('woocommerce_get_price_html', 'bbloomer_show_price_logged');
}
 
function bbloomer_show_price_logged($price){
if(is_user_logged_in() ){
return $price;
}
else
{
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
}
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);