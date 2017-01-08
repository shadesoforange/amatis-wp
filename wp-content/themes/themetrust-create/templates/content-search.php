<?php
/**
 * @package create
 */
?>
<div class="entry-content">
<article id="post-<?php the_ID(); ?>" <?php post_class('clear'); ?> >
	<?php if(has_post_thumbnail()) : ?>			
		<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail('create_thumb_square', array('class' => 'post-thumb', 'alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a>
	<?php endif; ?>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	
</article><!-- #post-## -->
</div>