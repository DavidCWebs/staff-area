<?php
namespace Staff_Area\Members;

/**
* The file that defines the registration fallback class
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Staff Area
* @subpackage Members
*/

/**
* Register users when form submitted via POST
*
* @since      1.0.0
* @package    Staff Area
* @subpackage Members
* @author     David Egan <david@carawebs.com>
*/
class Register_Fallback extends Register {

	/**
	 * Form processor for PHP submission via post method
	 *
	 * In the normal course of events, form submission will take place by means of
	 * AJAX - so this method will never be called. It is here to comply with the
	 * principle of progressive enhancement, allowing users to submit the form
	 * even if Javascript has been disabled.
	 *
	 * @return void
	 */
	public function form_facade() {

		// Only allow PHP form submission from the specified page
		// -----------------------------------------------------------------------
		if ( ! is_page( 'staff-registration' ) ) {

			return;

		}

		if ( !empty($_POST) ) {

			if ( false === $this->nonce_check( $_POST['cw_new_user_nonce'], 'cw_new_user' ) ) {

					die ( 'Sorry, but the security check has failed and we can\'t process this form');

			}

			// Define variables and set to empty values, then add $_POST data if set.
			// -----------------------------------------------------------------------
			$firstname = $email = $lastname = $user_role = $coord_ID = '';

			$firstname			= $_POST['cw_firstname'];
			$lastname				= $_POST['cw_lastname'];
			$email					= $_POST['cw_email'];
			$user_role			= $_POST['role'];
			$coord_ID				= $_POST['coordinator_ID']; // For Ajax submissions, these are set by ajax-reg.js by means of jQuery
			$business_unit	= $_POST['business_unit'];

			// Validation Checks
			// -----------------------------------------------------------------------
			$form = new Validator( $firstname, $lastname, $email, $business_unit );
			$form->is_valid(); // returns boolean true if no errors, otherwise $errors array
			$errors = $form->get_errors(); // Get the validation errors, if they exist.

			// Form data is valid
			// -----------------------------------------------------------------------
			if ( true === $form->is_valid() ) {

				// Create new user PASS SANITIZED VALUES HERE!
				// ---------------------------------------------------------------------
				$new_user = new Create_User( $user_role, $coord_ID, $form->get_sanitized_values() );

				// register a user, using values that have been checked for errors & sanitised
				// If the user is created, $user_created will be set to true.
				$user_created = $new_user->register( $user_role );

				if ( true === $user_created ) {
					// User Creation Success
					// -------------------------------------------------------------------
					$_SESSION['form_report'] = $this->user_creation_success_message( $user_role, $new_user->new_user_info, false, $coord_ID );

				} else {
					// User Creation Failure
					// -------------------------------------------------------------------
					$_SESSION['form_report'] = $this->user_creation_failure_message( $user_role, false );

				}

			}

			// There are validation errors
			// -------------------------------------------------------------------------
			if ( !empty( $errors ) && !empty( $_POST ) ) {

				$fail_validation = $this->user_reg_validation_fail( $errors, false );

				$_SESSION['form_report'] = $fail_validation;

			}

		}

	}

}
