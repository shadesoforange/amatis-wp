<?php
/**
 * @package create
 */



/**
* Class to create a custom widget for adding vertical space
*/

class TTrust_Spacer extends WP_Widget {

	/**
	 * Register widget with Wordpress
	 *
	 * @global mixed $ttrust_config[] stores theme variables
	 */

	function TTrust_Spacer() {

		global 	$ttrust_config;

		$widget_ops = array( 'classname' => 'create-spacer', 'description' => __('Adds empty vertical space.', 'create' ) );

		// Instantiate parent
		parent::__construct(

			'ttrust_spacer', // Base ID
			__( 'Create Spacer', 'create' ), // Name
			 $widget_ops // Args

		); // parent::__construct()

	} // TTrust_Spacer()

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
			$min_height = $instance['min_height'];
			echo $before_widget;
			?>
				<div id="<?php echo $args['widget_id']; ?>" class="create-spacer" style="height: <?php echo $min_height;?>px;"></div>
			<?php
		echo $after_widget; 

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
		$instance['min_height'] = $new_instance['min_height'];
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
		$min_height = (isset($instance['min_height'])) ? esc_attr($instance['min_height']) : '50';
		?>
		
		<p>
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Height in pixels', 'create' ); ?></label>
		<input type="number" name="<?php echo $this->get_field_name( 'min_height' ); ?>" min="1" max="99" value="<?php echo $min_height; ?>">
		</p>

<?php
	} // form()

} // TTrust_Spacer

register_widget( 'TTrust_Spacer' );
?>