<?php
/**
 * Template for staff member details
 */
$user = new Staff_Area\Members\User_Data( $staff_member_ID );
$userdata = $user->get_userdata();  // Data for this user
$completed_resources = $user->get_completed_resources();  // Staff Resources completed by this user
?>
<h3>This is the Staff Record for: <?php echo $userdata['full_name']; ?></h3>
<?php include_once( dirname( __DIR__ ) . '/partials/staff-member-info.php' ); ?>


<h3>Staff Resources</h3>
<?php $user->resources_table( 'complete' ); ?>
