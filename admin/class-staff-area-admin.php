<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
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
 * @author     Your Name <email@example.com>
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

	public function staff_resource_init() {

		register_post_type( 'staff-resource', array(
			'labels'            => array(
				'name'                => __( 'Staff resources', 'staff-area' ),
				'singular_name'       => __( 'Staff resource', 'staff-area' ),
				'all_items'           => __( 'Staff resources', 'staff-area' ),
				'new_item'            => __( 'New staff resource', 'staff-area' ),
				'add_new'             => __( 'Add New', 'staff-area' ),
				'add_new_item'        => __( 'Add New staff resource', 'staff-area' ),
				'edit_item'           => __( 'Edit staff resource', 'staff-area' ),
				'view_item'           => __( 'View staff resource', 'staff-area' ),
				'search_items'        => __( 'Search staff resources', 'staff-area' ),
				'not_found'           => __( 'No staff resources found', 'staff-area' ),
				'not_found_in_trash'  => __( 'No staff resources found in trash', 'staff-area' ),
				'parent_item_colon'   => __( 'Parent staff resource', 'staff-area' ),
				'menu_name'           => __( 'Staff resources', 'staff-area' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'supports'          => array( 'title', 'editor' ),
			'has_archive'       => true,
			'rewrite'           => true,
			'query_var'         => true,
			'menu_icon'         => 'dashicons-admin-post',
		) );

	}

	public function staff_resource_updated_messages( $messages ) {

		global $post;

		$permalink = get_permalink( $post );

		$messages['staff-resource'] = array(
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

	public function staff_resource_page_template( $page_template ) {
		error_log( $page_template );

		if ( is_singular( 'staff-resource' ) ) {

        $page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/staff-resource-template.php';

				error_log( $page_template );

    }
    return $page_template;

	}

}
