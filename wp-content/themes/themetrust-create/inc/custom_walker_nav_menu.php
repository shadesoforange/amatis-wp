<?php
/**
 *
 * Main Menu Walker
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'Walker_Nav_Menu_Custom' ) ){


  /**
   * Copied from WordPress 3.7 Core
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  class Walker_Nav_Menu_Custom extends Walker_Nav_Menu {

    var $child_count       = 0;
    var $is_custom_width   = false;
    var $menu_type         = array();

    function start_lvl( &$output, $depth = 0, $args = array() ) {

      $style   = ( $this->is_custom_width ) ? ' style="width: '. $this->is_custom_width .'px"' : '';
      $indent  = str_repeat( "\t", $depth );
      $output .= "\n$indent<ul class=\"sub-menu\"". $style .">\n";

    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {

      $indent  = str_repeat( "\t", $depth );
      $output .= "$indent</ul>\n";

    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

      $class_names    = '';
      $value          = '';
      $indent         = ( $depth ) ? str_repeat( "\t", $depth ) : '';
      $classes        = empty( $item->classes ) ? array() : $item->classes;
      $classes[]      = 'menu-item-' . $item->ID;

      // adding depth class
      $classes[]      = 'depth-'. $depth;

	  //adding if link is disabled
	  $classes[]       = ( !empty( $item->column_title_link ) ) ? 'no-link' : '';

      // adding if mega menu class
      $classes[]      = ( $depth == 0 && ! empty( $item->mega ) ) ? 'mega-menu' : '';

	  // adding if full-width class
      $classes[]      = ( $depth == 0 && ! empty( $item->mega ) && $item->mega_width == 'full-width' ) ? 'col-'.$this->child_count . ' '. $item->mega_width : '';

      // adding if natural-width class
      $classes[]      = ( $depth == 0 && ! empty( $item->mega ) && $item->mega_width == 'natural' ) ? 'col-'.$this->child_count . ' '. $item->mega_width : '';

      // adding if custom-width class
      $classes[]      = ( $depth == 0 && ! empty( $item->mega ) && $item->mega_width == 'custom' ) ? ''. $item->mega_width : '';

      // adding if right position class
      $classes[]      = ( $depth == 0 && ! empty( $item->mega ) && ! empty( $item->mega_width ) && ! empty( $item->mega_position ) ) ? 'right' : '';

      // adding bootstrap col if parent item is mega!
      if( $depth == 1 && isset( $this->menu_type[$item->menu_item_parent] ) ) {
        $bs_col = create_get_bootstrap( $this->child_count );
      }
      $classes['col'] = ( ! empty( $bs_col ) ) ? $bs_col : '';

      // adding force custom boostrap col
      $classes['col'] = ( $depth == 1 && ! empty( $item->column_width ) ) ? $item->column_width : $classes['col'];

      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
      $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

      $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
      $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

      $output .= $indent . '<li' . $id . $value . $class_names .'>';

      $atts = array();
      $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
      $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
      $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
      $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

      $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

      $attributes = '';
      foreach ( $atts as $attr => $value ) {
        if ( ! empty( $value ) ) {
          $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
          $attributes .= ' ' . $attr . '="' . $value . '"';
        }
      }

      $item_output = $args->before;

      

        // if column title link not disable
        if( empty( $item->column_title_link ) ) {

          $create_hover_effect  = "";

          $is_mega_column   = ( isset( $this->menu_type[$item->menu_item_parent] ) ) ? ' title' : '';

          $item_output .= '<a'. $attributes .' class="link link-depth-'. $depth . $is_mega_column .'">';

        } else if( $depth == 1 && ! empty( $item->column_title_link ) ) {
          $item_output .= '<a class="link title column-title">'. $item->colum_title;
        }

        // adding icon
        $item_output .= ( ! empty( $item->icon ) ) ? '<i class="'. create_icon_class( $item->icon ) .'"></i>' : '';

		// if column title not disable
	    if( empty( $item->column_title ) ) {
        	$item_output .= $args->link_before . '<span>'.apply_filters( 'the_title', $item->title, $item->ID ) . '</span>' . $args->link_after;
		}

        // if column title link not disable
        if( empty( $item->column_title_link ) || ( $depth == 1 && ! empty( $item->column_title_link ) ) ) {
          $item_output .= '</a>';
        }

      $item_output .= $args->after;

      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {

      if( !empty( $element->mega ) ) {
        $this->child_count = ( isset( $children_elements[$element->ID] ) ) ? count( $children_elements[$element->ID] ) : 0;
        $this->menu_type[$element->ID]  = true;
      }

      if( $depth == 0 && !empty( $element->mega ) && $element->mega_width == 'custom' ) {
        $this->is_custom_width = $element->mega_custom_width;
      } else {
        $this->is_custom_width = false;
      }

      parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);

    }

    public function custom_wrap(){
		$output = '';
	   	return '<ul id="%1$s" class="%2$s">%3$s'. $output .'</ul>';
    }

  } // Walker_Nav_Menu
}


if( !class_exists('Walker_Nav_Menu_Edit_Custom') ){
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

  function start_lvl( &$output, $depth = 0, $args = array() ) {}

  function end_lvl( &$output, $depth = 0, $args = array() ) {}

  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    global $_wp_nav_menu_max_depth;
    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

    ob_start();
    $item_id = esc_attr( $item->ID );
    $removed_args = array(
      'action',
      'customlink-tab',
      'edit-menu-item',
      'menu-item',
      'page-tab',
      '_wpnonce',
    );

    $original_title = '';
    if ( 'taxonomy' == $item->type ) {
      $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
      if ( is_wp_error( $original_title ) )
        $original_title = false;
    } elseif ( 'post_type' == $item->type ) {
      $original_object = get_post( $item->object_id );
      $original_title = get_the_title( $original_object->ID );
    }

    $classes = array(
      'menu-item menu-item-depth-' . $depth,
      'menu-item-' . esc_attr( $item->object ),
      'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
    );

    $title = $item->title;

    if ( ! empty( $item->_invalid ) ) {
      $classes[] = 'menu-item-invalid';
      $title = sprintf( '%s (Invalid)', $item->title );
    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
      $classes[] = 'pending';
      $title = sprintf( '%s (Pending)', $item->title );
    }

    $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

    $submenu_text = '';
    if ( 0 == $depth )
      $submenu_text = 'style="display: none;"';

    ?>
    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
      <dl class="menu-item-bar">
        <dt class="menu-item-handle">
          <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php echo 'sub item'; ?></span></span>
          <span class="item-controls">
            <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>

            <!-- Mega Labels Action -->
            <?php do_action('create_mega_menu_labels'); ?>
            <!-- /Mega Labels Action -->

            <span class="item-order hide-if-js">
              <a href="<?php
                echo esc_url( wp_nonce_url(
                  add_query_arg(
                    array(
                      'action' => 'move-up-menu-item',
                      'menu-item' => $item_id,
                    ),
                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                  ),
                  'move-menu_item'
                ) );
              ?>" class="item-move-up"><abbr title="<?php echo 'Move up'; ?>">&#8593;</abbr></a>
              |
              <a href="<?php
                echo esc_url( wp_nonce_url(
                  add_query_arg(
                    array(
                      'action' => 'move-down-menu-item',
                      'menu-item' => $item_id,
                    ),
                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                  ),
                  'move-menu_item'
                ) );
              ?>" class="item-move-down"><abbr title="<?php echo 'Move down'; ?>">&#8595;</abbr></a>
            </span>
            <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php echo 'Edit Menu Item'; ?>" href="<?php
              echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url( add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
            ?>"><?php echo 'Edit Menu Item'; ?></a>
          </span>
        </dt>
      </dl>

      <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
        <?php if( 'custom' == $item->type ) : ?>
          <p class="field-url description description-wide">
            <label for="edit-menu-item-url-<?php echo $item_id; ?>">
              <?php echo 'URL'; ?><br />
              <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
            </label>
          </p>
        <?php endif; ?>
        <p class="description description-thin">
          <label for="edit-menu-item-title-<?php echo $item_id; ?>">
            <?php echo 'Navigation Label'; ?><br />
            <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
          </label>
        </p>
        <p class="description description-thin">
          <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
            <?php echo 'Title Attribute'; ?><br />
            <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
          </label>
        </p>
        <p class="field-link-target description">
          <label for="edit-menu-item-target-<?php echo $item_id; ?>">
            <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
            <?php echo 'Open link in a new window/tab'; ?>
          </label>
        </p>
        <p class="field-css-classes description description-thin">
          <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
            <?php echo 'CSS Classes (optional)'; ?><br />
            <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
          </label>
        </p>
        <p class="field-xfn description description-thin">
          <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
            <?php echo 'Link Relationship (XFN)'; ?><br />
            <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
          </label>
        </p>

        <!-- Mega Menu Fields-->
        <?php do_action('create_mega_menu_fields', $item_id, $item); ?>
        <!-- /Mega Menu Fields-->

        <p class="field-move hide-if-no-js description description-wide">
          <label>
            <span><?php echo 'Move'; ?></span>
            <a href="#" class="menus-move-up"><?php echo 'Up one'; ?></a>
            <a href="#" class="menus-move-down"><?php echo 'Down one'; ?></a>
            <a href="#" class="menus-move-left"></a>
            <a href="#" class="menus-move-right"></a>
            <a href="#" class="menus-move-top"><?php echo 'To the top'; ?></a>
          </label>
        </p>

        <?php
          // Nav Menu Roles Plugin conflict fixing here.
          if ( class_exists( 'Nav_Menu_Roles' ) ) {
            do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args );
          }
        ?>

        <div class="menu-item-actions description-wide submitbox">
          <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
            <p class="link-to-original">
              <?php printf( 'Original: %s', '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
            </p>
          <?php endif; ?>
          <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
          echo esc_url( wp_nonce_url(
            add_query_arg(
              array(
                'action' => 'delete-menu-item',
                'menu-item' => $item_id,
              ),
              admin_url( 'nav-menus.php' )
            ),
            'delete-menu_item_' . $item_id
          ) ); ?>"><?php echo 'Remove'; ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
            ?>#menu-item-settings-<?php echo $item_id; ?>"><?php echo 'Cancel'; ?></a>
        </div>

        <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
        <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
        <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
        <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
        <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
        <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
      </div><!-- .menu-item-settings-->
      <ul class="menu-item-transport"></ul>
    <?php
    $output .= ob_get_clean();
  }

} // Walker_Nav_Menu_Edit
}