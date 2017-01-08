<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); 

// Grab the metabox values
$id = get_the_ID();
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
?>

<div id="primary" class="content-area">
	
	<?php if($show_title != "no"){ ?>
	<header class="main entry-header <?php echo $header_class; ?>" <?php if($title_parallax=="yes"){?> data-smooth-scrolling="off" data-scroll-speed="1.5" data-parallax-image="<?php echo $title_bg; ?>" data-parallax-id=".title-parallax" <?php } ?>>
		<div class="inner">
		<div class="title">	
		<?php if( $title_img ) { ?>
			<img src="<?php echo $title_img; ?>">
		<?php } ?>
		<?php if ($hide_text!='yes'){?>	
			<h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
			<?php if( $subtitle ) { ?>
				<p class="subtitle">
					<?php echo $subtitle; ?>
				</p>
			<?php }else{
				do_action( 'woocommerce_archive_description' );
			} ?>
		<?php } ?>
		</div>
		</div><!-- .inner -->
	</header><!-- .entry-header -->
	<?php } //end if ?>
	
<main id="main" class="site-main" role="main">
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
</main>
</div><!-- #primary -->
<?php get_footer( 'shop' ); ?>