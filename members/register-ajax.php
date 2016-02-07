<?php
namespace Staff_Area\Members;

/**
* The file that defines the AJAX registration class
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Staff Area
* @subpackage Members
*/

/**
* Process staff registration by AJAX submission
*
* @since      1.0.0
* @package    Staff Area
* @subpackage Members
* @author     David Egan <david@carawebs.com>
*/
class Register_AJAX extends Register {

	/**
	* Main facade processing function for creating users from front end form submissions
	*
	* Facade method to control AJAX submission, form validation, data processing
	* and error/success reporting.
	*
	* @package Staff_Area
	* @subpackage Members
	* @param  string $coord_ID		User ID for the coordinator
	* @param  string $user_role		User role for the user being created
	* @return mixed								Success/error message
	*
	*/
	function userform_process_facade () {

		$success_message = '';

		if ( !empty($_POST) ) {

			if ( false === $this->nonce_check( $_POST['cwNewUserNonce'], 'cw_new_user' ) ) {

					die ( 'Sorry, but the security check has failed and we can\'t process this form');

			}

			// Define variables and set to empty values, then add $_POST data if set.
			// -----------------------------------------------------------------------
			$firstname = $email = $lastname = $user_role = $coord_ID = '';

			$firstname			= $_POST['cwFirstname'];
			$lastname				= $_POST['cwLastname'];
			$email					= $_POST['cwEmail'];
			$user_role			= $_POST['cwUserRole'];
			$coord_ID				= $_POST['cwCoordID']; // For Ajax submissions, these are set by ajax-reg.js by means of jQuery
			$business_unit	= $_POST['cwUnit'];
			$cw_ajax    		= $_POST['cwAjax']; // Set cw_ajax in the jQuery function - this allows a differential response for ajax submissions

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

				// User Creation Success
				// ---------------------------------------------------------------------
				if ( true === $user_created ){

					$this->user_creation_success_message( $user_role, $new_user->new_user_info, true, $coord_ID );

				} else {

				// User Creation Failure
				// -------------------------------------------------------------------
				$this->user_creation_failure_message( $user_role, true );

				}

			}

			// Form Validation Failure
			// -----------------------------------------------------------------------

			if ( !empty($errors) ) {

				$this->user_reg_validation_fail( $errors, true );

			}

		}

	}

}
