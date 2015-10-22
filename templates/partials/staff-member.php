<?php
/**
 * Template for staff member details
 */
$user = new Staff_Area\Members\User_Data( $staff_member_ID );
$userdata = $user->get_userdata();
?>
<p><a href="<?php echo esc_url( home_url('/staff-management') ); ?>">&laquo;&nbsp;Back to Staff Management</a></p>
<h3>This is the Staff Record for: <?php echo $userdata['full_name']; ?></h3>

<table class="table">
  <thead>
    <tr>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Email: </td>
      <td>
        <a href="mailto:<?php echo $userdata['email']; ?>">
          <?php echo $userdata['email']; ?>
        </a>
      </td>
    </tr>
    <tr>
      <td>Phone:</td>
      <td><button class="btn btn-lg">Click to call <?php echo $userdata['first_name']; ?></button></td>
    </tr>
  </tbody>
</table>
<h3>Staff Resources</h3>
<?php if ( is_array( $userdata['completed'] ) ){

  ?>
  <p>
    <?php echo $userdata['first_name']; ?> has marked the following staff resources as complete:
  </p>
  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Date Completed</th>
      </tr>
    </thead>
    <tbody>
      <?php echo $user->get_completed_resources( 'rows' ); ?>
    </tbody>
  </table>
<?php

} else {

  ?>
  <p>
    <?php echo $userdata['first_name']; ?> has not marked any staff resources as complete.
  </p>
  <?php

}
