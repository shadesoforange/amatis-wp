<?php

/**
 * Collection of functions and template tags specific to WooCommerce
 *
 * @package create
 */

// Disable default CSS
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
// Remove the sections
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
// Add them back in in a different order
add_action( 'woocommerce_before_single_product', 'woocommerce_breadcrumb', 9 );


//DISABLE WOOCOMMERCE PRETTY PHOTO STYLE
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );

function my_deregister_styles() {
	wp_deregister_style( 'woocommerce_prettyPhoto_css' );
}



/**
 * Redirects the purchaser straight to the checkout page when the buy-now button is used.
 *
 * @return string $checkout_url
 */
add_filter ( 'woocommerce_add_to_cart_redirect', 'create_wc_redirect_to_checkout' );

function create_wc_redirect_to_checkout() {
	global $woocommerce;

	$buy_now_post = isset( $_GET['buy_now'] ) ? $_GET['buy_now'] : false;

	if( $buy_now_post ) {
		$checkout_url = $woocommerce->cart->get_checkout_url();

		return $checkout_url;
	}
}

/**
 * Creates a buy-now button
 *
 * @param str $product_id   The ID of the product to buy
 *
 * @return string $output   HTML for the Buy Now button
 */
function create_wc_the_buy_now( $product_id ) {

	$buy_now = do_shortcode( '[add_to_cart_url id="' . $product_id . '"]' );

	$output = '<a href="' . $buy_now . '&buy_now=true" class="buy-now">' . __( 'Buy Now', 'create' ) . '</a>';

	echo $output;

} // create_wc_the_buy_now


/**
 * Define image sizes
 */
function create_wc_image_dimensions() {
	global $pagenow;
 
	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

  	$catalog = array(
		'width' 	=> '600',	// px
		'height'	=> '800',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '600',	// px
		'height'	=> '600',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '120',	// px
		'height'	=> '120',	// px
		'crop'		=> 1 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}

add_action( 'after_switch_theme', 'create_wc_image_dimensions', 1 );


 
/**
 * Update cart icon
 */
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>
	<a class="cart-icon cart-contents right open" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'create'); ?>">
	<span class="cart-count"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
	</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');



/**
* is_realy_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @access public
* @return bool
*/
function is_woocommerce_page () {
        if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
                return true;
        }
        $woocommerce_keys   =   array ( "woocommerce_shop_page_id" ,
                                        "woocommerce_terms_page_id" ,
                                        "woocommerce_cart_page_id" ,
                                        "woocommerce_checkout_page_id" ,
                                        "woocommerce_pay_page_id" ,
                                        "woocommerce_thanks_page_id" ,
                                        "woocommerce_myaccount_page_id" ,
                                        "woocommerce_edit_address_page_id" ,
                                        "woocommerce_view_order_page_id" ,
                                        "woocommerce_change_password_page_id" ,
                                        "woocommerce_logout_page_id" ,
                                        "woocommerce_lost_password_page_id" ) ;
        foreach ( $woocommerce_keys as $wc_page_id ) {
                if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
                        return true ;
                }
        }
        return false;
}


// Set Number of products per page
$products_per_page = get_theme_mod( 'create_shop_product_count', 12 );
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . $products_per_page . ';' ), 20 );

