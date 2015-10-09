<?php
//include_once( dirname ( dirname( __DIR__ ) ) . '/includes/class-carawebs-user-management-register.php');
//use Member\Includes\Register;
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Carawebs_User_Management
 * @subpackage Carawebs_User_Management/public/partials
 */
?>
<h4><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;Set Up New Staff Members</h4>
<div class="row">
  <div class="col-md-6">
    <p>Set up new staff members using this form.</p>
    <p>When you create a new staff member, their login details and instructions will be automatically emailed to them.</p>
    <div id="result-message" class="well"></div>
  </div>
  <div class="col-md-6">
    <?php
    $register = new Member\Includes\Register();
    $register->userform_process_facade ( 1, $user_role = 'subscriber' );
    ?>
  </div>
</div>
