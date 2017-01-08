<?php
/**
 * @package create
 */
$id = get_the_ID();
$custom_sidebar = get_post_meta( $id, '_create_footer_custom_widget_area', true );
$footer_hide = get_post_meta( $id, '_create_footer_hide', true );
$footer_columns = get_theme_mod( 'create_footer_columns', '3' );
$footer_class = '';
$footer_class .= 'col-'. $footer_columns;
?>
</div> <!-- end middle -->	
	<?php if ($footer_hide != "yes") {?>
	<footer id="footer" class="<?php echo $footer_class; ?>">
		<div class="inside clear">
			
			<?php
			if($custom_sidebar != ""){
				$sidebar = $custom_sidebar;
			}else{
				$sidebar = 'footer';
			}
			?>
			
			<?php if( is_active_sidebar( $sidebar ) ) { ?>
			<div class="main clear">
					<?php dynamic_sidebar( $sidebar ); ?>
			</div><!-- end footer main -->

			<?php } ?>
			
			
			<div class="secondary">

				<?php $footer_left = get_theme_mod( 'create_footer_left' ); ?>
				<?php $footer_right = get_theme_mod( 'create_footer_right' ); ?>
				<div class="left"><p><?php if( $footer_left ){ echo( $footer_left ); } else{ ?>&copy; <?php echo date( 'Y' );?> <a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a> All Rights Reserved.<?php }; ?></p></div>
				<?php if($footer_right) { ?>
				<div class="right"><p><?php echo $footer_right; ?></p></div>
				<?php } ?>
			</div><!-- end footer secondary-->
		</div><!-- end footer inside-->
	</footer>
	<?php } ?>
	
</div> <!-- end main-container -->
</div> <!-- end site-wrap -->
<?php wp_footer(); ?>

</body>
</html>