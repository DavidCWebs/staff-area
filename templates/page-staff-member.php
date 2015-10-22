<?php
/**
 * Display staff member data
 *
 */
get_template_part('templates/page', 'header');
$staff_member_ID = isset ( $_GET['staff_member'] ) ? $_GET['staff_member'] : null;
$access_list = ['staff_manager', 'staff_member'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/welcome.php' );

// The view for Staff Supervisors, Staff Managers, Site Admins, Site Editors
// ---------------------------------------------------------------------------
if ( 'full_access' == $access || 'supervisor_access' == $access || 'manager_access' == $access ) {

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/staff-member.php' );

}