<?php
/**
 * @package create
 */

$shop_count = get_theme_mod( 'create_shop_count' );
$price      = esc_attr( get_post_meta( get_the_ID(), '_price', true ) );
$reg_price  = esc_attr( get_post_meta( get_the_ID(), '_regular_price', true) );
$sale       = $price < $reg_price ? TRUE : FALSE;
$currency   = get_woocommerce_currency_symbol();
$hover_color = "#000";
?>

<li class="product small" id="<?php echo $post->ID; ?>">
	<div class="inside">
	<div class="thumb-container">
		<a href="<?php the_permalink(); ?>" rel="bookmark" alt="<?php the_title_attribute(); ?>">
			
			<div class="product-thumb">
				<div class="overlay" style="background-color: <?php echo $hover_color; ?>;"></div>
				<?php
				if( has_post_thumbnail() ) {
					woocommerce_template_loop_product_thumbnail();					
				} else { ?>
					<span class="blank-product"></span>
				<?php } ?>

				<?php if( $sale ) {?>

					<span class="sale"><?php _e( 'Sale', 'create' ); ?></span>

				<?php } ?>

			</div>
		</a>
		
	</div>


	<div class="details">
		<span class="title"><?php the_title(); ?></span>
		<span class="price"><?php echo $sale ? '<span>' . $currency . $reg_price . '</span> ' . $currency . $price : $currency . $price;?></span>
	</div>
	
	</div>
</li>