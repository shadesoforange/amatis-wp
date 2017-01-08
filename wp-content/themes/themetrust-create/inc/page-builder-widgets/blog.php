<?php
/**
 * @package create
 */



/**
* Class to create a custom widget for displaying blog
*/

class TTrust_Blog extends WP_Widget {

	/**
	 * Register widget with Wordpress
	 *
	 * @global mixed $ttrust_config[] stores theme variables
	 */

	function TTrust_Blog() {

		global 	$ttrust_config;

		$widget_ops = array( 'classname' => 'create-blog', 'description' => __('Display blog.', 'create' ) );

		// Instantiate parent
		parent::__construct(

			'ttrust_blog', // Base ID
			__( 'Create Blog', 'create' ), // Name
			 $widget_ops // Args

		); // parent::__construct()

	} // TTrust_Blog()

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
			
			global $recent_posts_config;		

			// Get the layout class
			$layout_class = preg_replace('#[ -]+#', '-', $instance['layout']);
			$column_class = 'col-'.$instance['columns'];
			$alignment_class = 'alignment-'.$instance['alignment'];
			$category = (isset($instance['category'])) ? esc_attr($instance['category']) : '';
			$orderby = $instance['orderby'];
			$order = $instance['order'];
			$show_excerpt = $instance['show_excerpt'];
			echo $before_widget;
			
			$recent_posts_config = array();
			$recent_posts_config['show_excerpt'] = $instance['show_excerpt'];
			
			?>
				<div id="<?php echo $args['widget_id']; ?>" class="blog clear <?php echo $layout_class; ?> <?php echo $column_class; ?> <?php echo $alignment_class; ?>">

				<?php
				$r = new WP_Query( array(
						'post_type' 	=> "post",
						'showposts' 	=> $instance['count'],
						'nopaging' 		=> 0,
						'cat'           => $category,
						'post_status'	=> 'publish',
						'orderby' 		=> $orderby,
						'order'         => $order
					) );
				?>
						<?php  while ( $r->have_posts()) : $r->the_post();
							get_template_part( 'templates/content', 'post-small' ); 
						?>
						<?php endwhile; ?>
				</div><!-- .blog -->
				<?php echo $after_widget; ?>
				<?php if($instance['layout']=='carousel'){ ?>
					<script type="text/javascript">
					//<![CDATA[
					jQuery(document).ready(function(){		
						jQuery('#<?php echo $args['widget_id']; ?>.blog.carousel').owlCarousel({
						    items : <?php echo $instance['columns']; ?>,							
							itemsDesktop : [1199,<?php echo $instance['columns']; ?>],
							itemsDesktopSmall : [980,3],
							itemsTablet: [768,2],
							itemsTabletSmall: false,
							itemsMobile : [479,1]						
						})
						jQuery('head').append('<style type="text/css">.blog.carousel .owl-controls .owl-pagination .owl-page span {background-color: <?php echo $instance["carousel-nav-color"]; ?>;}</style>');
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
		$instance['category'] = $new_instance['category'];
		$instance['columns'] = $new_instance['columns'];
		$instance['alignment'] = $new_instance['alignment'];
		$instance['order'] = $new_instance['order'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? strip_tags( $new_instance['show_excerpt'] ) : '';
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
		$category = (isset($instance['category'])) ? esc_attr($instance['category']) : '';
		$columns = (isset($instance['columns'])) ? esc_attr($instance['columns']) : '';
		$alignment = (isset($instance['alignment'])) ? esc_attr($instance['alignment']) : '';
		$order = (isset($instance['order'])) ? $instance['order'] : '';
		$orderby = (isset($instance['order'])) ? $instance['orderby'] : '';
		$show_excerpt = (isset($instance['show_excerpt'])) ? esc_attr($instance['show_excerpt']) : '';
		$carousel_nav_color = (isset($instance['carousel-nav-color'])) ? esc_attr($instance['carousel-nav-color']) : '';
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Name', 'create' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of Posts', 'create' ); ?></label>
		<input type="number" name="<?php echo $this->get_field_name( 'count' ); ?>" min="1" max="99" value="<?php echo $count; ?>">

		<p>
		<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category (leave blank to show all)', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" class="widefat">
		<option value=""></option>
		<?php
		$terms = get_terms('category');
		foreach ( $terms as $term ) { 
		$selected = ($category == $term->term_id) ? ' selected="selected"' : '';
		echo '<option value="' . $term->term_id . '" id="' . $this->get_field_id($term->slug)  . '"' . $selected . '>'. $term->name . '</option>';
		}
		?>
		</select>
		</p>
		
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
		<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
		<?php
		$options = array('Date' => 'date', 'Custom' => 'menu_order', 'Title' => 'title');
		foreach ($options as $option => $value) {
		$selected = ($orderby == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
		<?php
		$options = array('DESC' => 'DESC', 'ASC' => 'ASC');
		foreach ($options as $option => $value) {
		$selected = ($order == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		    
			<label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e( 'Show Excerpt', 'create' ); ?></label>
			<select name="<?php echo $this->get_field_name('show_excerpt'); ?>" id="<?php echo $this->get_field_id('show_excerpt'); ?>" class="widefat">
			<?php
			$options = array('No'=>'no', 'Yes'=>'yes');
			foreach ($options as $option=>$value) {
			$selected = ($show_excerpt == $value) ? ' selected="selected"' : '';
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

} // TTrust_Blog

register_widget( 'TTrust_Blog' );
?>