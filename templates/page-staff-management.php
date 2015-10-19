<?php while (have_posts()) : the_post();

  $allowed = [ 'staff-manager', 'editor', 'administrator'];
  get_template_part('templates/page', 'header');
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

  if ( 'no_access' == $access ) {

    return;

  }

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/welcome.php' );

  $staff = new Staff_Area\Members\Staff_Dashboard();

  echo $staff->render_table();

  //caradump( $staff, 'Staff' );

  the_content();

endwhile; ?>
