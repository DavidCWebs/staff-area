<?php
namespace Staff_Area\Members;
/**
 * The file that defines access
 *
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The Access class - used to control access.
 *
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Access {

  /**
   * Roles that are allowed access
   * @since    1.0.0
   * @var array
   */
  private $allowed_roles;

  /**
   * ID of the currently accessing user
   * @var [type]
   */
  private $user_ID;

  /**
   * The roles of the current user
   * @var array
   */
  private $current_user_roles;

  /**
   * Access level
   * @var string
   */
  private $access_level;

  public $first_name;

  /**
   * Set up the allowed roles.
   *
   * Pass in an array to override or extend the default arguments.
   *
   * @since    1.0.0
   * @param array $override Array of WP_Query arguments
   */
  public function __construct( $user_ID, $override_allowed_roles = [] ) {

    $this->user_ID        = $user_ID;
    $this->allowed_roles  = array_merge(
      array (
        'administrator',
        'editor'
      ),
      $override_allowed_roles
    );
    $this->set_user_data();
    $this->set_access_level();

  }

  /**
   * Set the current user's roles
   *
   */
  private function set_user_data() {

    $user_object              = get_user_by( 'id', $this->user_ID );
  	$this->current_user_roles = $user_object->roles;
    $this->first_name         = $user_object->user_firstname;

  }

  /**
   * [set_access_level description]
   */
  private function set_access_level() {

    // If one of the current user's roles is not in the $this->allowed_roles array, no access
    if ( count( array_intersect( $this->current_user_roles, $this->allowed_roles ) ) === 0 ) {

      $this->access_level = 'no_access';
      return;

    }

    if ( in_array( 'administrator', $this->current_user_roles ) ) {

      $this->access_level = 'full_access';

    }

    if ( in_array( 'editor', $this->current_user_roles ) ) {

      $this->access_level = 'full_access';

    }

    if ( in_array( 'staff_member', $this->current_user_roles ) ) {

      $this->access_level = 'staff_access';

    }

    if ( in_array( 'staff_supervisor', $this->current_user_roles ) ) {

      $this->access_level = 'supervisor_access';

    }

    if ( in_array( 'staff_manager', $this->current_user_roles ) ) {

      $this->access_level = 'manager_access';

    }

  }

  public function get_access_level() {

    return $this->access_level;

  }

  public function get_user_roles_string() {

    return implode( ', ', $this->current_user_roles );

  }

  public function get_allowed_roles_string() {

    return implode( ', ', $this->allowed_roles );

  }

  public function get_readable_user_role() {

    $level = "No role";

    if ( in_array ( 'staff_manager', $this->current_user_roles ) ) { $level = "Manager"; }
    if ( in_array ( 'staff_supervisor', $this->current_user_roles ) ) { $level = "Supervisor"; }
    if ( in_array ( 'staff_member', $this->current_user_roles ) ) { $level = "Staff Member"; }
    if ( in_array ( 'administrator', $this->current_user_roles ) ) { $level = "Administrator"; }
    if ( in_array ( 'editor', $this->current_user_roles ) ) { $level = "Editor"; }

    return $level;

  }

  /**
   * Return a string of allowed user roles
   *
   * User roles are made human readable. Admin and editor roles are removed from
   * the array - these users have default access and it is assumed that they
   * know this.
   *
   * @return string A string of allowed user roles
   */
  public function get_access_string() {

    // Remove 'administrator' and 'editor' from the allowed roles array
    $allowed = array_diff( $this->allowed_roles, ['administrator', 'editor'] );

    // Output readable values
    $allowed = array_map(

      function($a) {

        if ('staff_manager' === $a) { $a = "Manager"; }
        if ('staff_supervisor' === $a) { $a = "Supervisor"; }
        return $a;

      }, $allowed

    );

    return implode( " or ", $allowed );

  }

}
