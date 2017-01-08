<?php
/**
 * @package create
 */

$show_featured_img = get_post_meta( $id, '_create_post_show_featured_img', true );
?>

	
		<div class="entry-content">
			<?php if(has_post_thumbnail() && $show_featured_img == "yes") { ?>
				<div class="featured-image">
					<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail( 'create_post_thumb', array( 'class' => 'post-thumb', 'alt' => ''. the_title_attribute( 'echo=0' ) .'', 'title' => ''. the_title_attribute( 'echo=0' ) .'' ) ); ?></a>
				</div>
			<?php } ?>
			
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'create' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	
