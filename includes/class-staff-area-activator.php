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

		// Staff member role
		Staff_Area\Admin\Roles::staff_member_roles_and_caps();

		// Unit manager role
		Staff_Area\Admin\Roles::staff_manager_roles_and_caps();

		$custom_post_types = new Staff_Area\Admin\CPT();

		// Register 'staff_resource' Custom Post Type
		//$this->loader->add_action( 'init', $custom_post_types, 'staff_resource_init' );
		$custom_post_types->staff_resource_init();

		// Register a custom taxonomy for staff resource & management resource CPTs
		//$this->loader->add_action( 'init', $custom_post_types, 'staff_resource_taxonomy' );
		$custom_post_types->staff_resource_taxonomy();

		// Register 'management_resource' Custom Post Type
		//$this->loader->add_action( 'init', $custom_post_types, 'management_resource_init' );
		$custom_post_types->management_resource_init();

		// Register a custom taxonomy for management resource CPTs
		//$this->loader->add_action( 'init', $custom_post_types, 'management_resource_taxonomy' );
		$custom_post_types->management_resource_taxonomy();


		flush_rewrite_rules();

	}

}
