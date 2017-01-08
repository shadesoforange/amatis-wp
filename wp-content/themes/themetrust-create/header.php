<?php
// @package create
global $woocommerce;
$header_position              = get_theme_mod( 'create_header_position', 'header-top' );
$slide_bg                     = get_theme_mod( 'create_slide_panel_background' );
$header_transparent_bg        = get_theme_mod( 'create_slide_panel_background' );
$header_color_scheme          = get_theme_mod( 'create_header_color_scheme' );
$show_header_search           = get_theme_mod( 'create_enable_header_search' );
$show_slide_panel             = get_theme_mod( 'create_enable_slide_panel' );
$header_layout                = get_theme_mod( 'create_header_top_layout', 'inline-header' );

//Grab the metabox values
$header_class = "main ";
if(!is_archive() && !is_search()) {
$id = get_the_ID();
$header_hide = get_post_meta( $id, '_create_header_hide', true );
$header_transparent_bg = get_post_meta( $id, '_create_header_transparent_bg', true );
$header_color_scheme_metabox = get_post_meta( $id, '_create_header_color_scheme', true );
$header_color_scheme = ($header_color_scheme != $header_color_scheme_metabox) ? $header_color_scheme_metabox : $header_color_scheme;
if($header_transparent_bg == "yes"){ $header_class .= "transparent "; }
$header_class .= $header_color_scheme;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
	<?php create_loader(); ?>
	<?php create_scroll_to_top(); ?>
	
	<?php if(!isset($header_hide) || $header_hide != "yes") { ?>
	<!-- Slide Panel -->
	<div id="slide-panel"<?php if( $slide_bg ){ echo ' style="background-image: url(' . $slide_bg . ');"'; } ?>>
		<div class="hidden-scroll">
			<div class="inner <?php if(has_nav_menu('slide_panel_mobile')) echo 'has-mobile-menu'; ?>">	
				<?php wp_nav_menu( array(
					'container'			=> 'nav',
					'container_id'		=> 'slide-main-menu',
					'menu_class'        => 'collapse sidebar',
					'theme_location'	=> 'slide_panel',
					'fallback_cb' 		=> 'create_slide_nav'
				) ); ?>
				
				<?php wp_nav_menu( array(
					'container'			=> 'nav',
					'container_id'		=> 'slide-mobile-menu',
					'menu_class'        => 'collapse sidebar',
					'theme_location'	=> 'slide_panel_mobile',
					'fallback_cb' 		=> 'create_slide_nav',
					'menu' => get_post_meta( $id, '_create_header_menu_mobile', true)
				) ); ?>

				<?php if ( is_active_sidebar( 'slide_panel' ) ) : ?>
					<div class="widget-area desktop" role="complementary">
						<?php dynamic_sidebar( 'slide_panel' ); ?>
					</div><!-- .widget-area-desktop -->
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'slide_panel_mobile' ) ) : ?>
					<div class="widget-area mobile" role="complementary">
						<?php dynamic_sidebar( 'slide_panel_mobile' ); ?>
					</div><!-- .widget-area-mobile -->
				<?php endif; ?>
			</div><!-- .inner -->
		</div>
		<span id="menu-toggle-close" class="menu-toggle right close slide" data-target="slide-panel"><span></span></span>
	</div><!-- /slide-panel-->	
	<?php } ?>
	
	
<div id="site-wrap">
<?php if($header_position == "side-header"){ // Side positioned header ?>
	
	<?php if(!isset($header_hide) || $header_hide != "yes") { ?>
	<header id="site-header">
		<div class="inside clearfix">
			<?php $logo_head_tag = ( is_front_page() ) ? "h1" : "h2"; ?>
			<?php $ttrust_logo_side = get_theme_mod( 'create_logo_side' ); ?>
			<?php $ttrust_logo_top = get_theme_mod( 'create_logo_top' ); ?>

			<div id="logo">

			<?php if( $ttrust_logo_side ) { ?>
				<<?php echo $logo_head_tag; ?> class="site-title side"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo $ttrust_logo_side; ?>" alt="<?php bloginfo('name'); ?>" /></a></<?php echo $logo_head_tag; ?>>
				<?php if( $ttrust_logo_top ) { //show the top header logo on smal devices ?>
					<<?php echo $logo_head_tag; ?> class="site-title top"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo $ttrust_logo_top; ?>" alt="<?php bloginfo('name'); ?>" /></a></<?php echo $logo_head_tag; ?>>
				<?php } ?>
			<?php } else { ?>
				<<?php echo $logo_head_tag; ?> class="site-title normal"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></<?php echo $logo_head_tag; ?>>
			<?php } ?>
			
			</div>

			<div class="main-nav woocommerce">

				<?php wp_nav_menu( array(
					'container'			=> 'nav',
					'container_id'		=> 'main-menu',
					'menu_class' 		=> 'sf-menu clear',
					'theme_location'	=> 'primary',
					'fallback_cb' 		=> 'create_main_nav',
					'menu' => get_post_meta( $id, '_create_header_menu_main', true)
				) ); ?>
				
			</div>
			
			<?php if ( is_active_sidebar( 'header-sidebar' ) ) : // The sidebar in the side header ?>
				<div id="widget-area" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'header-sidebar' ); ?>
				</div><!-- .widget-area -->
			<?php endif; ?>
			
		</div>

	</header><!-- #site-header -->
	<?php } ?>
	
<?php } ?>
<div id="main-container">
	<?php if($header_position != "side-header"){ // Top positioned header ?>
			<?php if(!isset($header_hide) || $header_hide != "yes") { ?>
			<header id="site-header" class="<?php echo $header_class; ?>">
				<?php if($show_header_search == "yes") { ?>
				<div id="header-search" class="header-search">
					<div class="inside">
						<div class="form-wrap">
						<form role="search" method="get" id="searchform" class="searchform clear" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php $search_text = __("Type and press enter to search.", "create"); ?> 
							<input type="text" placeholder="<?php echo $search_text; ?>" name="s" id="s" />
							<span id="search-toggle-close" class="search-toggle right close" data-target="header-search" ></span>
						</form>
						
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="inside logo-and-nav clearfix">

					<?php $logo_head_tag = ( is_front_page() ) ? "h1" : "h2"; ?>
					<?php $ttrust_logo_top = ($header_color_scheme == "light") ? get_theme_mod( 'create_logo_top_light' ) : get_theme_mod( 'create_logo_top' ); ?>
					<?php $ttrust_logo_sticky = get_theme_mod( 'create_logo_sticky' ); ?>
					
					<div id="logo" class="<?php if($ttrust_logo_sticky) echo 'has-sticky-logo'; ?>">
					<?php if( $ttrust_logo_top ) { ?>
						<<?php echo $logo_head_tag; ?> class="site-title"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo $ttrust_logo_top; ?>" alt="<?php bloginfo('name'); ?>" /></a></<?php echo $logo_head_tag; ?>>
					<?php } else { ?>
						<<?php echo $logo_head_tag; ?> class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></<?php echo $logo_head_tag; ?>>
					<?php } ?>

					<?php if( $ttrust_logo_sticky ) { ?>
						<<?php echo $logo_head_tag; ?> class="site-title sticky"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo $ttrust_logo_sticky; ?>" alt="<?php bloginfo('name'); ?>" /></a></<?php echo $logo_head_tag; ?>>
					<?php } else { ?>
						<<?php echo $logo_head_tag; ?> class="site-title sticky"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></<?php echo $logo_head_tag; ?>>
					<?php } ?>
					</div>
					
					<?php if($header_layout=="split-header inline-header"){?>
					<div class="main-nav left clearfix">
					<?php wp_nav_menu( array(
						'container'			=> 'nav',
						'container_id'		=> 'left-menu',
						'menu_class' 		=> 'main-navigation sf-menu clear',
						'theme_location'	=> 'left',
						'fallback_cb' 		=> 'create_main_nav',
						'menu' => get_post_meta( $id, '_create_header_menu_left', true)
					) ); ?>
					</div>
					<?php } ?>

					<div class="nav-holder">
					
						<div class="main-nav clearfix">
						<?php wp_nav_menu( array(
							'container'			=> 'nav',
							'container_id'		=> 'main-menu',
							'menu_class' 		=> 'main-navigation sf-menu clear',
							'theme_location'	=> 'primary',
							'fallback_cb' 		=> 'create_main_nav',
							'menu' => get_post_meta( $id, '_create_header_menu_main', true)
						) ); ?>
						</div>
						
						<div class="secondary-nav clearfix">
							
							<?php if($woocommerce) { ?>
							<a class="cart-icon right open" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'create'); ?>">
							<?php if($woocommerce->cart->cart_contents_count > 0){?>
							<span class="cart-count"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
							<?php } ?>
							</a>
							<?php } ?>
							
							<?php if($show_header_search == "yes") { ?>
							<span id="search-toggle-open" class="search-toggle right open" data-target="header-search" ></span>
							<?php } ?>
							
							<span id="menu-toggle-open" class="menu-toggle right open slide <?php if($show_slide_panel == "yes") echo 'constant'; ?>" data-target="slide-menu" ></span>
							
						
						</div>
					</div>
						
				</div>
	
			</header><!-- #site-header -->
			<?php } ?>
		<?php } ?>
	<div id="middle">