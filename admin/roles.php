<?php
namespace Staff_Area\Admin;
/**
 * The file that defines the Loops class
 *
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The loops class - used to define custom loops.
 *
 * Passing an array of arguments when instantiating the class object allows the
 * default $args to be overridden.
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Roles {

  /**
   * Arguments to be passed to WP_Query()
   * @since    1.0.0
   * @var array
   */
  private $args;

  public static function staff_member_roles_and_caps() {

     // start with subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    $member_caps = $subscriber->capabilities;

    // Remove the role in case of changes
    remove_role( 'staff_member' );

    // Add the Staff Member role
    add_role( 'staff_member', 'Staff Member', $member_caps );

  }

  public static function staff_manager_roles_and_caps() {

     // start with subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    $supervisor_caps = $subscriber->capabilities;

    // Remove the role in case of changes
    remove_role( 'supervisor' );

    // Add the Staff Unit Manager role
    add_role( 'staff_supervisor', 'Supervisor', $supervisor_caps );

  }

}
