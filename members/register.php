<?php
namespace Staff_Area\Members;

/**
* The file that defines the registration class
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Staff Area
* @subpackage Members
*/

/**
* Common methods for user registration
*
* @since      1.0.0
* @package    Staff Area
* @subpackage Members
* @author     David Egan <david@carawebs.com>
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
	 * Check to see if the submitted nonce matches the generated nonce
	 * @package Staff Area
	 * @subpackage Members
	 * @param  string $nonce		The nonce value
	 * @param  string $action		The nonce action (description)
	 * @return bool							true denotes that the nonce has been verified
	 */
	protected function nonce_check( $nonce, $action ) {

		if ( wp_verify_nonce( $nonce, $action ) ){

			return true;

		} else {

			return false;

		}

	}

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
	* @package Staff Area
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
		<li>Business Unit: {$new_user_details['business_unit']}</li>
		</ul>
		You can create more staff members using this form if you like.";

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
