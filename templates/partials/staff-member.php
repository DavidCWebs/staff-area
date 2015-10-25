<?php
/**
 * Template for staff member details
 */
$userdata = new Staff_Area\Members\User_Data( $staff_member_ID );
//caradump($userdata);
$user_resources = new Staff_Area\Display\Member_Resource_Status( $staff_member_ID );
?>
<h3>This is the Staff Record for: <?php echo $userdata->get_userdata()['full_name']; ?></h3>
<hr>
<h3>Staff Resources</h3>
<?php $user_resources->completed_resources_table(); ?>

<?php $user_resources->not_completed_resources_table(); ?>
