<?php
include_once( plugin_dir_path( dirname (dirname( __FILE__ ) ) ) . 'includes/class-staff-area-access.php' );
$current_user     = wp_get_current_user();
$current_user_ID  = $current_user->ID;

if( !is_user_logged_in() ) {

  ?>
  <h1>Sorry...This Area is Off-Limits</h1>
  <p>You need to be a logged-in staff-member to view this content.</p>
	<p>For more info on <?php echo get_bloginfo(); ?>, why not visit our <a href="<?php echo esc_url(home_url('/') ) ; ?>">Home Page?</a></p>
  <p><a class="topspace btn btn-primary btn-lg" href="<?php echo wp_login_url(); ?>" title="Login">Login to the Site</a></p>
  <?php

  $access = 'no_access';
  return;

}

$access_check = new Carawebs\Staff\Access( $current_user_ID );
$access = $access_check->get_access_level();

if ( 'no_access' === $access ) {

  ?>
  <h1>Sorry...This Area is Off-Limits</h1>
  <p>You need to be a staff-member to view this content.</p>
	<p>For more info on <?php echo get_bloginfo(); ?>, why not visit our <a href="<?php echo esc_url(home_url('/') ) ; ?>">Home Page?</a></p>
  <?php

}
caradump( $access_check );
