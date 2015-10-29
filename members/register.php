<?php
namespace Staff_Area\Members;

/**
* The file that defines the registration class
*
* A class definition that includes attributes and functions used across both the
* public-facing side of the site and the admin area.
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Carawebs_User_Management
* @subpackage Carawebs_User_Management/includes
*/

/**
* The core plugin class.
*
* This is used to define internationalization, admin-specific hooks, and
* public-facing site hooks.
*
* Also maintains the unique identifier of this plugin as well as the current
* version of the plugin.
*
* @since      1.0.0
* @package    Carawebs_User_Management
* @subpackage Carawebs_User_Management/includes
* @author     Your Name <email@example.com>
*/
class Register {

	/**
	* Define the core functionality of the registration process.
	*
	*
	* @since    1.0.0
	*/
	public function __construct() {

		//$this->coord_ID = $coord_ID;

	}

	/**
	* Facade method to control form validation, data processing and error/success reporting.
	*
	* Main facade processing function for creating users from front end form submissions.
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

		$nonce = isset ($_POST['cwNewUserNonce']) ? $_POST['cwNewUserNonce'] : $_POST['cw_new_user_nonce'];

			// check to see if the submitted nonce matches the generated nonce
			if ( ! wp_verify_nonce( $nonce, 'cw_new_user' ) ){

				die ( 'Sorry, but the security check has failed and we can\'t process this form');

			}

			// Define variables and set to empty values, then add $_POST data if set.
			// -----------------------------------------------------------------------
			$firstname = $email = $lastname = $user_role = $coord_ID = '';

			$firstname			= isset( $_POST['cwFirstname'] )	? $_POST['cwFirstname']	: $_POST['cw_firstname'];
			$lastname				= isset( $_POST['cwLastname'] )		? $_POST['cwLastname']	: $_POST['cw_lastname'];
			$email					= isset( $_POST['cwEmail'] )			? $_POST['cwEmail']			: $_POST['cw_email'];
			$user_role			= isset( $_POST['cwUserRole'] )		? $_POST['cwUserRole']	: $_POST['role'];
			$coord_ID				= isset( $_POST['cwCoordID'] )		? $_POST['cwCoordID']		: $_POST['coordinator_ID']; // For Ajax submissions, these are set by ajax-reg.js by means of jQuery
			$business_unit	= isset( $_POST['cwUnit'] )				? $_POST['cwUnit']			: $_POST['business_unit'];
			$cw_ajax    		= isset( $_POST['cwAjax'] )				? $_POST['cwAjax']			: false; // Set cw_ajax in the jQuery function - this allows a differential response for ajax submissions

			// Only allow PHP form submission from the specified page
			if ( false === $cw_ajax ) {

				if ( ! is_page( 'staff-registration' ) ) {

					exit ( "You can't submit the form from this page." );

				}

			}

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

					if ( true === $cw_ajax ) {

						$this->user_creation_success_message( $user_role, $new_user->new_user_info, $cw_ajax, $coord_ID );

					} else {

						$success_message = $this->user_creation_success_message( $user_role, $new_user->new_user_info, $cw_ajax, $coord_ID );

					}

				} else {

					// User Creation Failure
					// -------------------------------------------------------------------
					if ( true === $cw_ajax ) {

						// If ajax submission, returns an Ajax object
						// -----------------------------------------------------------------
						$this->user_creation_failure_message( $user_role, $cw_ajax );

					} else {

						// This function returns a response tailored for PHP
						// -----------------------------------------------------------------
						$fail_message_php = $this->user_creation_failure_message( $user_role, $cw_ajax );

					}

				}

			}

			// Form Validation Failure: Response for Ajax callback
			// -----------------------------------------------------------------------

			if ( !empty($errors) ) {

				$this->user_reg_validation_fail( $errors, $cw_ajax );

			}

		}

		$view = new View_Form();
		echo $view->render();

		echo '<div class="indicator">Please wait...</div>';

		// Success message PHP
		// -------------------------------------------------------------------------
		if ( !empty( $success_message ) ) {

			echo "<div id='result-message-show' class='well topspace'>$success_message</div>";

		}

		// There are validation errors, and the $_POST was submitted by PHP
		// -------------------------------------------------------------------------
		if ( !empty( $errors ) && !empty( $_POST ) && (false == $cw_ajax) ) {

			$fail_validation = $this->user_reg_validation_fail( $errors, $cw_ajax );

			echo "<div id='result-message-show' class='well topspace'>$fail_validation</div>";

		}

		// User creation error - PHP
		// -------------------------------------------------------------------------
		if ( !empty( $fail_message_php ) ) {

			echo $fail_message_php;

		}

	}

	/**
	* Link the facade function to the jQuery function/Ajax processor.
	* This is set in assets/js/ajax-reg.js
	*
	* @see userform_process_facade()
	*
	*/
	//add_action('wp_ajax_register_new_user', 'userform_process_facade');

	/**
	* Build a relevant message on user-creation success.
	*
	* 	$new_user_info = array(
	*    'first_name'    => $user->user_firstname,
	*    'last_name'     => $user->user_lastname,
	*    'email'         => $user->user_email,
	*    'login'         => $user->user_login,
	*    'display_name'  => $user->user_nicename,
	*    );
	*
	* @package StudentStudio
	* @subpackage Users
	* @param  string $user_role        'student' or 'supervisor'
	* @param  array $new_user_details  User data for the new user.
	* @param  boolean                  true === data submitted by Ajax.
	* @return mixed                    JSON object for Ajax, string for PHP
	* @see userform_process_facade()
	*
	*/
	function user_creation_success_message( $user_role, $new_user_details, $cw_ajax, $coord_ID ){

		$user_role_name = $this->get_role_name( $user_role );

		// Build a success message
		// -------------------------------------------------------------------------
		$user_created =
		"<h2>Success!</h2>You've just created a new $user_role_name. Their details are:
		<ul class='user-list'>
		<li>Name: {$new_user_details['first_name']} {$new_user_details['last_name']}</li>
		<li>Email: {$new_user_details['email']}</li>
		<li>Login Username: {$new_user_details['login']}</li>
		<li>Display name: {$new_user_details['display_name']}</li>
		</ul>
		You can create more staff members using the above form if you like.";

		//$next_steps = $this->next_steps_message ( $user_role, $coord_ID );

		// Success Return for Ajax
		// -------------------------------------------------------------------------
		if ( true == $cw_ajax ) {

			$response               = array(); // this will be a JSON array
			$response['status']     = 'success';
			$response['message']    = $user_created;
			//$response['nextSteps']  = $next_steps;

			return wp_send_json( $response ); // sends $response as a JSON object

		} else {

			// Success Return for PHP
			// -----------------------------------------------------------------------
			return $user_created;

		}

	}

	private function get_role_name( $user_role ) {

		global $wp_roles;
    return $wp_roles->roles[$user_role]['name'];

	}

	/**
	* Display relevant message on user-creation failure
	*
	* @package StudentStudio
	* @subpackage Users
	* @param  string $user_role  User role
	* @param  boolean $cw_ajax   true == submitted by Ajax
	* @return mixed              If Ajax, JSON object. If PHP, string.
	* @see userform_process_facade
	*
	*/
	function user_creation_failure_message( $user_role, $cw_ajax ){

		$user_creation_failure = "$user_role user creation failed.";

		// Failure return for Ajax
		// -------------------------------------------------------------------------
		if ( true == $cw_ajax ) {

			$response = array(); // this will be a JSON array
			$response['status']       = 'error';
			$response['message']      = $user_creation_failure;//json_encode($user_creation_failure);

			return wp_send_json( $response ); // sends $response as a JSON object

		} else {

			// Failure return for PHP
			// -----------------------------------------------------------------------
			return $user_creation_failure;

		}

	}

	/**
	* Error messages for user registration validation failure
	*
	* @package StudentStudio
	* @subpackage Users
	* @param  array $errors    Error array returned from
	* @param  boolean $cw_ajax true == returned by Ajax
	* @return mixed            If Ajax, JSON object. If PHP, string.
	* @see userform_process_facade
	*/
	function user_reg_validation_fail( $errors, $cw_ajax ) {

		// Build a common error message
		// -------------------------------------------------------------------------
		$error_message =
		'<p>Sorry - we can\'t process your form because:</p>
		<ul class="error-list">';

		foreach ( $errors as $error ) {

			$error_message .= '<li>' . $error . '</li>';

		}

		$error_message .= '</ul>';

		if ( true == $cw_ajax ) {
			// Error message for Ajax
			// -----------------------------------------------------------------------
			$response = array(); // this will be a JSON array
			$response['status']       = 'error';
			$response['message']      = $error_message;
			wp_send_json( $response ); // sends $response as a JSON object

		} else {

			// Error message for PHP
			// -----------------------------------------------------------------------
			return $error_message;

		}

	}

}
