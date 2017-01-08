<main id="main" class="site-main blog" role="main">
	<div class="body-wrap clear">
		<div class="content-main full">
		<?php if ( have_posts() ) : ?>

			<div id="posts-scroll" class="posts-scroll clear masonry">
				<div class="grid-sizer"></div>

			<?php  while ( have_posts()) : the_post(); ?>

				<?php get_template_part( 'templates/content', 'post-masonry' ); ?>
		
			<?php endwhile; ?>

			</div><!-- #posts-scroll -->

			<?php create_the_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>
		</div>
	</div>
</main><!-- #main -->