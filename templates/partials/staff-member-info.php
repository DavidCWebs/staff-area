<?php
/**
 * Display an information table relating to the staff member
 */
?>
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
