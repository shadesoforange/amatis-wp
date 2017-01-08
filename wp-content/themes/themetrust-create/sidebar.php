<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package create
 */

$id = get_the_ID();
$custom_sidebar = get_post_meta( $id, '_create_sidebar_custom_widget_area', true );



if($custom_sidebar != ""){
	$sidebar = $custom_sidebar;
}else{
	if(is_archive() && is_active_sidebar('sidebar')) : $sidebar = 'sidebar';
	elseif(is_home() && is_active_sidebar('sidebar')) : $sidebar = 'sidebar';
	elseif(is_single() && is_active_sidebar('sidebar')) : $sidebar = 'sidebar';
	elseif(is_page() && is_active_sidebar('sidebar')) : $sidebar = 'sidebar';
	elseif(is_search() && is_active_sidebar('sidebar')) : $sidebar = 'sidebar';		
	else : $sidebar = 'sidebar_default';
	endif; 
}

?>

<aside class="sidebar">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside>
