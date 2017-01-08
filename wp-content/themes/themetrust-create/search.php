<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package create
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<header class="main entry-header">
			<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'create' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<span class="overlay"></span>
		</header><!-- .entry-header -->
		
		
		<main id="main" class="site-main blog" role="main">
			<div class="body-wrap clear">
				<div class="content-main">	
		<?php if ( have_posts() ) : ?>
			
			<div id="posts-scroll">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'templates/content', 'search' );
				?>

			<?php endwhile; ?>
			</div>

			<?php create_the_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main><!-- #main -->
</section><!-- #primary -->
<?php get_footer(); ?>