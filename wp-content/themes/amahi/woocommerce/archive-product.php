<?php
/**
 * Template for woocommerce shop pages.
 *
 * @package create
 */

// Grab the metabox values
if( is_shop() || is_product_category() ) { // if we're on a shop page, grab the shop page id
	$id = get_option( 'woocommerce_shop_page_id' );
}else{
	$id = get_the_ID();
}

$show_title = get_post_meta( $id, '_create_title_show', true ); 
$hide_text = get_post_meta( $id, '_create_title_hide_text', true ); 
$subtitle = get_post_meta( $id, '_create_title_subtitle', true ); 
$title_img = get_post_meta( $id, '_create_title_img', true );
$title_parallax = get_post_meta( $id, '_create_title_parallax', true );
$title_alignment = get_post_meta( $id, '_create_title_alignment', true );
$title_bg = get_post_meta( $id, '_create_title_bg_img', true );
$title_style = "";

$header_class = $title_alignment;
if($title_parallax=='yes'){
	$header_class .= " parallax-section title-parallax";
}

$shop_layout = get_theme_mod( 'create_shop_layout', 'has-sidebar' );
$shop_class = "";
if($shop_layout == "has-sidebar") {
	$shop_class .= "col3";
}else {
	$shop_class .= "col4";
}

get_header('shop'); ?>

	<div id="primary" class="content-area">
		<header class="main entry-header <?php echo $header_class; ?>" <?php if($title_parallax=="yes"){?> data-smooth-scrolling="off" data-scroll-speed="1.5" data-parallax-image="<?php echo $title_bg; ?>" data-parallax-id=".title-parallax" <?php } ?>>
			<div class="inner">
			<div class="title">	
				<h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
				<?php do_action( 'woocommerce_archive_description' ); ?>
			</div>
			</div><!-- .inner -->
		</header><!-- .entry-header -->
		
		<main id="main" class="site-main product-archive clear " role="main">
			<div class="body-wrap clear">
			<?php

			// Make sure WooCommerce is installed and that we want to show products on the home page
			if( class_exists( 'WooCommerce' ) ) { ?>

					<section id="shop" class="shop <?php echo $shop_class; ?>">
					
					<div class="before-shop clear">
						<?php
						/**
						* woocommerce_before_shop_loop hook
						*
						* @hooked woocommerce_result_count - 20
						* @hooked woocommerce_catalog_ordering - 30
						*/
						do_action( 'woocommerce_before_shop_loop' );
						?>
					</div>	
						
					
						<?php if ( have_posts() ) : ?>
									
									

									<?php woocommerce_product_loop_start(); ?>

										<?php woocommerce_product_subcategories(); ?>

										<?php while ( have_posts() ) : the_post(); ?>

											<?php wc_get_template_part( 'templates/content-product-small'); ?>

										<?php endwhile; // end of the loop. ?>

									<?php woocommerce_product_loop_end(); ?>

									<?php
										/**
										 * woocommerce_after_shop_loop hook
										 *
										 * @hooked woocommerce_pagination - 10
										 */
										do_action( 'woocommerce_after_shop_loop' );
									?>

								<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

									<?php wc_get_template( 'loop/no-products-found.php' ); ?>

								<?php endif; ?>
						

					

					<div class="clear"></div>
				</section><!-- #shop-home -->

			<?php } // if
			wp_reset_query(); ?>
			
			<?php
				/**
				 * woocommerce_sidebar hook
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				if($shop_layout == "has-sidebar") {
				do_action( 'woocommerce_sidebar' );
				}
			?>
			
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer('shop'); ?>