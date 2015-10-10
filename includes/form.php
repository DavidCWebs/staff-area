<?php
namespace Staff_Area\Includes;

class Form {

  /**
	 * Return an array of user roles
	 *
	 * @since 1.0.0
	 * @return array An array of user role names
	 *
	 *
	 */
	protected function get_user_roles(){

		global $wp_roles;

		if ( ! isset( $wp_roles ) ){

			$wp_roles = new WP_Roles();

		}

		return $wp_roles->get_names();

	}

	/**
	 * Options list for form, allows user role to be selected.
	 *
	 * @since 1.0.0
	 * @return HTML Option list with user roles
	 */
	protected function users_options(){

		?>
		<option value="0">All Users<?php
		?></option>
		<?php
		$user_roles = $this->get_user_roles();

		if( $user_roles ) {

			if ( is_array( $user_roles ) ){

				foreach( $user_roles as $user_role ) {

					echo '<option value="' . $user_role . '">' . $user_role . '</option>';

				}

			} else {

				echo '<option value="' . $user_role . '">' . $user_role . '</option>';

			}

		}

	}

	protected function data_fields() {

		$values = [
			'First Name',
			'Second Name',
			'Email'
		];

		ob_start();

		$i = 1;
		foreach ( $values as $value ) {

			$var_value = strtolower( str_replace(" ", "_", $value ) );

			?>
			<div class="checkbox">
				<label for="checkboxes-<?= $i; ?>">
					<input type="checkbox" name="fields[<?= $var_value; ?>]" id="checkboxes-<?= $i; ?>" value=true>
					<?= $value; ?>
				</label>
			</div>
			<?php

			$i++;

		}

		echo ob_get_clean();

	}

}
