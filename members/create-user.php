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
 * @package StudentStudio
 * @subpackage Users
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
  public $firstname;
  public $lastname;
  public $email;
  public $user_role;
  public $coordinator_ID;
  public $username;
  public $success_message;

  /**
   * Construct method to set properties.
   * @param string $user_role This will be either 'student', 'supervisor'.
   * @param int $coordinator_ID The originating coordinator's user ID
   * @param array $user_values
   *
   */
  function __construct ( $user_role, $coordinator_ID, $user_values ) {

    $this->user_role      = $user_role;
    $this->coordinator_ID = $coordinator_ID;
    $this->firstname      = $user_values['first_name'];
    $this->lastname       = $user_values['last_name'];
    $this->email          = $user_values['email'];
    $this->username       = $user_values['email'];

  }

  /**
   * Uses wp_insert_user to programmatically create a new user
   *
   * New user will have an auto-generated password.
   * The user login is set to the email address. Uses add_user_meta to set the user role.
   * @param  string $user_role User role: 'student' or 'supervisor'
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

    $user_id = wp_insert_user( $userdata ) ;

      if ( is_wp_error( $user_id ) ) {

        // If there is an error inserting a new user...
        $insert_user_error = $user_id->get_error_message();

      } else {

        // Set the user role
        // ---------------------------------------------------------------------
        $user = new \WP_User( $user_id );
        $user->set_role( $this->user_role );

        // Set the Student's Coordinator
        // ---------------------------------------------------------------------
        add_user_meta( $user_id, 'coordinator_id', $this->coordinator_ID); // $coordinator_id comes from the originating page

        // Set the Company for the student/supervisor
        // ---------------------------------------------------------------------
        $company = get_user_meta( $this->coordinator_ID, 'company', true );

        if ( !empty( $company )){

          add_user_meta( $user_id, 'company', $company );

        }

        // Add Student info to the **coordinators** user meta data
        // ---------------------------------------------------------------------
        if ( 'student' === $this->user_role ){

          $coordinated_students = get_user_meta( $this->coordinator_ID, 'coordinated_students', true );

          // First student, create the metadata
          // -------------------------------------------------------------------
          if ( empty ( $coordinated_students ) ){

            $first_student = array( $user_id );
            add_user_meta( $this->coordinator_ID, 'coordinated_students', $first_student );

          }

          // There are existing students, so add the user ID to existing array and update
          // -------------------------------------------------------------------
          if( !empty ( $coordinated_students ) ){

            $coordinated_students[] = $user_id;

            update_user_meta( $this->coordinator_ID, 'coordinated_students', $coordinated_students );

          }

        }

        $coordinator_info = get_userdata( $this->coordinator_ID );
        $coordinator_name = $coordinator_info->user_firstname . ' ' . $coordinator_info->user_lastname;

        // Build an array that can be returned, for a success message
        // ---------------------------------------------------------------------
        $this->new_user_info = array(
          'first_name'    => $user->user_firstname,
          'last_name'     => $user->user_lastname,
          'email'         => $user->user_email,
          'login'         => $user->user_login,
          'display_name'  => $user->user_nicename,
        );

        // Moved the email function to CW_Assign_Workbook() for STUDENTS!
        // Supervisors get an email and login on user creation - because we don't
        // want to be resetting their password every time they are associated with
        // a workbook.
        // ---------------------------------------------------------------------
        if ( 'supervisor' === $this->user_role ){

          $this->email_new_user( $password );

        }

        return true;

      }

  }

  /**
   * Send email to the new user with instructions & password.
   * Email content is generated from the content of a specified 'email' CPT -
   * this will allow site admins to control the message.
   *
   * @TODO set up success and error messages.
   * @param  string $password Auto generated password.
   * @return [type]           [description]
   *
   */
  public function email_new_user( $password ){

    // Email the user
    // --------------
    if ( 'student' === $this->user_role ){

      $email_slug = 'student-introduction';
      $login_url = esc_url( home_url('/student') );

    } elseif ( 'supervisor' === $this->user_role ){

      $email_slug = 'supervisor-introduction';
      $login_url = esc_url( home_url('/supervisor') );

    }

    // Allow html in email
    add_filter( 'wp_mail_content_type', function($content_type){

      return 'text/html';

    });

    $headers = 'From: info@studentstudio.co.uk' . "\r\n";
    $welcome = 'Welcome, ' . $this->firstname . '!';

    // Target email CPT by slug.
    $args = array(
      'name' => $email_slug,
      'post_type' => 'email',
      'post_status' => 'publish',
      'showposts' => 1,
    );

    $my_posts = get_posts($args);

    if( $my_posts ) {

      $post_id = $my_posts[0]->ID;

    }

    $post_object = get_post( $post_id );
    $mailmessage = apply_filters('the_content', $post_object->post_content); // Build a mail message from the content of a post.
    $mailmessage .= "<hr><p>Your username is: $this->email (yes, your email address!)</p><p>Your password is: $password</p>";
    $mailmessage .= "<p><a href='$login_url'>Login to Student Studio.</a></p>";

    wp_mail( $this->email, $welcome, $mailmessage, $headers );

  }

}
