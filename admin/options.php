<?php
namespace Staff_Area\Admin;

class Options {

  /**
  * The plugin name.
  *
  * @since  1.0.0
  * @access private
  * @var    string  $plugin_name  The plugin name.
  */
  private $plugin_name;

  /**
  * The version of this plugin.
  *
  * @since  1.0.0
  * @access private
  * @var    string  $version  The current version of this plugin.
  */
  private $version;

  /**
  * The unique options name used in this plugin
  *
  * @since  1.0.0
  * @access private
  * @var    string  $option_name  Option name of this plugin
  */
  private $option_name;

  /**
  * Plugin settings from the options table
  *
  * @since  1.0.0
  * @var    array   $options  Settings values from the options DB table
  */
  private $options;

  /**
  * Set up properties
  *
  * @since 1.0.0
  * @param string $plugin_name The plugin name
  * @param string $version     The plugin version number
  */
  public function __construct ( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->option_name = 'carawebs_' . str_replace( '-', '_', strtolower( $plugin_name ) ) . '_data';
    $this->options = get_option( $this->option_name );
    $this->email_option_name = 'carawebs_' . str_replace( '-', '_', strtolower( $plugin_name ) ) . '_email';
    $this->email_options = get_option( $this->email_option_name );

  }

  /**
  * Add top-level menu page to the WP admin area
  *
  * The slug returned by `add_menu_page()` is the name of an action. This may be
  * needed if targeting the menu page.
  *
  *  @since 1.0.0
  *
  */
  public function add_menu_page(){

    $this->menu_page = add_menu_page(
    __( 'Staff Area', $this->plugin_name ), // Page Title
    __( 'Staff Area', $this->plugin_name ), // Menu Title
    'manage_options',                       // Capability (admin)
    "carawebs-".$this->plugin_name,         // Menu slug
    array( $this, 'display_page_content' ), // Callback function to render the page
    'dashicons-groups'                      // Icon
    // Menu Position
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

  ?>
  <div class="wrap">
    <h2>My Settings</h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
      <?php

      // This prints out all hidden setting fields
      settings_fields( $this->plugin_name . "_main" );  // Match Setting group name in register_setting()

      // This function replaces the form-field markup in the form with the fields defined
      // Slug name of the page on which to output settings - must match the page name used in add_settings_section().
      do_settings_sections( $this->plugin_name );       // Page/Menu Slug

      submit_button();
      ?>
    </form>
  </div>
  <?php

}


/**
* Set up settings and fields for the plugin options page
*
* @return [type] [description]
*/
public function page_init(){

  register_setting(
    $this->plugin_name . '_main',   // Option group name. This must match the group name in `settings_fields()`
    $this->option_name,             // Option name
    array( $this, 'sanitize_data' ) // Sanitize callback
  );

  add_settings_section(
    $this->plugin_name . '_main',        // Saved as the key - must correspond to 'section' in `add_settings_fields()` XX
    'Staff Area Settings',                // Title
    array( $this, 'print_section_info' ), // Callback
    $this->plugin_name                    // Page
  );

  add_settings_field(
    'id_number', // ID
    'ID Number', // Title
    array( $this, 'id_number_callback' ), // Callback
    $this->plugin_name, // Page
    $this->plugin_name . '_main' // Section - must correspond to ID of `add_settings_section()` XX
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

  // Wysiwyg field for email content
  add_settings_field(
    'email',
    'Email Content',
    array( $this, 'email_callback' ),
    $this->plugin_name,
    $this->plugin_name . '_main'
  );

}

/**
 * Add a wysiwyg field for email content
 *
 * @uses wp_editor() - note that the name attribute must equal the value of the option
 *
 * @return string email content
 */
public function email_callback() {

  $initial_content = isset( $this->options['staff_email_content'] ) ? $this->options['staff_email_content'] : '';
  $option = $this->option_name . "[staff_email_content]";
  printf(
    wp_editor(
      $initial_content,
      $this->option_name . "_email",
      array( 'textarea_name' => $option )
    )
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
    <p><input type="checkbox" value="<?= $role_value; ?>" name="<?php echo $this->option_name ; ?>[allowed_roles][]"<?php echo true === $checked ? 'checked="checked"': '';?>><?php echo $role_name; ?></p>
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
