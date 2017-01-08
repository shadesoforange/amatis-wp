<?php
/**
 * @package create
 */
?>

	<?php
	$featured_image = "";
	if ( is_single() ) {
		if( has_post_thumbnail() ) {
			$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'create_full_width' );
		}
	}
	?>

	<header class="campaign main entry-header parallax <?php if( $featured_image ) { echo ' has-background'; } ?>" <?php if( $featured_image ) { echo 'style="background-image: url(' . esc_url( $featured_image[0] ) . ');"'; } ?>>
		<div class="inner">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php the_content(); ?>
		</div>
	</header><!-- .entry-header -->