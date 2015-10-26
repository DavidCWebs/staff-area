<?php
/**
 * New user registration
 *
 * HTML markup for front-end user creation.
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
  <div class="col-md-5">
    <?php the_content(); ?>
    <p>Set up new staff members using this form.</p>
    <p>When you create a new staff member, their login details and instructions will be automatically emailed to them.</p>
    <div id="result-message" class="well"></div>
  </div>
  <div class="col-md-6 col-md-offset-1">
    <?php
    $register = new Staff_Area\Members\Register( $current_user_ID );
    $register->userform_process_facade ();
    ?>
  </div>
</div>
