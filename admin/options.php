<?php
namespace Staff_Area\Admin;

class Options {

  /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name;

	private $options;

  public function __construct ( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->option_name = 'carawebs_' . str_replace( '-', '_', strtolower( $plugin_name ) );
		$this->options = get_option( $this->option_name . '_data' );

  }

  public function add_options_page() {

		$this->plugin_page = add_users_page(
			__( 'Export to CSV', $this->plugin_name ),
			__( 'Export to CSV', $this->plugin_name ),
			'list_users',
			$this->plugin_name,
			array( $this, 'display_page_content' )
		);

	}

	/**
	 * Add menu page under "Users"
	 *
	 * The slug returned by `add_menu_page()` is the name of an action.
	 *
	 */
	public function add_menu_page(){

		$this->menu_page = add_menu_page(
			__( 'Staff Area', $this->plugin_name ),
			__( 'Staff Area', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_page_content' ),
			'dashicons-users'
		);

	}

  /**
	* Content of the settings page
	*
	* @since 1.0.0
	*
	**/
	public function display_page_content() {

		if ( ! current_user_can( 'manage_options' ) ) {

			wp_die( __( 'You do not have sufficient permissions to access this page.', 'carawebs-csv' ) );

		}

		$form = new Admin_Form();
		$form->render_form();

	}

  /**
	 * [register_settings description]
	 * @return [type] [description]
	 */
	public function register_settings(){

		register_setting(
			$this->plugin_name,											// Setting Group Name
			$this->option_name . '_data',						// Option name (DB field - this will be an array)
			array( $this, 'sanitize_data' )			// Sanitization callback
			);

		// Add a General section
		add_settings_section(
	    $this->option_name . '_data',												// HTML ID tag for section
	    __( 'Address', 'address' ),													// Section title, shown in a <h3> tag
	    array( $this, $this->option_name . '_general' ),		// Callback function to echo content/section explanation
	    $this->plugin_name																	// Settings page to show this on
		);

		add_settings_field(
	    $this->option_name . '_business_name',
	    __( 'Business Name', 'address' ),
	    array( $this, $this->option_name . '_business_name' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_business_name' )
		);

	}

  /**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
		public function sanitize_data( $data ) {

			foreach( $data as $field ){

				sanitize_text_field( $field );

			}

			return $data;

		}

}
