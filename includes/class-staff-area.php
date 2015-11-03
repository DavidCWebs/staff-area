<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Staff_Area {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Staff_Area_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $staff_area    The string used to uniquely identify this plugin.
	 */
	protected $staff_area;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->staff_area = 'staff-area';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Staff_Area_Loader. Orchestrates the hooks of the plugin.
	 * - Staff_Area_i18n. Defines internationalization functionality.
	 * - Staff_Area_Admin. Defines all hooks for the admin area.
	 * - Staff_Area_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-staff-area-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-staff-area-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-staff-area-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-staff-area-public.php';

		$this->loader = new Staff_Area_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Staff_Area_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Staff_Area_i18n();
		$plugin_i18n->set_domain( $this->get_staff_area() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @TODO set up a new class to register custom templates?
	 */
	private function define_admin_hooks() {

		$plugin_admin				= new Staff_Area_Admin( $this->get_staff_area(), $this->get_version() );
		$form_processor			= new Staff_Area\Members\Register();
		$plugin_options			= new Staff_Area\Admin\Options( $this->get_staff_area(), $this->get_version() );
		$custom_post_types	= new Staff_Area\Admin\CPT();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Menu page
		// -------------------------------------------------------------------------
		$this->loader->add_action( 'admin_menu', $plugin_options, 'add_menu_page' );
		//$this->loader->add_action( 'admin_menu', $plugin_options, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_options, 'page_init' );

		// Email "from" field
		//$this->loader->add_filter( 'wp_mail_from', $plugin_admin, 'email_from' );

		// Ajax processor callback for user registration
		$this->loader->add_action( 'wp_ajax_register_new_user', $form_processor, 'userform_process_facade');

		// Filter template loader for custom templates, staff registration page
		$this->loader->add_filter( 'template_include', $plugin_admin, 'staff_registration_page_template' );

		// Register 'staff_resource' Custom Post Type
		$this->loader->add_action( 'init', $custom_post_types, 'staff_resource_init' );

		// Register a custom taxonomy for staff resource & management resource CPTs
		$this->loader->add_action( 'init', $custom_post_types, 'staff_resource_taxonomy' );

		// Register 'management_resource' Custom Post Type
		$this->loader->add_action( 'init', $custom_post_types, 'management_resource_init' );

		// Register a custom taxonomy for management resource CPTs
		$this->loader->add_action( 'init', $custom_post_types, 'management_resource_taxonomy' );

		// Register 'business-unit' Custom Post Type
		$this->loader->add_action( 'init', $custom_post_types, 'business_unit_init' );

		// Messages for Staff Resource CPT
		$this->loader->add_action( 'post_updated_messages', $custom_post_types, 'staff_resource_updated_messages' );

		// Messages for 'management_resource' Custom Post Type
		$this->loader->add_action( 'post_updated_messages', $custom_post_types, 'management_resource_updated_messages' );

		// Messages for 'business-unit' CPT
		$this->loader->add_action( 'post_updated_messages', $custom_post_types, 'business_unit_updated_messages' );

		// Block dashboard for all users except admin & editor
		$this->loader->add_action( 'init', $plugin_admin, 'dashboard_block' );

		// Filter template loader for custom templates
		$this->loader->add_filter( 'template_include', $plugin_admin, 'staff_area_page_template_routes' );

		// Staff Resources archive admin
		//$this->loader->add_filter( 'manage_edit-staff-resource_columns', $plugin_admin, 'edit_staff_resource_columns' ) ;

		// Usermeta in admin
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'business_unit_form' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'business_unit_form' );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'phone_number_form' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'phone_number_form' );
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_custom_user_meta' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_custom_user_meta' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public	= new Staff_Area_Public( $this->get_staff_area(), $this->get_version() );
		$toolbar				= new Staff_Area\Members\Toolbar();
		//$menus					= new Staff_Area\Helpers\Menu();

		// Enqueue styles
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		// Enqueue scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Redirects
		$this->loader->add_filter( 'login_redirect', $plugin_public, 'login_redirect', 10, 3 );

		// Block dashboard
		$this->loader->add_action( 'init', $plugin_public, 'block_dashboard' );

		// Amend Toolbar
		$this->loader->add_action( 'admin_bar_menu', $toolbar, 'edit_toolbar', 999 );
		$this->loader->add_action( 'wp_before_admin_bar_render', $toolbar, 'custom_toolbar', 999 );

		// Block toolbar for staff
		$this->loader->add_action('wp', $toolbar, 'control_admin_toolbar');

		// Confirmation form processor
		$this->loader->add_action( 'wp', $plugin_public, 'process_read_status' );

		// Confirmation form AJAX processor
		$this->loader->add_action( 'wp_ajax_mark_as_read', $plugin_public, 'ajax_process_read_status' );

		// Add a custom body class to plugin pages
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_class' );

		// Add menu
		//$this->loader->add_action( 'init', $menus, 'register_navigation' );

		// Create menu
		//$this->loader->add_action( 'after_setup_theme', $menus, 'create_menus' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_staff_area() {
		return $this->staff_area;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Staff_Area_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
