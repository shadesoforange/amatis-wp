<?php
/**
 * @package create
 */
?>

<?php

	$posts_count        = get_theme_mod( 'create_recent_posts_count', 3 );
	$recent_posts_title = get_theme_mod( 'create_recent_posts_title', __( 'Recent Posts', 'create' ) );
	$blog_page_id       = get_option( 'page_for_posts' );
	$recent_summary     = create_clean_and_tag( get_theme_mod( 'create_recent_posts_summary' ) );
	$background_image   = esc_url( get_theme_mod( 'create_posts_background_image' ) );

if( $posts_count > 0 ) { ?>

<section id="blog"<?php if( $background_image ){ echo ' class="has-background" style="background-image: url(' . $background_image . ');"'; } ?>>

	<?php ?>

	<?php if( $recent_posts_title ) {?>

		<header>
			<h3><?php echo wp_kses_post( $recent_posts_title ); ?></h3>
			<?php echo $recent_summary; ?>
		</header>

	<?php } ?>

	<?php

	$args = array(
		'ignore_sticky_posts' => 1,
    	'posts_per_page' => $posts_count,
    	'post_type' => array(
			'post'
		)
	);

	?>
	<?php $recentPosts = new WP_Query( $args ); ?>

	<div class="posts">

		<div id="posts-scroll">

			<?php while ( $recentPosts->have_posts() ) {
				$recentPosts->the_post(); ?>

				<?php get_template_part( 'templates/content-post-small' ); ?>

			<?php } ?>

		</div>

		<div class="clear"></div>

	</div>

	<footer>
		<?php create_the_view_all('post'); ?>
	</footer>

</section><!-- #testimonials -->
<?php } ?>