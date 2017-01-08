<?php
/**
 * @package create
 */



/**
* Class portfolio to create a custom widget for displaying projects
*/

class TTrust_Portfolio extends WP_Widget {

	/**
	 * Register widget with Wordpress
	 *
	 * @global mixed $ttrust_config[] stores theme variables
	 */

	function TTrust_Portfolio() {

		global 	$ttrust_config;

		$widget_ops = array( 'classname' => 'create-portfolio', 'description' => __('Display portfolio projects.', 'create' ) );

		// Instantiate parent
		parent::__construct(

			'ttrust_portfolio', // Base ID
			__( 'Create Portfolio', 'create' ), // Name
			 $widget_ops // Args

		); // parent::__construct()

	} // TTrust_Portfolio()

	/**
	 * Front-end display of widget.
	 *
 	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function widget( $args, $instance ) {

			global $ttrust_config;
			global $paged;
			global $portfolio_config;
			
			extract( $args ); 		
			// Get the layout class
			$layout_class = preg_replace('#[ -]+#', '-', $instance['layout']);
			$column_class = 'col-'.$instance['columns'];
			$orderby = $instance['orderby'];
			$order = $instance['order'];
			$count = $instance['count'];
			$show_load_more = $instance['show_load_more'];
			$load_more_color = $instance['load_more_color'];
			$load_more_text_color = $instance['load_more_text_color'];
			$filter_alignment = $instance['filter_alignment'];
			$show_filter = $instance['show_filter'];
			$skills = array();
			$portfolio_config = array();
			$portfolio_config['hover_color'] = $instance['hover_color'];
			$portfolio_config['hover_text_color'] = $instance['hover_text_color'];
			$portfolio_config['hover_effect'] = $instance['hover_effect'];
			$portfolio_config['masonry'] = (strpos($layout_class, 'masonry') === 0) ? true : false;
			$portfolio_config['thumb_proportions'] = $instance['thumb_proportions'];
			$portfolio_config['show_skills'] = $instance['show_skills'];
			$portfolio_config['enable_lightbox'] = $instance['enable_lightbox'];
			
			echo $before_widget;
			?>
			
				<div class="projects <?php echo $layout_class; ?> <?php echo $column_class; ?>">
				
					<?php
						// FilterNav output
						
							if($instance['skills']){
								$skills = array_values( array_filter($instance['skills']) );
							}
							if( sizeof( $skills ) > 0 ) { 
								if($show_filter=="yes"){?>
								<ul id="filter-nav" class="clearfix <?php echo $filter_alignment; ?>">
									<li class="all-btn"><a href="#" data-filter="*" class="selected"><?php _e( 'All', 'create' ); ?></a></li>
									<?php
									foreach( $skills as $skill ) {
										$skill = get_term_by( 'slug', trim(htmlentities($skill)), 'skill');
										if($skill) {
										$output = sprintf( '<li><a href="#" data-filter=".%1$s">%2$s</a></li>%3$s',
												esc_attr( $skill->slug ),
												ucfirst( esc_attr( $skill->name ) ),
												"\n"
											);

										echo $output;
										}
									} // foreach

									?>
								</ul>
								<?php
								}
								$r = new WP_Query( array(
									'post_type' 	=> "project",
									'posts_per_page' 	=> $count,
									'paged' => $paged,
									'post_status'	=> 'publish',
									'orderby' 		=> $orderby,
									'order'         => $order,
									'skill'         => implode(',', $instance['skills'])
								) );
								?>
							<?php
							}else{ 
								if($show_filter=="yes"){?>
								<ul id="filter-nav" class="clearfix <?php echo $filter_alignment; ?>">
									<li class="all-btn"><a href="#" data-filter="*" class="selected"><?php _e('All', 'themetrust'); ?></a></li>
									<?php $j=1;
									$skills = get_terms('skill');
									foreach ($skills as $skill) {
										$a = '<li><a href="#" data-filter=".'.$skill->slug.'">';
								    	$a .= $skill->name;					
										$a .= '</a></li>';
										echo $a;
										echo "\n";
										$j++;
									}?>
								</ul>
							<?php
								}
							$r = new WP_Query( array(
								'post_type' 	=> "project",
								'posts_per_page' 	=> $count,
								'paged' => $paged,
								'post_status'	=> 'publish',
								'orderby' 		=> $orderby,
								'order'         => $order,
							) );
							
							} // if
						
					?>
	
				
					<div class="thumbs clearfix">
						<div class="grid-sizer"></div>						
						<?php  while ( $r->have_posts()) : $r->the_post();
							get_template_part( 'templates/content', 'project-small' ); 
							?>
						<?php endwhile; ?>
					</div><!-- .thumbs -->
					<?php if($instance['show_load_more']=="yes"){ ?>
					<div class="load-more-holder">
						<div class="load-more-button" style="background:<?php echo $load_more_color; ?>; color:<?php echo $load_more_text_color; ?>;" data-rel="<?php echo $r->max_num_pages ?>"><?php echo get_next_posts_link( "Load More", $r->max_num_pages); ?></div>
						<div class="loading hidden button" style="background:<?php echo $load_more_color; ?>; color:<?php echo $load_more_text_color; ?>;"><?php _e( "Loading", 'create'); ?></div>
					</div>
					<?php } ?>
				</div><!-- .projects -->
				<?php echo $after_widget; ?>
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
		$instance['show_filter'] = isset($new_instance['show_filter']) ? strip_tags( $new_instance['show_filter'] ) : '';
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = $new_instance['count'];
		$instance['layout'] = $new_instance['layout'];
		$instance['columns'] = $new_instance['columns'];
		$instance['skills'] = isset($new_instance['skills']) ? $new_instance['skills'] : "";
		$instance['order'] = $new_instance['order'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['hover_color'] = $new_instance['hover_color'];
		$instance['hover_text_color'] = $new_instance['hover_text_color'];
	    $instance['hover_effect'] = $new_instance['hover_effect'];
		$instance['show_skills'] = $new_instance['show_skills'];
		$instance['show_load_more'] = $new_instance['show_load_more'];
		$instance['load_more_color'] = $new_instance['load_more_color'];
		$instance['load_more_text_color'] = $new_instance['load_more_text_color'];
		$instance['thumb_proportions'] = $new_instance['thumb_proportions'];
		$instance['filter_alignment'] = $new_instance['filter_alignment'];
		$instance['enable_lightbox'] = $new_instance['enable_lightbox'];
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
		$show_filter = (isset($instance['show_filter'])) ? esc_attr($instance['show_filter']) : '';
		$count = (isset($instance['count'])) ? esc_attr($instance['count']) : '3';
		$layout = (isset($instance['layout'])) ? esc_attr($instance['layout']) : '';
		$columns = (isset($instance['columns'])) ? esc_attr($instance['columns']) : '';
		$skills = (isset($instance['skills'])) ? $instance['skills'] : array();
		$order = (isset($instance['order'])) ? $instance['order'] : '';
		$orderby = (isset($instance['order'])) ? $instance['orderby'] : '';
		$hover_color = (isset($instance['hover_color'])) ? $instance['hover_color'] : '';
		$hover_text_color = (isset($instance['hover_text_color'])) ? $instance['hover_text_color'] : '';
		$hover_effect = (isset($instance['hover_effect'])) ? $instance['hover_effect'] : '';
		$show_skills = (isset($instance['show_skills'])) ? esc_attr($instance['show_skills']) : '';
		$show_load_more = (isset($instance['show_load_more'])) ? esc_attr($instance['show_load_more']) : '';
		$load_more_color = (isset($instance['load_more_color'])) ? esc_attr($instance['load_more_color']) : '';
		$load_more_text_color = (isset($instance['load_more_text_color'])) ? esc_attr($instance['load_more_text_color']) : '';
		$thumb_proportions = (isset($instance['thumb_proportions'])) ? esc_attr($instance['thumb_proportions']) : '';
		$filter_alignment = (isset($instance['filter_alignment'])) ? esc_attr($instance['filter_alignment']) : '';
		$enable_lightbox = (isset($instance['enable_lightbox'])) ? esc_attr($instance['enable_lightbox']) : '';
		?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Name', 'create' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p>
		    
			<label for="<?php echo $this->get_field_id('show_filter'); ?>"><?php _e( 'Show Filter', 'create' ); ?></label>
			<select name="<?php echo $this->get_field_name('show_filter'); ?>" id="<?php echo $this->get_field_id('show_filter'); ?>" class="widefat">
			<?php
			$options = array('No'=>'no', 'Yes'=>'yes');
			foreach ($options as $option=>$value) {
			$selected = ($show_filter == $value) ? ' selected="selected"' : '';
			echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
			}
			?>
			</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('filter_alignment'); ?>"><?php _e('Filter Alignment', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('filter_alignment'); ?>" id="<?php echo $this->get_field_id('filter_alignment'); ?>" class="widefat">
		<?php
		$options = array('Center'=>'center', 'Left'=>'left', 'Right'=>'right');
		foreach ($options as $option => $value) {
		$selected = ($filter_alignment == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of Projects', 'create' ); ?></label>
		<input type="number" name="<?php echo $this->get_field_name( 'count' ); ?>" min="1" max="99" value="<?php echo $count; ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('thumb_proportions'); ?>"><?php _e('Featured Image Proportions for Non-masonry Layout ', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('thumb_proportions'); ?>" id="<?php echo $this->get_field_id('thumb_proportions'); ?>" class="widefat">
		<?php
		$options = array('Square' => 'square', 'Landscape' => 'landscape', 'Portrait' => 'portrait');
		foreach ($options as $option => $value) {
		$selected = ($thumb_proportions == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('layout'); ?>" id="<?php echo $this->get_field_id('layout'); ?>" class="widefat">
		<?php
		$options = array('Rows with Gutter'=>'rows with gutter', 'Rows without Gutter'=>'rows without gutter', 'Masonry with Gutter'=>'masonry with gutter', 'Masonry without Gutter'=>'masonry without gutter');
		foreach ($options as $option=>$value) {
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
		$options = array('2','3', '4', '5');
		foreach ($options as $option) {
		$selected = ($columns == $option) ? ' selected="selected"' : '';
		echo '<option value="' . $option . '" id="' . $option . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('skills'); ?>"><?php _e('Show only certain skills', 'create'); ?></label>
		<?php
		$terms = get_terms('skill');
		     foreach ( $terms as $term ) { ?>
				<?php $current_skill = (isset($skills[$term->slug])) ? $skills[$term->slug] : ""; ?>
				<span style="display: block;">
		        	<input id="<?php echo $this->get_field_id($term->slug) ?>" name="<?php echo $this->get_field_name('skills'); ?>[<?php echo $term->slug ?>]" type="checkbox" value="<?php echo $term->slug; ?>" <?php if($current_skill){echo 'checked';} ?> /> <?php echo $term->name; ?>
				</span>
			<?php
		     }
		?>
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
		<label for="<?php echo $this->get_field_id('hover_effect'); ?>"><?php _e('Hover Effect', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('hover_effect'); ?>" id="<?php echo $this->get_field_id('hover_effect'); ?>" class="widefat">
		<?php
		$options = array('Effect 1' => 'effect-1', 'Effect 2' => 'effect-2', 'Effect 3' => 'effect-3');
		foreach ($options as $option=>$value) {
		$selected = ($hover_effect == $value) ? ' selected="selected"' : '';
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
		    <label for="<?php echo $this->get_field_id( 'hover_color' ); ?>" style="display:block;"><?php _e( 'Hover Color', 'create' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'hover_color' ); ?>" name="<?php echo $this->get_field_name( 'hover_color' ); ?>" type="text" value="<?php echo esc_attr( $hover_color ); ?>" />
		</p>
		
		<p>
		    <label for="<?php echo $this->get_field_id( 'hover_text_color' ); ?>" style="display:block;"><?php _e( 'Hover Text Color', 'create' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'hover_text_color' ); ?>" name="<?php echo $this->get_field_name( 'hover_text_color' ); ?>" type="text" value="<?php echo esc_attr( $hover_text_color ); ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('show_skills'); ?>"><?php _e('Show Skills on Hover', 'create'); ?></label>
		<select name="<?php echo $this->get_field_name('show_skills'); ?>" id="<?php echo $this->get_field_id('show_skills'); ?>" class="widefat">
		<?php
		$options = array('No'=>'no', 'Yes'=>'yes');
		foreach ($options as $option=>$value) {
		$selected = ($show_skills == $value) ? ' selected="selected"' : '';
		echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
		}
		?>
		</select>
		</p>
		
		<p>		    
			<label for="<?php echo $this->get_field_id('show_load_more'); ?>"><?php _e( 'Show Load More Button', 'create' ); ?></label>
			<select name="<?php echo $this->get_field_name('show_load_more'); ?>" id="<?php echo $this->get_field_id('show_load_more'); ?>" class="widefat">
			<?php
			$options = array('No'=>'no', 'Yes'=>'yes');
			foreach ($options as $option=>$value) {
			$selected = ($show_load_more == $value) ? ' selected="selected"' : '';
			echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
			}
			?>
			</select>
		</p>
		
		<p>
		    <label for="<?php echo $this->get_field_id( 'load_more_color' ); ?>" style="display:block;"><?php _e( 'Load More Button Color', 'create' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'load_more_color' ); ?>" name="<?php echo $this->get_field_name( 'load_more_color' ); ?>" type="text" value="<?php echo esc_attr( $load_more_color ); ?>" />
		</p>
		
		<p>
		    <label for="<?php echo $this->get_field_id( 'load_more_text_color' ); ?>" style="display:block;"><?php _e( 'Load More Button Text Color', 'create' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'load_more_text_color' ); ?>" name="<?php echo $this->get_field_name( 'load_more_text_color' ); ?>" type="text" value="<?php echo esc_attr( $load_more_text_color ); ?>" />
		</p>
		
		<p>		    
			<label for="<?php echo $this->get_field_id('enable_lightbox'); ?>"><?php _e( 'Enable Lightbox', 'create' ); ?></label>
			<select name="<?php echo $this->get_field_name('enable_lightbox'); ?>" id="<?php echo $this->get_field_id('enable_lightbox'); ?>" class="widefat">
			<?php
			$options = array('No'=>'no', 'Yes'=>'yes');
			foreach ($options as $option=>$value) {
			$selected = ($enable_lightbox == $value) ? ' selected="selected"' : '';
			echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>'. $option . '</option>';
			}
			?>
			</select>
		</p>

<?php
	} // form()

} // TTrust_Portfolio

register_widget( 'TTrust_Portfolio' );
?>