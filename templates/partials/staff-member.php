<?php
/**
 * Template for staff member details
 */
$userdata = new Staff_Area\Members\User_Data( $staff_member_ID );
$user_resources = new Staff_Area\Display\Member_Resource_Status( $staff_member_ID );
//caradump( $user_resources, '$user_resources' );
//caradump( $userdata, '$userdata' );
////$userdata = $user->get_userdata();  // Data for this user
//$completed_resources = $user->get_completed_resources();  // Staff Resources completed by this user
?>
<h3>This is the Staff Record for: <?php //echo $userdata['full_name']; ?></h3>
<?php //include_once( dirname( __DIR__ ) . '/partials/staff-member-info.php' ); ?>


<h3>Staff Resources</h3>
<?php $user_resources->completed_resources_table(); ?>

<?php $user_resources->not_completed_resources_table(); ?>
