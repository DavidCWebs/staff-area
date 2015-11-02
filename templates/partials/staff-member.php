<?php
/**
 * Template for staff member details
 */
$user_data = new Staff_Area\Members\User_Data( $staff_member_ID );
$user_resources = new Staff_Area\Display\Resources_Status( $staff_member_ID, $user_data );

?>
<h3>This is the Staff Record for: <?php echo $user_resources->get_personal_data()['full_name']; ?></h3>
<?php include_once( dirname( __DIR__ ) . '/partials/staff-member-info.php' ); ?>
<hr>
<?php $user_resources->not_completed_resources_table(); ?>
<hr>
<?php $user_resources->completed_resources_table(); ?>
