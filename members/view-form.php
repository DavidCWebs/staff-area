<?php
namespace Staff_Area\Members;
/**
* The file that defines the form view
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Carawebs_User_Management
* @subpackage Carawebs_User_Management/includes
*/

/**
* Create a HTML form for front-end user registration.
*
* @since      1.0.0
* @package    Carawebs_User_Management
* @subpackage Carawebs_User_Management/includes
* @author     Your Name <email@example.com>
*/
class View_Form {

	/**
	* Set up properties
	* @param string $user_role   The user role for the new user
	* @param int $coordinator_ID The user ID of the originating coordinator
	*/
	function __construct () {

		//$this->user_role      = $user_role;
		//$this->coordinator_ID = $coordinator_ID;

	}

	/**
	* Build a front-end form for user creation
	* @return string Form html
	*
	*/
	public function render( $success_message = '' ) {

		$roles_radio = $this->roles_radio();

		$partial = include_once( dirname( __DIR__ ) . '/public/partials/user-reg-form.php' );

	}

	/**
	 * Dynamically build radio buttons for user-role selection in the registration form.
	 *
	 * @return string HTML for radio buttons
	 */
	public function roles_radio() {

		$allowed_roles = $this->allowed_roles( array( 'staff_supervisor', 'staff_member' ) );

		ob_start();

		$i = 0;

		foreach( $allowed_roles as $role ) {

			?>
			<div class="radio">
				<label for="radios-<?= $i; ?>">
					<input type="radio" name="role" id="cw_user_role" value="<?= $role['role']; ?>" checked="checked">
					<?= $role['display']; ?>
				</label>
			</div>
			<?php

			$i++;

		}

		return ob_get_clean();

	}

	/**
	 * Make an array of data for allowed roles to be used in the registration form.
	 *
	 * Returns an associative array of allowed roles and role (display) names.
	 *
	 * @TODO		Allowed roles should be passed in from an options page.
	 * @param 	array $roles an array of allowed role names
	 * @return array  Associative array of roles and role names
	 */
	public function allowed_roles( $roles ) {

		global $wp_roles;

		$allowed_roles = [];

		foreach ( $roles as $role ) {

			$allowed_roles[] = [
				'role'		=> $role,
				'display'	=> $wp_roles->roles[$role]['name']
			];

		}

		return $allowed_roles;

	}

}
