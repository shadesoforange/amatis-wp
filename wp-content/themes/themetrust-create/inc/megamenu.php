<?php
/**
 *
 * Create Mega Menu 
 * @since 1.0.0
 * @version 1.0.0
 *
 */
locate_template( 'inc/custom_walker_nav_menu.php', true );

class Create_Megamenu extends TT_Abstract{

  public $extra_fields = array( 'highlight', 'highlight_type', 'icon', 'mega', 'mega_width', 'mega_position', 'mega_custom_width', 'column_title', 'column_title_link', 'column_width', 'content' );
  public $walker = null;

  public function __construct() {

    $this->addFilter( 'wp_nav_menu_args', 'wp_nav_menu_args', 99 );
    $this->addFilter( 'wp_edit_nav_menu_walker', 'wp_edit_nav_menu_walker', 10, 2 );
    $this->addFilter( 'wp_setup_nav_menu_item', 'wp_setup_nav_menu_item', 10, 1 );

    $this->addAction( 'wp_update_nav_menu_item', 'wp_update_nav_menu_item', 10, 3 );
    $this->addAction( 'create_mega_menu_fields', 'create_mega_menu_fields', 10, 2 );
    $this->addAction( 'create_mega_menu_labels', 'create_mega_menu_labels' );

  }

  /**
   *
   * Menu Fields
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  public function create_mega_menu_fields( $item_id, $item ) {
  ?>

  <div class="field-icon description description-wide">
    <?php
      $hidden = ( empty( $item->icon ) ) ? ' hidden' : '';
      $icon   = ( !empty( $item->icon ) ) ? ' class="'. create_icon_class( $item->icon ) . '"' : '';
    ?>
    <div class="create_field create_field_icon">
      <div class="icon-select">
        <span class="button icon-picker <?php echo $item->icon; ?>"><?php _e(" Icon", 'create'); ?></span>
        <input type="hidden" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo $item->icon; ?>" class="widefat code edit-menu-item-icon icon-value"/>
	  </div>
    </div>
  </div>

  <div class="mega-menu">

    <div class="field-mega">
      <label for="edit-menu-item-mega-<?php echo $item_id; ?>">
        <input type="checkbox" class="is-mega" id="edit-menu-item-mega-<?php echo $item_id; ?>" value="mega" name="menu-item-mega[<?php echo $item_id; ?>]"<?php checked( $item->mega, 'mega' ); ?> />
        <?php _e("Mega Menu", 'create'); ?>
      </label>
    </div>

    <div class="field-mega-width">
      <select id="edit-menu-item-mega_width-<?php echo $item_id; ?>" name="menu-item-mega_width[<?php echo $item_id; ?>]" class="is-width">
        <option value="full-width"><?php _e("Full Width", 'create'); ?></option>
        <?php
          $mega_width = array( 'natural' => 'Auto Width', 'custom'  => 'Custom Width' );
          foreach ( $mega_width as $key => $value ) {
            echo '<option value="'. $key .'"'. selected( $key, $item->mega_width) .'>'. $value .'</option>';
          }
        ?>
      </select>
    </div>

    <div class="mega-depend-width hidden">
      <p class="description">
        <label for="edit-menu-item-mega_custom_width<?php echo $item_id; ?>">
          <?php _e("Custom Width (without px)", 'create'); ?><br />
          <input type="text" id="edit-menu-item-mega_custom_width<?php echo $item_id; ?>" class="widefat code edit-menu-item-mega_custom_width" name="menu-item-mega_custom_width[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->mega_custom_width ); ?>" />
        </label>
      </p>
    </div>

    <div class="mega-depend-position hidden">
      <p class="description">
        <label for="edit-menu-item-mega_position<?php echo $item_id; ?>">
          <input type="checkbox" id="edit-menu-item-mega_position<?php echo $item_id; ?>" value="1" name="menu-item-mega_position[<?php echo $item_id; ?>]"<?php checked( $item->mega_position, 1 ); ?> />
          <?php _e("Align Right", 'create'); ?>
        </label>
      </p>
    </div>

    <div class="clear"></div>
  </div>

  <div class="">

    <p class="field-column description description-thin">
      <label for="edit-menu-item-column-title-<?php echo $item_id; ?>">
        <input type="checkbox" id="edit-menu-item-column-title-<?php echo $item_id; ?>" value="1" name="menu-item-column_title[<?php echo $item_id; ?>]"<?php checked( $item->column_title, 1 ); ?> /> <?php _e("Disable Title Text", 'create'); ?>
      </label>
    </p>

    <p class="field-column description description-thin last">
      <label for="edit-menu-item-column-title-link-<?php echo $item_id; ?>">
        <input type="checkbox" id="edit-menu-item-column-title-link-<?php echo $item_id; ?>" value="1" name="menu-item-column_title_link[<?php echo $item_id; ?>]"<?php checked( $item->column_title_link, 1 ); ?> /> <?php _e("Disable Link", 'create'); ?>
      </label>
    </p>

    <div class="clear"></div>
  </div>

  <div class="clear"></div>
  <?php
  }
  public function create_mega_menu_labels() {

    $out   = '<span class="item-mega"><span class="label label-primary">Mega Menu</span></span>';
    $out  .= '<span class="item-mega-column"><span class="label label-success">Column</span></span>';
    echo $out;

  }

  /**
   *
   * Custom Menu Args
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  public function wp_nav_menu_args( $args ) {

    if( $args['theme_location'] == 'primary' && ! isset( $args['mobile'] ) ) {
      $this->walker       = new Walker_Nav_Menu_Custom();
      $args['container']  = false;
      $args['menu_class'] = 'main-menu sf-menu';
      $args['walker']     = $this->walker;
      $args['items_wrap'] = $this->walker->custom_wrap();
    } else if ( isset( $args['mobile'] ) ) {
      $args['after']      = '<div class="dropdown-plus"><i class="fa fa-plus"></i></div>';
    }

    return $args;
  }

  /**
   *
   * Custom Nav Menu Edit
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  public function wp_edit_nav_menu_walker( $walker, $menu_id ) {
    return 'Walker_Nav_Menu_Edit_Custom';
  }

  /**
   *
   * Save Custom Fields
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  public function wp_setup_nav_menu_item( $item ) {

    foreach ( $this->extra_fields as $key ) {
      $item->$key = get_post_meta( $item->ID, '_menu_item_'. $key, true );
    }

    return $item;
  }

  /**
   *
   * Update Custom Fields
   * @since 1.0.0
   * @version 1.0.0
   *
   */
  public function wp_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {

    foreach ( $this->extra_fields as $key ) {
      $value = ( isset( $_REQUEST['menu-item-'.$key][$menu_item_db_id] ) ) ? $_REQUEST['menu-item-'.$key][$menu_item_db_id] : '';
      update_post_meta( $menu_item_db_id, '_menu_item_'. $key, $value );
    }

  }
}
new Create_Megamenu();