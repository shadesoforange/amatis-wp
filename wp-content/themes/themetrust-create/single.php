<?php
/**
 * The Template for displaying all single posts.
 *
 * @package create
 */

// Grab the metabox values
$id = get_the_ID();
$show_title = get_post_meta( $id, '_create_title_show', true ); 
$hide_text = get_post_meta( $id, '_create_title_hide_text', true ); 
$subtitle = get_post_meta( $id, '_create_title_subtitle', true ); 
$title_img = get_post_meta( $id, '_create_title_img', true );
$title_parallax = get_post_meta( $id, '_create_title_parallax', true );
$title_alignment = get_post_meta( $id, '_create_title_alignment', true );
$title_bg = get_post_meta( $id, '_create_title_bg_img', true );
$title_style = "";
$full_width_content = get_post_meta( $id, '_create_post_full_width', true );

$header_class = $title_alignment;
if($title_parallax=='yes'){
	$header_class .= " parallax-section title-parallax";
}


get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<div id="primary" class="content-area blog">
		
		<header class="main entry-header <?php echo $header_class; ?>" <?php if($title_parallax=="yes"){?> data-smooth-scrolling="off" data-scroll-speed="1.5" data-parallax-image="<?php echo $title_bg; ?>" data-parallax-id=".title-parallax" <?php } ?>>
			<div class="inner">
			<div class="title">	
			<?php if( $title_img ) { ?>
				<img src="<?php echo $title_img; ?>">
			<?php } ?>
			<?php if ($hide_text!='yes'){?>	
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<span class="meta <?php create_meta_class(); ?>">
						<?php create_the_post_meta(); ?>
					</span>
			<?php } ?>
			</div>
			</div><!-- .inner -->
		</header><!-- .entry-header -->
		
		<main id="main" class="site-main" role="main">
			<div class="body-wrap clear">
			
			<article id="post-<?php the_ID(); ?>" <?php post_class('content-main'); ?>>

				<?php get_template_part( 'templates/content', 'single' ); ?>
				
				<footer class="post-nav">
					<?php if(get_theme_mod( 'create_show_social_on_posts', 'yes' ) == 'yes') {
						create_social_sharing(); 
					} ?>
				
					<?php create_the_post_nav(); ?>
					
				</footer>
				
				<?php if ( comments_open() || '0' != get_comments_number() ) : // If comments are open or we have at least one comment, load up the comment template?>
					<div class="comments-wrap">
						<?php comments_template(); ?>
					</div>
				<?php endif; ?>

			</article><!-- #post-## -->
			
			<?php if($full_width_content!='yes'){
			get_sidebar();
			}
			?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>