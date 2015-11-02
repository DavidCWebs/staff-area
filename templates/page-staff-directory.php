<?php while (have_posts()) : the_post();

  $access_list = [ 'staff_manager'];
  get_template_part('templates/page', 'header');
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

  if ( 'no_access' == $access ) {

    return;

  }

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/welcome.php' );

  the_content();

  $staff        = new Staff_Area\Members\Staff_Dashboard( 'staff_member' );
  $supervisors  = new Staff_Area\Members\Staff_Dashboard( 'staff_supervisor' );

  echo $staff->render_table();
  echo "<hr>";
  echo $supervisors->render_table();
  echo "<hr>";

endwhile; ?>
