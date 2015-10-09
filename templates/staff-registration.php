<?php while (have_posts()) : the_post(); ?>
  <?php
  get_template_part('templates/page', 'header');

  include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/registration.php' );

  get_template_part('templates/page', 'footer');

endwhile; ?>
