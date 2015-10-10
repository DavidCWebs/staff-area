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
    $this->option_name = 'carawebs_' . str_replace( '-', '_', strtolower( $plugin_name ) ) . '_data';
    $this->options = get_option( $this->option_name );

  }

  /**
  * Add top-level menu page
  *
  * The slug returned by `add_menu_page()` is the name of an action.
  *
  */
  public function add_menu_page(){

    $this->menu_page = add_menu_page(
    __( 'Staff Area', $this->plugin_name ), // Page Title
    __( 'Staff Area', $this->plugin_name ), // Menu Title
    'manage_options',                       // Capability (admin)
    $this->plugin_name,                     // Menu slug
    array( $this, 'display_page_content' ), // Callback function
    'dashicons-groups'                      // Icon
  );

}

/**
* Menu page callback
*
* Content of the settings page.
*
* @since 1.0.0
*
**/
public function display_page_content() {

  if ( ! current_user_can( 'manage_options' ) ) {

    wp_die( __( 'You do not have sufficient permissions to access this page.', 'staff-area' ) );

  }

  $this->options = get_option( $this->option_name );

  ?>
  <div class="wrap">
      <h2>My Settings</h2>
      <form method="post" action="options.php">
      <?php
          // This prints out all hidden setting fields
          settings_fields( $this->plugin_name . '_main' );  // Match Setting group name in register_setting()
          do_settings_sections( $this->plugin_name );       // Page/Menu Slug
          submit_button();
      ?>
      </form>
  </div>
  <?php

  //$form = new Admin_Form();
  //$form->render_form();

}


/**
* [register_settings description]
* @return [type] [description]
*/
public function page_init(){

  register_setting(
    $this->plugin_name . '_main', // Option group name. This must match the group name in `settings_fields()`
    $this->option_name,//'staff_area', // Option name
    array( $this, 'sanitize_data' ) // Sanitize
  );

  add_settings_section(
    $this->plugin_name . '_main',               // Saved as the key
    'Staff Area Settings',                // Title
    array( $this, 'print_section_info' ), // Callback
    $this->plugin_name                    // Page
  );

  add_settings_field(
    'id_number', // ID
    'ID Number', // Title
    array( $this, 'id_number_callback' ), // Callback
    $this->plugin_name, // Page
    $this->plugin_name . '_main' // Section
  );

  add_settings_field(
    'title',
    'Title',
    array( $this, 'title_callback' ),
    $this->plugin_name,
    $this->plugin_name . '_main'
  );

  add_settings_field(
    'allowed_roles',
    'Allowed Roles',
    array( $this, 'allowed_roles_callback' ),
    $this->plugin_name,
    $this->plugin_name . '_main'
  );

}

/**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function allowed_roles_callback() {

      global $wp_roles;

      if ( ! isset( $wp_roles ) )
      	$wp_roles = new \WP_Roles();
      	$roles = $wp_roles->get_names();

      	foreach ($roles as $role_value => $role_name) {

          $checked = in_array( $role_value, $this->options['allowed_roles'] ) ? true : false;

          ?>
          <p><input type="checkbox" name="<?php echo $this->option_name ; ?>[allowed_roles][]"<?php echo true === $checked ? 'checked="checked"': '';?>><?php echo $role_name; ?></p>
          <?php
        	}

    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="%s" value="%s" />',
            $this->option_name . "[id_number]",
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="%s" value="%s" />',
            $this->option_name . "[title]",
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
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

  foreach( $data as $field ) {

    sanitize_text_field( $field );

  }

  return $data;

}

}
