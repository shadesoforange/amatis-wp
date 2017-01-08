<?php
/**
 * @package create
 */
?>

<div class="testimonial small clearfix">
	<div class="inside">	
		<div class="testimonial-img">			
			<?php the_post_thumbnail("create_thumb_square", array('class' => '', 'alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?>						
		</div>	
		<div class="testimonial-text">			
			<?php the_content(); ?>	
			<span class="title"><span><?php the_title(); ?></span></span>
		</div>
	</div>			
</div>
