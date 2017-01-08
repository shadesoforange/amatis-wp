<?php

function create_gfp_custom_controls( $wp_customize ) {

	// Description

	class Create_GFP_Tag extends WP_Customize_Control {
		public $type = 'tag';
		public function render_content() {
		?>
			<h3 class="tt_gfp"><?php echo esc_html( $this->label ); ?></h4>
		<?php
		}
	}
	// Autocomplete -- Requires the JS in gfonts.class.php > tt_gfp_js_controllers()

	class Create_GFP_Autocomplete extends WP_Customize_Control {
		public $type = 'autocomplete';
		public function render_content() {
		?>
			<label for="font-family"><?php echo esc_html( $this->label ) ?>: </label>
			<input class="font-family">
		<?php
		}
	}

}

add_action( 'customize_register', 'create_gfp_custom_controls' );
?>