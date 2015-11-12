<?php
/**
 * Display staff member data
 *
 */

/**IP check
echo $_SERVER['REMOTE_ADDR'];
if( '88.87.172.77' !== $_SERVER['REMOTE_ADDR'] && '127.0.0.1' !== $_SERVER['REMOTE_ADDR'] ) {
  return;
}
*/
$log = get_option('limit_login_logged');
caradump( $log, 'log collected by limit login plugin' );

$staff_member_ID = isset ( $_GET['staff_member'] ) ? $_GET['staff_member'] : null;
$access_list = ['staff_manager'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}

// The view for Staff Supervisors, Staff Managers, Site Admins, Site Editors
// -----------------------------------------------------------------------------
if ( 'full_access' == $access || 'supervisor_access' == $access || 'manager_access' == $access ) {

  $user_data = new Staff_Area\Members\User_Data( $staff_member_ID );
  $user_resources = new Staff_Area\Display\Resources_Status( $staff_member_ID, $user_data );
  // btn class: class="btn btn-primary btn-lg"

  echo '<p style="margin-top: 10px;"><a href=' . esc_url( home_url('/staff-directory') ) . '>&laquo;&nbsp;Go To to Staff Directory</a></p>';
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/page-header.php' );
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/staff-member.php' );

}
