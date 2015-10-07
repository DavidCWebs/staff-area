<?php

/**
 * Fired during plugin activation
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Staff_Area_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		include_once( plugin_dir_path( __FILE__ ) . 'class-staff-area-roles.php' );

		// Staff member role
		Carawebs\Staff\Roles::staff_member_roles_and_caps();

		// Unit manager role
		Carawebs\Staff\Roles::staff_manager_roles_and_caps();

	}

}
