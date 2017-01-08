<?php
/**
 * Template Name: Blog
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
$title_style = "";

$header_class = $title_alignment;
if($title_parallax=='yes'){
	$header_class .= " parallax";
}

$post_count = get_post_meta( $id, '_create_blog_posts_per_page', true );

get_header(); ?>

	<div id="primary" class="content-area">
		
		<?php if($show_title == "yes"){ ?>
		<header class="main entry-header <?php echo $header_class; ?>" <?php if($title_parallax=="yes"){?> data-stellar-background-ratio="0.5" <?php } ?>>
			<div class="inner">
			<div class="title">	
			<?php if( $title_img ) { ?>
				<img src="<?php echo $title_img; ?>">
			<?php } ?>
			<?php if ($hide_text!='yes'){?>	
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php if( $subtitle ) { ?>
					<p class="subtitle">
						<?php echo $subtitle; ?>
					</p>
				<?php } ?>
			<?php } ?>
			</div>
			</div><!-- .inner -->
		</header><!-- .entry-header -->
		<?php } //end if ?>
		
		<main id="main" class="site-main blog" role="main">
			<div class="body-wrap clear">
				<div class="content-main">
				<?php if ( have_posts() ) : ?>

					<div id="posts-scroll">
					<?php
					$wp_query = new WP_Query( array(
						'post_type' 	=> "post",
						'posts_per_page' 	=> $post_count,
						'paged' => $paged,
						'post_status'	=> 'publish'
					));					
					?>

					<?php  while ( have_posts()) : the_post(); ?>

						<?php get_template_part( 'templates/content', get_post_format() ); ?>
				
					<?php endwhile; ?>

					</div><!-- #posts-scroll -->

					<?php create_the_paging_nav(); ?>

				<?php else : ?>

					<?php get_template_part( 'templates/content', 'none' ); ?>

				<?php endif; ?>
				</div>
			<?php get_sidebar(); ?>
			</div>
		</main><!-- #main -->
		
	</div><!-- #primary -->
<?php get_footer(); ?>
