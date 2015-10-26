<?php while (have_posts()) : the_post(); ?>
  <?php
  get_template_part('templates/page', 'header');
  $access_list = ['staff_manager', 'staff_member'];
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

  if ( 'no_access' == $access ) {

    return;

  }

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/welcome.php' );

  // The view for Staff Supervisors, Staff Managers, Site Admins, Site Editors
  // ---------------------------------------------------------------------------
  if ( 'full_access' == $access || 'supervisor_access' == $access || 'manager_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/management-only.php' );

  }

  // The view for ordinary staff members
  // ---------------------------------------------------------------------------
  if ( 'staff_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/staff-only.php' );

  }

  $compulsory_resources = new Staff_Area\Includes\Loop( '', $current_user_ID, 'compulsory' );
  $compulsory_resources->resource_loop( null, true );

  echo "<hr>";

  $all_resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'], $current_user_ID, 'not-compulsory' );
  $all_resources->resource_loop( null, true );

endwhile; ?>
