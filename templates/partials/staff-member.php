<?php
/**
 * Template for staff member details
 */
include_once( dirname( __DIR__ ) . '/partials/staff-member-info.php' );
?>
<hr>
<?php $user_resources->not_completed_resources_table(); ?>
<hr>
<?php $user_resources->completed_resources_table(); ?>
