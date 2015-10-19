<?php while (have_posts()) : the_post(); ?>
  <?php
  get_template_part('templates/page', 'header');
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );
  
  if ( 'no_access' == $access ) {

    return;

  }

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/welcome.php' );

  // The view for Supervisors & Admins
  // ---------------------------------------------------------------------------
  if ( 'full_access' == $access || 'supervisor_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/staff-management.php' );

  }

  // The view for ordinary staff members
  // ---------------------------------------------------------------------------
  if ( 'staff_access' == $access ) {

    include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/staff-member.php' );

  }

endwhile; ?>
