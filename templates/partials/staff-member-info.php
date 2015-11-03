<?php
/**
 * Display an information table relating to the staff member
 */
?>
<div>
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
        <td>
          <span class="hidden-xs">
            <?= $user_resources->get_personal_data()['display_phone']; ?>
          </span>
          <span class="visible-xs hidden-sm hidden-md hidden-lg">
          <a href="tel:<?= $user_resources->get_personal_data()['dial_num'];?>" class="btn-primary btn btn-lg"><i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;Click to call <?php echo $user_resources->get_personal_data()['first_name']; ?></a>
          </span>
        </td>
      </tr>
      <tr>
        <td>Business Unit:</td>
        <td><?php echo $user_resources->get_personal_data()['business_unit']; ?></td>
      </tr>
    </tbody>
  </table>
</div>
