<?php

add_action( 'wp_enqueue_scripts', 'amahi_enqueue_styles' );
function amahi_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'amahi-custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '20151215', true );
}

/* Add image size for banner */

add_image_size( 'square', 800, 800, false );

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