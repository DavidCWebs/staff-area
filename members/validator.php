<?php
namespace Staff_Area\Members;
/**
 * Class to validate & sanitise form input, for user registration.
 *
 * This is used for front-end user creation.
 *
 * @package StudentStudio
 * @subpackage Users
 * @author David Egan <david@carawebs.com>
 *
 *
 */
class Validator {

  public $form_errors = array();
  public $user_values = array();

  /**
   * Construct method
   * @param string $firstname Un-sanitized form input
   * @param string $lastname  Un-sanitized form input
   * @param string $email     Un-sanitized form input
   *
   */
  function __construct ( $firstname, $lastname, $email, $business_unit ) {

    $this->firstname      = $firstname;
    $this->lastname       = $lastname;
    $this->email          = $email;
    $this->business_unit  = $business_unit;

  }

  /**
   * Check validity of class properties
   * @return boolean Returns true if there are no errors, otherwise pushes errors into $form_errors array.
   *
   */
  public function is_valid() {

    // allow letters (both cases), hyphen, space and (\').
    $preg_str_check="#[^][(\\\\') A-Za-z-]#";

    if ( empty( $this->email ) ) {

      array_push( $this->form_errors, 'You must enter an email address.');

    }

    if ( empty( $this->firstname ) ) {

      array_push( $this->form_errors, 'You must enter a first name.');

    }

    if( preg_match( $preg_str_check, $this->firstname ) ) {

      array_push( $this->form_errors, 'The first name you entered doesn\'t look right - please try again with alphabetic characters only.');

    }

    if ( empty( $this->lastname ) ) {

      array_push( $this->form_errors, 'You must enter a last name.');

    }

    if( preg_match( $preg_str_check, $this->lastname ) ) {

      array_push( $this->form_errors, 'The last name you entered doesn\'t look right - please try again with alphabetic characters only.');

    }

    if ( ( $this->email) && !is_email( $this->email ) ) {

      array_push( $this->form_errors, 'The email address you submitted doesn\'t look right - please try again, with the format name@domain.com');

    }

    if ( email_exists( $this->email ) ) {

      array_push( $this->form_errors, 'This email is already in use.');

    }

    //if( preg_match( $preg_str_check, $this->business_unit ) ) {

    //  array_push( $this->form_errors, 'The business unit you entered doesn\'t look right - please try again with alphabetic characters only.');

    //}

    if ( empty($this->form_errors) ) { // There are no errors, so return the string 'true'

      return true;

    }

  }

  /**
   * Return form submission/validation errors
   * @return array Form errors.
   *
   */
  public function get_errors() {

    return $this->form_errors;

  }

  /**
   * Sanitize input user name data
   *
   * @param  string $data Form input data: firstname, lastname.
   * @return string       Cleaned up data - sanitised.
   */
  public function sanitise_name( $data ) {

    $data = trim( $data );
    $data = stripslashes( $data );
    $data = strip_tags( $data );
    $data = htmlspecialchars( $data );

    return $data;

    /* TM: May simplify this to the following:
     * return trim( stripslashes ( strip_tags( htmlspecialchars( $data ) ) ) );
     * DE: will keep it readable for now
     */

  }

  /**
   * Build an array of sanitized data relating to the new user
   *
   * Names sanitized with $this->sanitise_name(). Email sanitized with sanitize_email().
   *
   * @see https://codex.wordpress.org/Function_Reference/sanitize_email
   * @return array $this->user_values Array of sanitized user data: firstname, lastname, email address
   *
   */
  public function get_sanitized_values() {

    $this->firstname      = $this->sanitise_name( $this->firstname );
    $this->lastname       = $this->sanitise_name( $this->lastname );
    $this->email          = sanitize_email( $this->email ); // Strips out all characters that are not allowable in an email address.
    $this->business_unit  = (int)$this->business_unit;

    $this->user_values = array(
      'first_name'    => $this->firstname,
      'last_name'     => $this->lastname,
      'email'         => $this->email,
      'business_unit' => $this->business_unit
    );

    return $this->user_values;

  }

}
