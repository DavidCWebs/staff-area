<?php
/**
 * Template for staff member details
 */
$user = new Staff_Area\Members\User_Data( $staff_member_ID );
$userdata = $user->get_userdata();
?>
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

<h3>Completed Resources</h3>
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
