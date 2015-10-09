<?php
/**
 * Partial file contains HTML for user registration form.
 * To be output on the site front end.
 *
 * Note that annotations are required for [jQuery Validation Plugin](http://jqueryvalidation.org/)
 *
 */
?>
<div class="cw-registration-form">
  <form id="user-reg-form" class="registration-form" role="form" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?> " method="post">
    <input id="cw_coordinator" type="hidden" name="coordinator_ID" value="<?php echo get_current_user_id(); ?>" />
    <div class="form-group">
      <label for="cw_firstname" class="sr-only">First Name</label>
      <input minlength="2" required autocomplete="off" type="text" name="cw_firstname" id="cw_firstname" value="" placeholder="First Name" class="input-lg form-control" />
    </div>
    <div class="form-group">
      <label for="cw_lastname" class="sr-only">Last Name</label>
      <input minlength="2" required autocomplete="off" type="text" name="cw_lastname" id="cw_lastname" value="" placeholder="Last Name" class="input-lg form-control" />
    </div>
    <div class="form-group">
      <label for="cw_email" class="sr-only">Email</label>
      <input required autocomplete="off" type="email" name="cw_email" id="cw_email" value="" placeholder="Email" class="input-lg form-control" />
    </div>
    <div class="form-group">
      <label class="control-label" for="radios">Staff Role</label>
      <?= $roles_radio; ?>
    </div>
    <?php wp_nonce_field( 'cw_new_user','cw_new_user_nonce', true, true ); ?>
    <input type="submit" name="cw_register" class="btn btn-primary btn-lg" id="btn-new-user" value="Register" />
  </form>
</div>
