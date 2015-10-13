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
	 * Register 'staff_resource' custom post type
	 *
	 *
	 * @return [type] [description]
	 */
	 public function staff_resource_init() {

		 $labels = [
			 'name'                => _x( 'Staff Resources', 'Post Type General Name', 'staff-area' ),
			 'singular_name'       => _x( 'Staff Resource', 'Post Type Singular Name', 'staff-area' ),
			 'menu_name'           => __( 'Staff Resources', 'staff-area' ),
			 'name_admin_bar'      => __( 'Staff Resource', 'staff-area' ),
			 'parent_item_colon'   => __( 'Parent Staff Resource:', 'staff-area' ),
			 'all_items'           => __( 'All Staff Resources', 'staff-area' ),
			 'add_new_item'        => __( 'Add New Staff Resource', 'staff-area' ),
			 'add_new'             => __( 'Add New', 'staff-area' ),
			 'new_item'            => __( 'New Staff Resource', 'staff-area' ),
			 'edit_item'           => __( 'Edit Staff Resource', 'staff-area' ),
			 'update_item'         => __( 'Update Staff Resource', 'staff-area' ),
			 'view_item'           => __( 'View Staff Resource', 'staff-area' ),
			 'search_items'        => __( 'Search Staff Resources', 'staff-area' ),
			 'not_found'           => __( 'Not found', 'staff-area' ),
			 'not_found_in_trash'  => __( 'Not found in Trash', 'staff-area' ),

		 ];

		 register_post_type( 'staff_resource', array(
			 'label'               => __( 'Staff Resource', 'staff-area' ),
			 'description'         => __( 'Staff resource posts', 'staff-area' ),
			 'labels'              => $labels,
			 'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ),
			 'hierarchical'        => false,
			 'public'              => true,
			 'show_ui'             => true,
			 'show_in_menu'        => true,
			 'menu_icon'					 => 'dashicons-media-document',
			 'menu_position'       => 5,
			 'show_in_admin_bar'   => true,
			 'show_in_nav_menus'   => true,
			 'can_export'          => true,
			 'has_archive'         => true,
			 'exclude_from_search' => false,
			 'publicly_queryable'  => true,
			 'capability_type'     => 'page',
			 )
		 );

	 }

	 /**
	 * Custom messages for the Staff Resource Custom Post Type
	 *
	 * @param  array $messages [description]
	 * @return array $messages [description]
	 */
	 public function staff_resource_updated_messages( $messages ) {

		 global $post;

		 $permalink = get_permalink( $post );

		 $messages['staff_resource'] = array(
			 0 => '', // Unused. Messages start at index 1.
			 1 => sprintf( __('Staff resource updated. <a target="_blank" href="%s">View staff resource</a>', 'staff-area'), esc_url( $permalink ) ),
			 2 => __('Custom field updated.', 'staff-area'),
			 3 => __('Custom field deleted.', 'staff-area'),
			 4 => __('Staff resource updated.', 'staff-area'),
			 /* translators: %s: date and time of the revision */
			 5 => isset($_GET['revision']) ? sprintf( __('Staff resource restored to revision from %s', 'staff-area'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			 6 => sprintf( __('Staff resource published. <a href="%s">View staff resource</a>', 'staff-area'), esc_url( $permalink ) ),
			 7 => __('Staff resource saved.', 'staff-area'),
			 8 => sprintf( __('Staff resource submitted. <a target="_blank" href="%s">Preview staff resource</a>', 'staff-area'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			 9 => sprintf( __('Staff resource scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview staff resource</a>', 'staff-area'),
			 // translators: Publish box date format, see http://php.net/date
			 date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			 10 => sprintf( __('Staff resource draft updated. <a target="_blank" href="%s">Preview staff resource</a>', 'staff-area'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		 );

		 return $messages;

	 }

	 public function staff_resource_taxonomy() {

		 $labels = array(
			 'name'                       => _x( 'Resource Categories', 'Taxonomy General Name', 'staff-area' ),
			 'singular_name'              => _x( 'Resource Category', 'Taxonomy Singular Name', 'staff-area' ),
			 'menu_name'                  => __( 'Resource Category', 'staff-area' ),
			 'all_items'                  => __( 'All Resource Categories', 'staff-area' ),
			 'parent_item'                => __( 'Parent Resource Category', 'staff-area' ),
			 'parent_item_colon'          => __( 'Parent Resource Category:', 'staff-area' ),
			 'new_item_name'              => __( 'New Resource Category', 'staff-area' ),
			 'add_new_item'               => __( 'Add New Resource Category', 'staff-area' ),
			 'edit_item'                  => __( 'Edit Resource Category', 'staff-area' ),
			 'update_item'                => __( 'Update Resource Category', 'staff-area' ),
			 'view_item'                  => __( 'View Resource Category', 'staff-area' ),
			 'separate_items_with_commas' => __( 'Separate items with commas', 'staff-area' ),
			 'add_or_remove_items'        => __( 'Add or remove items', 'staff-area' ),
			 'choose_from_most_used'      => __( 'Choose from the most used', 'staff-area' ),
			 'popular_items'              => __( 'Popular Resource Categories', 'staff-area' ),
			 'search_items'               => __( 'Search Resource Categories', 'staff-area' ),
			 'not_found'                  => __( 'Not Found', 'staff-area' ),
		 );
		 $args = array(
			 'labels'                     => $labels,
			 'hierarchical'               => true,
			 'public'                     => true,
			 'show_ui'                    => true,
			 'show_admin_column'          => true,
			 'show_in_nav_menus'          => true,
			 'show_tagcloud'              => true,
		 );
		 register_taxonomy( 'resource_category', array( 'staff_resource', 'management_resource' ), $args );

	 }

	 /**
	  * Register Management Resource custom post type
	  *
	  * @return void
	  */
	 function management_resource_init() {

		 $labels = [
			 'name'                => _x( 'Management Resources', 'Post Type General Name', 'staff-area' ),
			 'singular_name'       => _x( 'Management Resource', 'Post Type Singular Name', 'staff-area' ),
			 'menu_name'           => __( 'Management Resources', 'staff-area' ),
			 'name_admin_bar'      => __( 'Management Resource', 'staff-area' ),
			 'parent_item_colon'   => __( 'Parent Management Resource:', 'staff-area' ),
			 'all_items'           => __( 'All Management Resources', 'staff-area' ),
			 'add_new_item'        => __( 'Add New Management Resource', 'staff-area' ),
			 'add_new'             => __( 'Add New', 'staff-area' ),
			 'new_item'            => __( 'New Management Resource', 'staff-area' ),
			 'edit_item'           => __( 'Edit Management Resource', 'staff-area' ),
			 'update_item'         => __( 'Update Management Resource', 'staff-area' ),
			 'view_item'           => __( 'View Management Resource', 'staff-area' ),
			 'search_items'        => __( 'Search Management Resources', 'staff-area' ),
			 'not_found'           => __( 'Not found', 'staff-area' ),
			 'not_found_in_trash'  => __( 'Not found in Trash', 'staff-area' ),

		 ];

		 register_post_type( 'management_resource', array(
			 'label'               => __( 'Management Resource', 'staff-area' ),
			 'description'         => __( 'Management resource posts', 'staff-area' ),
			 'labels'              => $labels,
			 'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ),
			 'hierarchical'        => false,
			 'public'              => true,
			 'show_ui'             => true,
			 'show_in_menu'        => true,
			 'menu_position'       => 5,
			 'menu_icon'					 => 'dashicons-media-interactive',
			 'show_in_admin_bar'   => true,
			 'show_in_nav_menus'   => true,
			 'can_export'          => true,
			 'has_archive'         => true,
			 'exclude_from_search' => false,
			 'publicly_queryable'  => true,
			 'capability_type'     => 'page',
			 )
		 );

	 }

	 /**
	  * Callback function to filter messages on 'management_resource' Custom Post Type
	  *
	  *
	  * @param  [type] $messages [description]
	  * @return [type]           [description]
	  */
	 function management_resource_updated_messages( $messages ) {

	 	global $post;

	 	$permalink = get_permalink( $post );

	 	$messages['management_resource'] = array(
	 		0 => '', // Unused. Messages start at index 1.
	 		1 => sprintf( __('Management resource updated. <a target="_blank" href="%s">View management resource</a>', 'staff-area'), esc_url( $permalink ) ),
	 		2 => __('Custom field updated.', 'staff-area'),
	 		3 => __('Custom field deleted.', 'staff-area'),
	 		4 => __('Management resource updated.', 'staff-area'),
	 		/* translators: %s: date and time of the revision */
	 		5 => isset($_GET['revision']) ? sprintf( __('Management resource restored to revision from %s', 'staff-area'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	 		6 => sprintf( __('Management resource published. <a href="%s">View management resource</a>', 'staff-area'), esc_url( $permalink ) ),
	 		7 => __('Management resource saved.', 'staff-area'),
	 		8 => sprintf( __('Management resource submitted. <a target="_blank" href="%s">Preview management resource</a>', 'staff-area'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	 		9 => sprintf( __('Management resource scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview management resource</a>', 'staff-area'),
	 		// translators: Publish box date format, see http://php.net/date
	 		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
	 		10 => sprintf( __('Management resource draft updated. <a target="_blank" href="%s">Preview management resource</a>', 'staff-area'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	 	);

	 	return $messages;

	 }

   public function management_resource_taxonomy() {

		 $labels = array(
			 'name'                       => _x( 'Management Categories', 'Taxonomy General Name', 'staff-area' ),
			 'singular_name'              => _x( 'Management Category', 'Taxonomy Singular Name', 'staff-area' ),
			 'menu_name'                  => __( 'Management Category', 'staff-area' ),
			 'all_items'                  => __( 'All Categories', 'staff-area' ),
			 'parent_item'                => __( 'Parent Management Category', 'staff-area' ),
			 'parent_item_colon'          => __( 'Parent Management Category:', 'staff-area' ),
			 'new_item_name'              => __( 'New Management Category', 'staff-area' ),
			 'add_new_item'               => __( 'Add New Management Category', 'staff-area' ),
			 'edit_item'                  => __( 'Edit Management Category', 'staff-area' ),
			 'update_item'                => __( 'Update Management Category', 'staff-area' ),
			 'view_item'                  => __( 'View Management Category', 'staff-area' ),
			 'separate_items_with_commas' => __( 'Separate items with commas', 'staff-area' ),
			 'add_or_remove_items'        => __( 'Add or remove items', 'staff-area' ),
			 'choose_from_most_used'      => __( 'Choose from the most used', 'staff-area' ),
			 'popular_items'              => __( 'Popular Management Categories', 'staff-area' ),
			 'search_items'               => __( 'Search Management Categories', 'staff-area' ),
			 'not_found'                  => __( 'Not Found', 'staff-area' ),
		 );
		 $args = array(
			 'labels'                     => $labels,
			 'hierarchical'               => true,
			 'public'                     => true,
			 'show_ui'                    => true,
			 'show_admin_column'          => true,
			 'show_in_nav_menus'          => true,
			 'show_tagcloud'              => true,
		 );
		 register_taxonomy( 'management_resource_category', 'management_resource', $args );

	 }

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
