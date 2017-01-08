<?php
/**
 * @package create
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="body-wrap">
			<div class="entry-content">
				<h2><?php _e( 'Oops! That page can&rsquo;t be found.', 'create' ); ?></h2>

				<p><?php _e( 'The page you are looking for could not be found. Try a different address, or search using the form below.', 'create' ); ?></p>

				<?php get_search_form(); ?>

			</div></div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
