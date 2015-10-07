<?php while (have_posts()) : the_post(); ?>
  <?php
  get_template_part('templates/page', 'header');
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-staff-area-loops.php' );

  if ( 'no_access' == $access ) {

    return;

  }

  if ( 'full_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/staff-management.php' );

  }

  if ( 'staff_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/staff-member.php' );

  }

  echo "<h2>All Staff Resources</h2>";
  $all_resources = new Carawebs\Staff\Loop( ['orderby' => 'date', 'order' => 'ASC'] );
  $all_resources->staff_resource_loop();
  echo "<hr>";
  $all_resources->staff_resources_by_term();


?>
<?php endwhile; ?>
