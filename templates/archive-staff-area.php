<?php
$post_ID = get_the_ID();
$access_list = ['staff_manager', 'staff_member', 'staff_supervisor'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}
get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'staff-area'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php echo term_description(); ?>
  <?php
  $marked_status  = \Staff_Area\User_Input\Confirm::is_marked_read( $current_user_ID, get_the_ID() );
  include( plugin_dir_path( __FILE__ ) . 'partials/teaser-content.php')
  ?>
<?php endwhile; ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
