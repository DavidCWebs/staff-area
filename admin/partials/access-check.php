<?php
//include_once( plugin_dir_path( dirname (dirname( __FILE__ ) ) ) . 'includes/class-staff-area-access.php' );
$current_user     = wp_get_current_user();
$current_user_ID  = $current_user->ID;

if( !is_user_logged_in() ) {

  include_once( plugin_dir_path( dirname( __DIR__ ) ) . 'templates/partials/not-logged-in.php' );
  $access = 'no_access';
  return;

}

$access_check = new Staff_Area\Members\Access( $current_user_ID, $access_list );
$access       = $access_check->get_access_level();
$first_name   = $access_check->first_name;

if ( 'no_access' === $access ) {

  include_once( plugin_dir_path( dirname( __DIR__ ) ) . 'templates/partials/logged-in-no-access.php' );

}
