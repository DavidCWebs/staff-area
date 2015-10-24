<?php
/**
 * Template for staff member details
 */
$user = new Staff_Area\Members\User_Data( $staff_member_ID );
$userdata = $user->get_userdata();  // Data for this user
$completed_resources = $user->get_completed_resources();  // Staff Resources completed by this user
//caradump($userdata, '$userdata');
?>
<h3>This is the Staff Record for: <?php echo $userdata['full_name']; ?></h3>
<?php include_once( dirname( __DIR__ ) . '/partials/staff-member-info.php' ); ?>


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
      <?php

      foreach ($completed_resources as $resource ) {

        echo "<tr>";
        echo "<td><a href='{$resource['permalink']}'>{$resource['title']}</a></td>";
        echo "<td>{$resource['completion_date']}</td>";
        echo "</tr>";

      }

      ?>
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
