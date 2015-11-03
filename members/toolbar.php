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

  private $staff_directory_page;

  public function __construct() {

    $this->set_staff_page();
    $this->set_staff_reg_page();
    $this->set_staff_management_page();
    $this->set_staff_directory_page();
    $this->set_display_toolbar();
    $this->set_management_resources_page();

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

  public function set_staff_directory_page() {

    $this->staff_directory_page = esc_url( home_url( '/staff-directory' ) );

  }

  public function set_management_resources_page() {

    $this->management_resources_page = esc_url( home_url( '/management-resources' ) );

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
    $wp_toolbar->remove_node('top-secondary');

    if( ! current_user_can( 'edit_pages' ) ) {

      $wp_toolbar->remove_node('new-content');
      $wp_toolbar->remove_node('site-name');

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

    $current_user     = wp_get_current_user();
    $current_user_ID  = $current_user->ID;
    $access_check     = new Access( $current_user_ID, ['staff_manager', 'staff_supervisor', 'staff_member'] );
    $access           = $access_check->get_access_level();
    $roles = $access_check->get_allowed_roles_string();

    global $wp_admin_bar;

  	$args = array(
  		'id'     => 'staff-link',
  		'title'  => __( 'Staff Resources', 'text_domain' ),
  		'href'   => $this->staff_page,
  	);

  	$wp_admin_bar->add_menu( $args );

    //if ( current_user_can( 'edit_pages') ) {
    if ( 'manager_access' == $access || 'full_access' == $access ) {

      $args = array(
    		'id'     => 'staff-reg-link',
    		'title'  => __( 'Staff Registration', 'staff-area' ),
        'href'   => $this->staff_reg_page
    	);
    	$wp_admin_bar->add_menu( $args );

      $args = array(
    		'id'     => 'staff-directory-link',
    		'title'  => __( 'Staff Directory', 'staff-area' ),
        'href'   => $this->staff_directory_page
    	);
    	$wp_admin_bar->add_menu( $args );

    }

    // Management Resources Link
    // -------------------------------------------------------------------------
    if ( 'manager_access' == $access || 'supervisor_access' == $access || 'full_access' == $access ) {

      $args = array(
    		'id'     => 'management-resources-link',
        //'title' => __('<img src="'.get_bloginfo('wpurl').'/icon.png" style="vertical-align:middle;margin-right:5px" alt="Visit Site" title="Visit Site" />Visit Site' ),
    		'title'  => __( 'Management Resources', 'staff-area' ),
        'href'   => $this->management_resources_page
    	);
    	$wp_admin_bar->add_menu( $args );

    }

    if ( 'staff_access' == $access || 'full_access' == $access || 'manager_access' ) {

      $args = array(
    		'id'     => 'staff-logout',
    		'title'  => __( 'Log Out', 'staff-area' ),
        'href'   => wp_logout_url( get_permalink() )
    	);
    	$wp_admin_bar->add_node( $args );

    }

  }

}
