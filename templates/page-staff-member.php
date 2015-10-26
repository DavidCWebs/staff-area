<?php
/**
 * Display staff member data
 *
 */
$staff_member_ID = isset ( $_GET['staff_member'] ) ? $_GET['staff_member'] : null;
$access_list = ['staff_manager'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/page-header.php' );
?>
<p><a href="<?php echo esc_url( home_url('/staff-management') ); ?>">&laquo;&nbsp;Back to Staff Management</a></p>
<?php
//$compulsory = Staff_Area\Includes\Loop::get_post_IDs( 'staff-resource', true );
//echo "Compulsory: " . implode(', ', $compulsory );
// The view for Staff Supervisors, Staff Managers, Site Admins, Site Editors
// -----------------------------------------------------------------------------
if ( 'full_access' == $access || 'supervisor_access' == $access || 'manager_access' == $access ) {

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/staff-member.php' );

}
