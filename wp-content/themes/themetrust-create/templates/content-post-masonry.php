<?php
/**
 * @package create
 */

global $post_meta;

$size = get_post_meta( $id, '_create_post_featured_image_size', true );
$thumb_size = 'create_thumb_' . $size;
?>

		<article <?php post_class('small'); ?>>
			<div class="inside">
			<?php if(has_post_thumbnail()) : ?>			
				<a href="<?php the_permalink() ?>" rel="bookmark" class="post-thumb"><?php the_post_thumbnail( $thumb_size, array( 'class' => '', 'alt' => '' . the_title_attribute( 'echo=0' ) . '', 'title' => '' . the_title_attribute( 'echo=0' ) . '' ) ); ?></a>
			<?php endif; ?>

			<div class="content">
				<h3><a href="<?php the_permalink(); ?>" rel="bookmark" alt="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h3>
				<span class="meta">
					<?php create_the_post_meta(); ?>
					<?php $show_comments = get_theme_mod( 'create_show_meta_comments', 'yes' ); ?>
					<?php if($show_comments == 'yes') {?>
						<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
							<span class="comments-link"><?php comments_popup_link( __( 'No Comments', 'create' ), __( '1 Comment', 'create' ), __( '% Comments', 'create' ) ); ?></span>
						<?php endif; ?>
					<?php } ?>
				</span>
				<?php if($post_meta['show_excerpt'] == "yes"){
					the_excerpt();
				 } ?>
			</div>
			</div>
		</article>