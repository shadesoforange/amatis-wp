<?php
/**
 * @package create
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
			<span class="meta <?php create_meta_class(); ?>">
				<?php create_the_post_meta(); ?>
				<?php $show_comments = get_theme_mod( 'create_show_meta_comments', 'yes' ); ?>
				<?php if($show_comments == 'yes') {?>
					<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
						<span class="comments-link"><?php comments_popup_link( __( 'No Comments', 'create' ), __( '1 Comment', 'create' ), __( '% Comments', 'create' ) ); ?></span>
					<?php endif; ?>
				<?php } ?>
			</span><!-- .entry-meta -->
		<?php endif; ?>

		<?php if(has_post_thumbnail()) : ?>
			<div class="featured-image">
				<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail( 'create_post_thumb', array( 'class' => 'post-thumb', 'alt' => ''. the_title_attribute( 'echo=0' ) .'', 'title' => ''. the_title_attribute( 'echo=0' ) .'' ) ); ?></a>
			</div>
		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php
		if ( is_search() ) : // Display Excerpts on Option or Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php $show_full_posts = get_theme_mod( 'create_show_full_posts', 'no' );?>
		<?php 
		if($show_full_posts == "yes"){
			the_content(); 
		}else{
			the_excerpt(); 
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
	
</article><!-- #post-<?php the_ID(); ?> -->
