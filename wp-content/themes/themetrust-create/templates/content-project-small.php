<?php
/**
 * The template used for displaying project thumbs
 *
 * @package Create
 * @since 1.0
 */
$id = get_the_ID();
global $post;
global $portfolio_config;

$skills = create_get_skills( $post ); /** @see inc/template-tags.php */
$skills_class = $skills['isotope_class'];
$effect_class = $portfolio_config['hover_effect'];
$project_background_color = $portfolio_config['hover_color']; 
$size_class = '';
$details_class = '';

if($portfolio_config['show_skills'] == "yes"){ 
	$details_class = ' has-skills';
}

if($portfolio_config['masonry']){ //check if masonry layout is enabled
	$size = get_post_meta( $id, '_create_project_featured_image_size', true );
	$thumb_size = 'create_thumb_' . $size;
	$size_class = 'masonry-'.$size;
}else{
	$thumb_size = 'create_thumb_' . $portfolio_config['thumb_proportions'];
}
$lightbox_video = "";
$lightbox_img = "";
if($portfolio_config['enable_lightbox'] == "yes"){ //check if lightbox mode is enabled
	$lightbox_video = get_post_meta( $id, '_create_project_lightbox_video', true );
	$lightbox_img = get_post_meta( $id, '_create_project_lightbox_img', true );
}
?>

					<div class="project small<?php echo ' ' . $skills_class . ' '. $effect_class . ' '. $size_class;?>" id="project-<?php echo $post->ID; ?>">
						<div class="inside">
							
							<div class="details <?php echo $details_class; ?>" style="border-color: <?php echo $portfolio_config['hover_text_color']; ?>;">
								<div class="text">
								
								<div class="title" >
									<?php the_title( '<h3 class="entry-title" style="color: '.$portfolio_config['hover_text_color'].'!important;">', "</h3>\n" );?>
								</div>
								
								<?php if($portfolio_config['show_skills'] == "yes"){ ?>
								<div class="skills" style="color: <?php echo $portfolio_config['hover_text_color']; ?>;">
									<?php create_the_thumb_skills(); /** @see inc/template-tags.php */ ?>
								</div>
								<?php } ?>
								
								</div>
							</div>

							<div class="overlay" style="background-color: <?php echo $portfolio_config['hover_color']; ?>;"></div>
							
							<?php if($lightbox_video){ //if lightbox mode is enabled and project has a lightbox image, link to lightbox image ?>
								<a href="<?php echo $lightbox_video; ?>" alt="<?php echo the_title_attribute( 'echo=0' ); ?>" data-rel="prettyPhoto[portfolio]"></a>
							<?php }elseif($lightbox_img){ //if lightbox mode is enabled and project has a lightbox image, link to lightbox image ?>
								<a href="<?php echo $lightbox_img; ?>" alt="<?php echo the_title_attribute( 'echo=0' ); ?>" data-rel="prettyPhoto[portfolio]"></a>
							<?php }else { ?>
								<a href="<?php the_permalink(); ?>" alt="<?php echo the_title_attribute( 'echo=0' ); ?>"></a>
							<?php } ?>
						<?php if( has_post_thumbnail() ) {							
							the_post_thumbnail( $thumb_size, array( 'class' => '', 'alt' => '' . the_title_attribute( 'echo=0' ) . '', 'title' => '' . the_title_attribute( 'echo=0' ) . '' ) );
						} else { ?>
							<span class="empty-project"></span>
						<?php } ?>

						

						
						</div>
					</div><!-- #post->ID -->
