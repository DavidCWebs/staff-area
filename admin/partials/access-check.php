<?php
$current_user     = wp_get_current_user();
$current_user_ID  = $current_user->ID;

if( !is_user_logged_in() ) {

  include_once( plugin_dir_path( dirname( __DIR__ ) ) . 'templates/partials/not-logged-in.php' );
  $access = 'no_access';
  return;

}

$access_check     = new Staff_Area\Members\Access( $current_user_ID, $access_list );
$access           = $access_check->get_access_level();
$readable_access  = $access_check->get_readable_user_role();
$first_name       = $access_check->first_name;
$access_string    = $access_check->get_access_string(); // Build a string of required access levels to give proper user feedback

if ( 'no_access' === $access ) {

  include_once( plugin_dir_path( dirname( __DIR__ ) ) . 'templates/partials/logged-in-no-access.php' );

}
