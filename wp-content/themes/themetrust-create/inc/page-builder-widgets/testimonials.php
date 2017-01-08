<?php
/**
 * @package create
 */



/**
* Class to create a custom widget for displaying testimonials
*/

class TTrust_Testimonials extends WP_Widget {

	/**
	 * Register widget with Wordpress
	 *
	 * @global mixed $ttrust_config[] stores theme variables
	 */

	function TTrust_Testimonials() {

		global 	$ttrust_config;

		$widget_ops = array( 'classname' => 'create-testimonials', 'description' => __('Display testimonials.', 'create' ) );

		// Instantiate parent
		parent::__construct(

			'ttrust_testimonials', // Base ID
			__( 'Create Testimonials', 'create' ), // Name
			 $widget_ops // Args

		); // parent::__construct()

	} // TTrust_Testimonials()

	/**
	 * Front-end display of widget.
	 *
 	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function widget( $args, $instance ) {
			extract( $args ); 		

			// Get the layout class
			$layout_class = preg_replace('#[ -]+#', '-', $instance['layout']);
			$column_class = 'col-'.$instance['columns'];
			$alignment_class = 'alignment-'.$instance['alignment'];
			echo $before_widget;
			?>
				<div id="<?php echo $args['widget_id']; ?>" class="testimonials <?php echo $layout_class; ?> <?php echo $column_class; ?> <?php echo $alignment_class; ?>">
				
				<?php
				$r = new WP_Query( array(
						'post_type' 	=> "testimonial",
						'showposts' 	=> $instance['count'],
						'nopaging' 		=> 0,
						'post_status'	=> 'publish',
						'orderby' 		=> $instance['order'],
						'order' 		=> "ASC"
					) );
				?>
						<?php  while ( $r->have_posts()) : $r->the_post();
							get_template_part( 'templates/content', 'testimonial-small' ); 
						?>
						<?php endwhile; ?>
				</div><!-- .testimonials -->
				<?php echo $after_widget; ?>
				<?php if($instance['layout']=='carousel'){ ?>
					<script type="text/javascript">
					//<![CDATA[
					jQuery(document).ready(function(){		
						jQuery('#<?php echo $args['widget_id']; ?>.testimonials.carousel').owlCarousel({
						    items : <?php echo $instance['columns']; ?>,							
							itemsDesktop : [1199,<?php echo $instance['columns']; ?>],
							itemsDesktopSmall : [980,<?php echo $instance['columns']; ?>],
							itemsTablet: [768,1],
							itemsTabletSmall: false,
							itemsMobile : [479,1]						
						})
						jQuery('head').append('<style type="text/css">#<?php echo $args["widget_id"]; ?>.testimonials.carousel .owl-controls .owl-pagination .owl-page span {background-color: <?php echo $instance["carousel-nav-color"]; ?>;}</style>');
					});
					//]]>
					</script>
				<?php } ?>

<?php
			wp_reset_query();

	} // widget()

	/**
	 * Update function filters title input.
	 *
	 * Takes the old instance, replaces the title with that from the new instance and strips tags on the title.
	 *
 	 * @see WP_Widget::widget()
	 *
	 * @param array $new_instance 	New values from database.
	 * @param array $old_instance 	Old values from database.
	 *
	 * @return array $instance 		Returned values from database.
	 */

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = $new_instance['count'];
		$instance['layout'] = $new_instance['layout'];
		$instance['columns'] = $new_instance['columns'];
		$instance['alignment'] = $new_instance['alignment'];
		$instance['order'] = $new_instance['order'];
		$instance['carousel-nav-color'] = $new_instance['carousel-nav-color'];
		return $instance;

	} // update()

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Updated values from database.
	 */

	function form( $instance ) {
		
		// Check values
		$title = (isset($instance['title'])) ? esc_attr($instance['title']) : '';
		$count = (isset($instance['count'])) ? esc_attr($instance['count']) : '3';
		$layout = (isset($instance['layout'])) ? esc_attr($instance['layout']) : '';
		$columns = (isset($instance['columns'])) ? esc_attr($instance['columns']) : '';
		$alignment = (isset($instance['alignment'])) ? esc_attr($instance['alignment']) : '';
		$order = (isset($instance['order'])) ? esc_attr($instance['order']) : '';
		$carousel_nav_color = (isset($instance['carousel-nav-color'])) ? esc_attr($instance['carousel-nav-color']) : '';
		?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Name', 'create' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of Testimonials', 'create' ); ?></label>
		<input type="number" name="<?php echo $this->get_field_name( 'count' ); ?>" min="1" max="99" value="<?php echo $count; ?>">
		
		<p>
		<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('layout'); ?>" id="<?php echo $this->get_field_id('layout'); ?>" class="widefat">
		<?php
		$options = array('Grid'=>'grid', 'Carousel'=>'carousel');
		foreach ($options as $option => $value) {
		$selected = ($layout == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Columns', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('columns'); ?>" id="<?php echo $this->get_field_id('columns'); ?>" class="widefat">
		<?php
		$options = array('1', '2', '3', '4');
		foreach ($options as $option) {
		$selected = ($columns == $option) ? ' selected="selected"' : '';
		echo '<option value="' . $option . '" id="' . $option . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e('Content Alignment', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('alignment'); ?>" id="<?php echo $this->get_field_id('alignment'); ?>" class="widefat">
		<?php
		$options = array('Left'=>'left', 'Center'=>'center');
		foreach ($options as $option => $value) {
		$selected = ($alignment == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
		<?php
		$options = array('Random' => 'rand', 'Custom' => 'menu_order');
		foreach ($options as $option => $value) {
		$selected = ($order == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<script type='text/javascript'>
			jQuery(document).ready(function($) {
				$('.color-picker').wpColorPicker();
			});
		</script>
		<p>
		    <label for="<?php echo $this->get_field_id( 'carousel-nav-color' ); ?>" style="display:block;"><?php _e( 'Carousel Nav Color', 'create' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'carousel-nav-color' ); ?>" name="<?php echo $this->get_field_name( 'carousel-nav-color' ); ?>" type="text" value="<?php echo esc_attr( $carousel_nav_color ); ?>" />
		</p>

<?php
	} // form()

} // TTrust_Testimonials

register_widget( 'TTrust_Testimonials' );
?>