<?php
namespace Staff_Area\Admin;
/**
 * The file that defines the Roles class
 *
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The class that registers custom site roles
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
   * Register a role for an ordinary staff member
   *
   * @return void
   */
  public static function staff_member_roles_and_caps() {

     // start with subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    $member_caps = $subscriber->capabilities;

    // Remove the role in case of changes
    remove_role( 'staff_member' );

    // Add the Staff Member role
    add_role( 'staff_member', 'Staff Member', $member_caps );

  }

  /**
   * Register a role for a supervisor staff member
   *
   * @return void
   */
  public static function staff_supervisor_roles_and_caps() {

     // start with subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    $supervisor_caps = $subscriber->capabilities;

    // Remove the role in case of changes
    remove_role( 'staff_supervisor' );

    // Add the Staff Unit Manager role
    add_role( 'staff_supervisor', 'Supervisor', $supervisor_caps );

  }

  /**
   * Register a role for a manager staff member
   *
   * @TODO Should this role have the ability to edit staff resources?
   *
   * @return void
   */
  public static function staff_manager_roles_and_caps() {

     // start with subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    $manager_caps = $subscriber->capabilities;

    // Remove the role in case of changes
    remove_role( 'staff_manager' );

    // Add the Staff Unit Manager role
    add_role( 'staff_manager', 'Staff Manager', $manager_caps );

  }

}
