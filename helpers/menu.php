<?php
namespace Staff_Area\Helpers;

class Menu {

  /**
   * Register a menu area
   *
   * @return void
   */
  function register_navigation() {

    register_nav_menus( array( 'staff_menu' => __( 'Staff Area Menu' ) ) );

  }

  public function create_menus() {

    $staff_menu = array(
      'menu-name'   => 'Staff Area Menu',
      'description' => 'The staff-only navigation menu for this website'
    );

    $custom_menu = wp_update_nav_menu_object( 0, $staff_menu );

    // Set the menus to appear in the proper theme locations
    $locations = get_theme_mod('nav_menu_locations');
    $locations['staff-menu'] = $custom_menu;
    set_theme_mod('nav_menu_locations', $locations);

    $menu_item = array(
      'menu-item-object-id'   => 711,
      'menu-item-parent-id'   => 0,
      'menu-item-position'    => 0,
      'menu-item-object'      => 'page',
      'menu-item-type'        => 'post_type',
      'menu-item-status'      => 'publish',
      'menu-item-title'       => 'LABEL'
    );

  // Add to nav menu
  wp_update_nav_menu_item( $custom_menu, 0, $menu_item );

  }

}
