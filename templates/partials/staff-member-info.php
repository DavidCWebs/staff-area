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
        <a href="mailto:<?php echo $user_resources->get_personal_data()['email']; ?>">
          <?php echo $user_resources->get_personal_data()['email']; ?>
        </a>
      </td>
    </tr>
    <tr>
      <td>Phone:</td>
      <td><button class="btn btn-lg">Click to call <?php echo $user_resources->get_personal_data()['first_name']; ?></button></td>
    </tr>
    <tr>
      <td>Business Unit:</td>
      <td><?php echo $user_resources->get_personal_data()['business_unit']; ?></td>
    </tr>
  </tbody>
</table>
