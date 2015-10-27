<?php
namespace Staff_Area\Members;
/**
 * Create a new user from front-end form input.
 *
 * All input values MUST have been sanitized previously. Form render, validation & sanitization
 * is handled by separate classes, to separate the logic.
 *
 * This class is typically called by a facade type function. For Student Studio, the following classes are related:
 *
 * - CW_Reg_Validator, which validates & sanitizes input, /classes/class-cw-reg-validator.php
 * - CW_View_Form, which builds the form, /classes/class-cw-view-form.php
 *
 * The facade function used for supervisor & student registration is carawebs_userform_process_facade()
 *
 * WordPress Version 4.1
 * @package Staff Area
 * @subpackage Members
 * @author David Egan <david@carawebs.com>
 * @see CW_Reg_Validator()
 * @see CW_View_Form()
 * @see carawebs_userform_process_facade()
 *
 * @TODO return error messages if:
 * - email doesn't get sent
 * - wp_insert_user fails
 *
 */

class Create_User {

  public $new_user_info = array();

  public $insert_user_error;

  /**
   * The new user's first name
   * @var string
   */
  public $firstname;

  /**
   * The new user's last name
   * @var string
   */
  public $lastname;

  public $business_unit;

  /**
   * The new user's email address
   * @var string
   */
  public $email;

  /**
   * User Role: WP role for this user
   * @var string role
   */
  public $user_role;

  /**
   * ID of the creating user
   * @var string|int
   */
  public $manager_ID;

  /**
   * The new user's username
   * @var string
   */
  public $username;

  /**
   * The success message
   * @var string
   */
  public $success_message;

  /**
   * Construct method to set properties.
   * @param string $user_role This will be either 'student', 'supervisor'.
   * @param int $manager_ID The originating manager's user ID
   * @param array $user_values
   *
   */
  function __construct ( $user_role, $manager_ID, $user_values ) {

    $this->user_role      = $user_role;
    $this->manager_ID     = $manager_ID;
    $this->firstname      = $user_values['first_name'];
    $this->lastname       = $user_values['last_name'];
    $this->email          = $user_values['email'];
    $this->username       = $user_values['email'];
    $this->business_unit  = $user_values['business_unit'];

  }

  /**
   * Uses wp_insert_user to programmatically create a new user
   *
   * New user will have an auto-generated password.
   * The user login is set to the email address.
   *
   * @uses    wp_generate_password()
   * @uses    wp_insert_user()
   * @uses    add_user_meta() to set the user role.
   * @return [type]            [description]
   *
   */
  public function register() {

    $password = wp_generate_password();
    $username = $this->username;

    $userdata = array(
      'user_login'      => $this->email,
      'user_pass'       => $password,
      'user_email'      => $this->email,
      'first_name'      => $this->firstname,
      'last_name'       => $this->lastname,
      'user_nicename'   => $this->firstname,
    );

    // Create the new user
    $user_id = wp_insert_user( $userdata ) ;

    if ( is_wp_error( $user_id ) ) {

      // If there is an error inserting a new user capture the message
      $insert_user_error = $user_id->get_error_message();

    } else {

      // Set the user role
      $user = new \WP_User( $user_id );
      $user->set_role( $this->user_role );

      // Set the User's Manager - $this->manager_id comes from the originating page
      add_user_meta( $user_id, 'manager_id', $this->manager_ID);

      // Add the business unit to the usermeta record
      add_user_meta( $user_id, 'business_unit', $this->business_unit );

      // Add new user info to the **managers** user meta data
      $this->update_manager_meta( $user_id );

      //$manager_info = get_userdata( $this->manager_ID );
      //$manager_name = $manager_info->user_firstname . ' ' . $manager_info->user_lastname;

      // Build an array that can be returned, for a success message
      // -----------------------------------------------------------------------
      $this->new_user_info = array(
        'first_name'    => $user->user_firstname,
        'last_name'     => $user->user_lastname,
        'email'         => $user->user_email,
        'login'         => $user->user_login,
        'display_name'  => $user->user_nicename,
      );

      // Email new user
      // -----------------------------------------------------------------------
      $email = new \Staff_Area\Includes\Email( $this->email, $user_id, $this->firstname, $this->lastname, $this->user_role );
      $email->email_new_user( $password );

      return true;

    }

  }

  /**
   * [update_manager_meta description]
   * @param  [type] $user_id [description]
   * @return [type]          [description]
   */
  private function update_manager_meta( $user_id ) {

    $users_created = get_user_meta( $this->manager_ID, 'created_users', true );

    // First user, create the metadata
    if ( empty ( $users_created ) ){

      $first_user = array( $user_id );
      add_user_meta( $this->manager_ID, 'created_users', $first_user );

    }

    // There are existing created users, so add the user ID to existing array and update
    if( !empty ( $users_created ) ){

      $users_created[] = $user_id;

      update_user_meta( $this->manager_ID, 'created_users', $users_created );

    }

  }

}
