<?php
namespace Staff_Area\Members;

/**
 * Class that modifies the default WP toolbar
 *
 * @see http://technerdia.com/1140_wordpress-admin-bar.html
 * @TODO All relevant pages should be defined by user, with appropriate fallbacks.
 *
 */
class Toolbar {

  private $hide_toolbar;

  public function __construct() {

    $this->set_staff_page();
    $this->set_staff_reg_page();
    $this->set_staff_management_page();
    $this->set_display_toolbar();

  }

  public function set_display_toolbar() {

    $hide_toolbar = get_option( 'carawebs_staff_area_data' )['hide_toolbar'];

    $this->hide_toolbar = "true" === $hide_toolbar ? true : false;

  }

  public function set_staff_reg_page() {

    $this->staff_reg_page = esc_url( home_url( '/staff-registration' ) );

  }

  public function set_staff_page() {

    $this->staff_page = esc_url( home_url( '/staff' ) );

  }

  public function set_staff_management_page() {

    $this->staff_management_page = esc_url( home_url( '/staff-management' ) );

  }

  public function  edit_toolbar( $wp_toolbar ) {

    $wp_toolbar->remove_node('wp-logo');
    //$wp_toolbar->remove_node('site-name');
    $wp_toolbar->remove_node('updates');
    $wp_toolbar->remove_node('comments');
    $wp_toolbar->remove_node('customize');
    $wp_toolbar->remove_node('search');

    if( ! current_user_can( 'edit_pages' ) ) {

      $wp_toolbar->remove_node('new-content');

      // Remove the right-hand account node
      //$wp_toolbar->remove_node('top-secondary');

    }

  }

  /**
	 * Control access to the WordPress toolbar
	 *
	 * @return [type] [description]
	 */
	public function control_admin_toolbar() {

	  if ( current_user_can( 'administrator' ) || current_user_can( 'edit_pages' ) ) {

	    show_admin_bar( true );

	  } else {

      if ( true === $this->hide_toolbar ) {

        show_admin_bar( false );

      }

	  }

	}

  public function custom_toolbar() {

  	global $wp_admin_bar;

  	$args = array(
  		'id'     => 'staff-link',
  		'title'  => __( 'Staff Area', 'text_domain' ),
  		'href'   => $this->staff_page,
  	);

  	$wp_admin_bar->add_menu( $args );

    if ( current_user_can( 'edit_pages') ) {

      $args = array(
    		'id'     => 'staff-reg-link',
    		'title'  => __( 'Staff Registration', 'staff-area' ),
        'href'   => $this->staff_reg_page
    	);
    	$wp_admin_bar->add_menu( $args );

      $args = array(
    		'id'     => 'staff-management-link',
    		'title'  => __( 'Staff Management', 'staff-area' ),
        'href'   => $this->staff_management_page
    	);
    	$wp_admin_bar->add_menu( $args );

    }

  }

}
