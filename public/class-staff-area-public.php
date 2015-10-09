<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/public
 * @author     David Egan <david@carawebs.com>
 */
class Staff_Area_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $staff_area    The ID of this plugin.
	 */
	private $staff_area;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $staff_area       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $staff_area, $version ) {

		$this->staff_area = $staff_area;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->staff_area, plugin_dir_url( __FILE__ ) . 'css/staff-area-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Enqueue the jQuery filter script on the specified pages.
		 * @TODO: Pass in an array of pages for the filter - set in an options page with sensible defaults.
		 * @var array of pages for the filter js function
		 */
		$filter_pages = ['staff'];

		if (is_page ( $filter_pages ) ) {

				wp_enqueue_script( $this->staff_area, plugin_dir_url( __FILE__ ) . 'js/resource-filter.js', array( 'jquery' ), $this->version, false );

		}

		/**
		 * Staff Registration Sctipts
		 */
		if (is_page('staff-registration') ) {

      wp_register_script('carawebs_user_reg_script', plugin_dir_url( __FILE__ ) . 'js/registration.js', array('jquery'), null, false);

      wp_enqueue_script('carawebs_user_reg_script');

      $current_user = wp_get_current_user();            // gets the current user object
      $user_id = $current_user->ID;

      wp_localize_script( 'carawebs_user_reg_script', 'carawebsRegVars', array(
        'carawebsAjaxURL' => admin_url( 'admin-ajax.php' ),
        'carawebsCoordinatorID' => $user_id, // This passes the user ID of the originating user (with the custom role 'coordinator')
        )
      );

    }

	}

}
