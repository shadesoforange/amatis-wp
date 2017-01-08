<?php
/**
 * @package create
 */

global $post_meta;
$post_meta = array();
$post_meta['show_excerpt'] = get_theme_mod( 'create_archive_show_excerpt', 'yes' ); 

get_header(); ?>

	<div id="primary" class="content-area">
		
		<?php $archive_layout = get_theme_mod( 'create_archive_layout', 'standard' ); ?>
		<?php get_template_part( 'templates/archive', $archive_layout ); ?>
		
	</div><!-- #primary -->
<?php get_footer(); ?>
