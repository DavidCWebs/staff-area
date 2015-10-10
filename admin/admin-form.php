<?php
namespace Staff_Area\Admin;

class Admin_Form extends \Staff_Area\Includes\Form {

  public function render_form() {

    ob_start();

    ?>
    <div class="wrap">
      <h2><?php _e( 'Export users to a CSV file', 'carawebs-csv' ); ?></h2>
      <?php
      if ( isset( $_GET['error'] ) ) {

        echo '<div class="updated"><p><strong>' . __( 'No user found.', 'carawebs-csv' ) . '</strong></p></div>';

      }
      ?>
      <form method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field( 'carawebs_csv_nonce_55', 'security' ); ?>
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><label for"pp_eu_users_role"><?php _e( 'Role', 'carawebs-csv' ); ?></label></th>
            <td>
              <select id="select-user-role" name="select-user-role" class="form-control">
  							<?php $this->users_options(); ?>
  				    </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for"pp_eu_users_role"><?php _e( 'Data to Include', 'carawebs-csv' ); ?></label></th>
            <td>
              <?php $this->data_fields(); ?>
            </td>
          </tr>
        </table>
        <p class="submit">
          <!--<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />-->
          <input type="hidden" name="action" value="process_csv" />
          <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Generate CSV Report', 'carawebs-csv' ); ?>" />
        </p>
      </form>
      <?php

      echo ob_get_clean();

  }

}
