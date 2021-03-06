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
if( !defined('WPINC') ) exit( 'No direct access permitted' );

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
	 * Set custom templates for this plugin's content
	 *
	 * This is a callback function hooked to the 'template_include' filter.
	 *
	 * @param  [type] $page_template [description]
	 * @return [type]                [description]
	 */
	public function staff_area_page_template_routes( $page_template ) {

		if ( is_singular( 'staff-resource' ) ) {

      $page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/single-staff-resource.php';

    }

    if ( is_singular( 'management-resource' ) ) {

      $page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/single-management-resource.php';

    }

		if ( is_page( 'staff' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/page-staff.php';

		}

    if ( is_page( 'staff-directory' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/page-staff-directory.php';

		}

    if ( is_page( 'staff-management' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/page-staff-management.php';

		}

    if ( is_page( 'management-resources' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/page-management-resources.php';

		}

    if ( is_page( 'staff-member' ) ) {

			$page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/page-staff-member.php';

		}

    if ( is_post_type_archive( ['staff-resource', 'management-resource'] ) || is_tax( 'resource-category' ) || is_tax( 'management-resource-category') ) {

      $page_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/archive-staff-area.php';

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

  public function save_custom_user_meta( $user_id ) {

    //if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

    update_user_meta( $user_id, 'business_unit', (int)$_POST['business_unit'] );

    update_user_meta( $user_id, 'phone_number', $_POST['phone_number'] );

  }


  public function business_unit_form( $user ) {

    $current_value = get_the_author_meta( 'business_unit', $user->ID );
    $units         = \Staff_Area\Helpers\Post_Data::custom_post_type_IDs( 'business-unit' );

    ?>
    <h3>Custom User Data</h3>

    <table class="form-table">
      <tr>
        <th>Business Unit</th>
        <td>
          <select id="business_unit" name="business_unit">
            <?php

            foreach( $units as $unit ) {
              ?>
              <option value="<?= $unit; ?>" <?php echo $unit == $current_value ? " selected='selected'" : ""; ?>>
                <?= get_the_title( $unit ); ?>
              </option>
              <?php

            }
            ?>
          </select>
        </td>
      </tr>
    </table>
    <?php
  }

  public function phone_number_form( $user ) {

    $current_value  = get_the_author_meta( 'phone_number', $user->ID );
    $current_value  = !empty ( $current_value ) ? $current_value : '';

    ?>
    <table class="form-table">
      <tr>
        <th>Phone Number</th>
        <td>
          <input name='phone_number' value='<?php echo htmlspecialchars($current_value); ?>' type='tel' pattern='[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}' title='Phone Number (Format: 087 123 4567'>
        </td>
      </tr>
    </table>
    <?php
  }

  public function edit_staff_resource_columns( $columns ) {

    $new_columns = array(
  		'compulsory' => __( 'Compulsory?' ),
  	);

  	return array_merge( $columns, $new_columns );


  }

  /**
   * [filter_email_from description]
   *
   * This method is a callback, hooked to the 'wp_mail_from' filter  in `includes/staff-area.php`
   *
   * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_mail_from
   * @return [type] [description]
   */
  public function email_from( $original_email_address ) {

    return "info@schoolfood.ie";

  }

  /**
   * [filter_email_from_name description]
   *
   * This method is a callback, hooked to the 'wp_mail_from_name'
   *
   * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_mail_from_name
   * @return [type] [description]
   */
  public function email_from_name( $original_name ) {

    return "School Food Co Management";

  }


}
