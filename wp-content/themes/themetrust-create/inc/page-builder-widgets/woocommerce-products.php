<?php
/**
 * @package create
 */



/**
* Class to create a custom widget for displaying blog
*/

class TTrust_Products extends WP_Widget {

	/**
	 * Register widget with Wordpress
	 *
	 * @global mixed $ttrust_config[] stores theme variables
	 */

	function TTrust_Products() {

		global 	$ttrust_config;

		$widget_ops = array( 'classname' => 'create-products', 'description' => __('Display Woocommerce Products.', 'create' ) );

		// Instantiate parent
		parent::__construct(

			'ttrust_products', // Base ID
			__( 'Create Woocommerce Products', 'create' ), // Name
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
			
			global $products_config;		

			// Get the layout class
			$layout_class = preg_replace('#[ -]+#', '-', $instance['layout']);
			$column_class = 'col'.$instance['columns'];
			$alignment_class = 'alignment-'.$instance['alignment'];
			$orderby = $instance['orderby'];
			$order = $instance['order'];
			$show_featured = $instance['show_featured'];
			$categories = array_values( array_filter($instance['categories']) );
			$categories = implode(',', $categories);
		
			$meta_key = '';
			$meta_value = '';
			if($show_featured == "yes") {
				$meta_key = '_featured';
				$meta_value = 'yes';
			}
			
			echo $before_widget;
			
			$products_config = array();
			
			
			?>
				<div id="<?php echo $args['widget_id']; ?>" class="shop products clear <?php echo $layout_class; ?> <?php echo $column_class; ?> <?php echo $alignment_class; ?>">

				<?php
				$r = new WP_Query( array(
						'post_type' 	=> "product",
						'showposts' 	=> $instance['count'],
						'nopaging' 		=> 0,
						'post_status'	=> 'publish',
						'product_cat'   => $categories,
						'meta_key' => $meta_key,
						'meta_value' => $meta_value,
						'orderby' 		=> $orderby,
						'order'         => $order
					) );
				?>
						<?php  while ( $r->have_posts()) : $r->the_post();
							get_template_part( 'templates/content', 'product-small' ); 
						?>
						<?php endwhile; ?>
				</div><!-- .products -->
				<?php echo $after_widget; ?>
				<?php if($instance['layout']=='carousel'){ ?>
					<script type="text/javascript">
					//<![CDATA[
					jQuery(document).ready(function(){		
						jQuery('#<?php echo $args['widget_id']; ?>.products.carousel').owlCarousel({
						    items : <?php echo $instance['columns']; ?>,							
							itemsDesktop : [1199,<?php echo $instance['columns']; ?>],
							itemsDesktopSmall : [980,3],
							itemsTablet: [768,2],
							itemsTabletSmall: false,
							itemsMobile : [479,1]						
						})
						jQuery('head').append('<style type="text/css">.products.carousel .owl-controls .owl-pagination .owl-page span {background-color: <?php echo $instance["carousel-nav-color"]; ?>;}</style>');
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
		$instance['alignment'] = $new_instance['alignment'];
		$instance['columns'] = $new_instance['columns'];
		$instance['show_featured'] = $new_instance['show_featured'];
		$instance['order'] = $new_instance['order'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['carousel-nav-color'] = $new_instance['carousel-nav-color'];
		$instance['categories'] = isset($new_instance['categories']) ? $new_instance['categories'] : array();
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
		$alignment = (isset($instance['alignment'])) ? esc_attr($instance['alignment']) : '';
		$columns = (isset($instance['columns'])) ? esc_attr($instance['columns']) : '';
		$show_featured = (isset($instance['show_featured'])) ? esc_attr($instance['show_featured']) : '';
		$order = (isset($instance['order'])) ? $instance['order'] : '';
		$orderby = (isset($instance['order'])) ? $instance['orderby'] : '';
		$categories = isset($instance['categories']) ? $instance['categories'] : array();
		$carousel_nav_color = (isset($instance['carousel-nav-color'])) ? esc_attr($instance['carousel-nav-color']) : '';
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Name', 'create' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of Products', 'create' ); ?></label>
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
		$options = array('2', '3', '4', '5');
		foreach ($options as $option) {
		$selected = ($columns == $option) ? ' selected="selected"' : '';
		echo '<option value="' . $option . '" id="' . $option . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
		<?php
		$options = array('Date' => 'date', 'Title' => 'title');
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
		<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Show only certain categories', 'create'); ?></label>
		<?php
		$terms = get_terms('product_cat');
		     foreach ( $terms as $term ) { ?>
				<?php $current_cat = (isset($categories[$term->slug])) ? $categories[$term->slug] : ""; ?>
				<span style="display: block;">
		        	<input id="<?php echo $this->get_field_id($term->slug) ?>" name="<?php echo $this->get_field_name('categories'); ?>[<?php echo $term->slug ?>]" type="checkbox" value="<?php echo $term->slug; ?>" <?php if($current_cat){echo 'checked';} ?> /> <?php echo $term->name; ?>
				</span>
			<?php
		     }
		?>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Show Featured', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('show_featured'); ?>" id="<?php echo $this->get_field_id('show_featured'); ?>" class="widefat">
		<?php
		$options = array('No'=>'no', 'Yes'=>'yes');
		foreach ($options as $option => $value) {
		$selected = ($show_featured == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
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

} // TTrust_Products

register_widget( 'TTrust_Products' );
?>