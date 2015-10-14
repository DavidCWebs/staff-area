<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/admin
 * @author     David Egan <david@carawebs.com>
 */
 class Staff_Area_Admin {

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
	 * @param      string    $staff_area       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 public function __construct( $staff_area, $version ) {

		 $this->staff_area = $staff_area;
		 $this->version = $version;

	 }

	 /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	 public function enqueue_styles() {

		 /**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Staff_Area_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Staff_Area_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 wp_enqueue_style( $this->staff_area, plugin_dir_url( __FILE__ ) . 'css/staff-area-admin.css', array(), $this->version, 'all' );

	 }

	 /**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	 public function enqueue_scripts() {

		 /**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Staff_Area_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Staff_Area_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 wp_enqueue_script( $this->staff_area, plugin_dir_url( __FILE__ ) . 'js/staff-area-admin.js', array( 'jquery' ), $this->version, false );

	 }

   /**
    * Add page slug to `body_class()` classes if it doesn't exist & add user role to body class
    *
    * Hooked into the `body_class` filter.
    *
    * @since    1.0.0
    *
    */
   /*
   public function add_body_class( $classes ) {

    // Add post/page slug
    // -------------------------------------------------------------------------
    if ( is_page( 'staff', 'staff-registration' ) ) {

      if ( !in_array( 'staff-area', $classes) ) {

        $classes[] = 'staff-area';

      }

      // Check to see if the user is logged in, and if they are, add their role to the body class.
      // -----------------------------------------------------------------------
      if( is_user_logged_in() && !is_front_page() ){

        $current_user       = wp_get_current_user();  // Current user object
        $current_user_id    = $current_user->ID;      // Current user ID
        $user_role          = carawebs_get_user_role( $current_user_id );
        $user_role          .= '-role'; // append modifier to avoid confusion with pages having same name
        $classes[]          = $user_role;

      }

    }

    return $classes;

  }*/

	 /**
	 * Set custom templates for this plugin's content
	 *
	 * @param  [type] $page_template [description]
	 * @return [type]                [description]
	 */
	public function staff_resource_page_template( $page_template ) {

		if ( is_singular( [ 'staff_resource', 'management_resource' ] ) ) {

      $page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/staff-resource-template.php';


    }

		if ( is_page( 'staff' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/staff-template.php';

		}

		return $page_template;

	}

  /**
	 * Set custom templates for the staff registration page(s)
	 *
	 * @param  [type] $page_template [description]
	 * @return [type]                [description]
	 */
	public function staff_registration_page_template( $page_template ) {

		if ( is_page( 'staff-registration' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/staff-registration.php';

		}

		return $page_template;

	}

	public function dashboard_block() {

		if ( is_admin() &&                              // Check if the Dashboard or the administration panel is attempting to be displayed.
		! current_user_can( 'administrator' ) &&        // Administrator
		! current_user_can( 'edit_pages') &&            // Editor
		! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )   // For when admin_ajax.php is used to process ajax requests
		{

			wp_redirect( esc_url( home_url() ) );

			exit;

		}

	}

}
