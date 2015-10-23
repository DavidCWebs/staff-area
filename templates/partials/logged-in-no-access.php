<?php
?>
<h1>Sorry...This Area is Off-Limits</h1>
<h3>This Content is For <?= $access_string; ?> Users</h3>
<p>You need to be a staff-member with the proper privileges to view this content.</p>
<p>Your access level is: <?= $readable_access; ?></p>
<p>For more info on <?php echo get_bloginfo(); ?>, why not visit our <a href="<?php echo esc_url(home_url('/') ) ; ?>">Home Page?</a></p>
