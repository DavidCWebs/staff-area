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

		$roles_radio	= $this->roles_radio();
		$unit_options	= $this->business_unit_options();

		include_once( dirname( __DIR__ ) . '/templates/partials/user-reg-form.php' );

	}

	/**
	 * Dynamically build radio buttons for user-role selection in the registration form.
	 *
	 * @return string HTML for radio buttons
	 */
	public function roles_radio() {

		$allowed_roles = $this->allowed_roles();

		ob_start();

		$i = 0;

		foreach( $allowed_roles as $role ) {

			?>
			<div class="radio">
				<label for="radio-role-<?= $i; ?>">
					<input type="radio" name="role" id="radio-role-<?= $i; ?>" class="cw_user_role" tabindex="<?= $i + 2; ?>" value="<?= $role['role']; ?>">
					<?= $role['display']; ?>
				</label>
			</div>
			<?php

			$i++;

		}

		return ob_get_clean();

	}

	public function business_unit_options() {

		$units = $this->get_business_units();

		ob_start();

		$i = 0;

		?>
		<div class="business-unit">
			<select id="cw_business_unit" name="business_unit" class="selectpicker" required>
				<?php

				foreach( $units as $unit ) {
					?>
					<option value="<?= $unit; ?>">
						<?= $unit; ?>
					</option>
					<?php

					$i++;

				}
				?>
			</select>
		</div>
		<?php

		return ob_get_clean();

	}

	/**
	 * Make an array of data for allowed roles to be used in the registration form.
	 *
	 * Returns an associative array of allowed roles and role (display) names. Roles are
	 * selected by admin users in the plugin options page.
	 *
	 * @see Options()
	 * @uses get_option()
	 * @return array  Associative array of roles and role names
	 */
	private function allowed_roles() {

		$roles = get_option( 'carawebs_staff_area_data' )['allowed_roles'];
		$allowed_roles = [];

		global $wp_roles;

		foreach ( $roles as $role ) {

			$allowed_roles[] = [
				'role'		=> $role,
				'display'	=> $wp_roles->roles[$role]['name']
			];

		}

		return $allowed_roles;

	}

	private function get_business_units() {

		return [
			'Ennis Community College',
			'Ennistymon Vocational School',
			'Scoil Mhuire Ennis',
			'Castletroy Community College'
		];


	}

	/**
	 * Make a hardcoded array of data for allowed roles to be used in the registration form.
	 *
	 * Returns an associative array of allowed roles and role (display) names.
	 *
	 * @deprecated
	 * @param 	array $roles an array of allowed role names
	 * @return array  Associative array of roles and role names
	 */
	public function hardcoded_allowed_roles( $roles ) {

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
