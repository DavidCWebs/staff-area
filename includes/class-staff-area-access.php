<?php
namespace Carawebs\Staff;
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

  /**
   * Set up the allowed roles.
   *
   * Pass in an array to override or extend the default arguments.
   *
   * @since    1.0.0
   * @param array $override Array of WP_Query arguments
   */
  public function __construct( $user_ID, $override_allowed_roles = [] ) {

    $this->user_ID  = $user_ID;
    $this->allowed_roles    = array_merge(
      array (
        'administrator',
        'editor'
      ),
      $override_allowed_roles
    );
    $this->set_user_roles();
    $this->set_access_level();

  }

  /**
   * Set the current user's roles
   *
   */
  private function set_user_roles() {

    $user_object = get_user_by( 'id', $this->user_ID );
  	$this->current_user_roles = $user_object->roles;

  }

  private function set_access_level() {

    if ( count( array_intersect($this->current_user_roles, $this->allowed_roles) ) === 0 ) {

      $this->access_level = 'no_access';

    }

    if( in_array( 'administrator', $this->current_user_roles ) ) {

      $this->access_level = 'full_access';

    }

    if( in_array( 'editor', $this->current_user_roles ) ) {

      $this->access_level = 'full_access';

    }

    if( in_array( 'staff_member', $this->current_user_roles ) ) {

      $this->access_level = 'staff_access';

    }

    if( in_array( 'staff_management', $this->current_user_roles ) ) {

      $this->access_level = 'staff_access';

    }

  }

  public function get_access_level() {

    return $this->access_level;

  }

}
