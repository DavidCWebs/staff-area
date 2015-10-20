<?php
/**
 * Staff Resource template
 * This is used for 'staff-resource' and 'management-resource' custom post types.
 */
while (have_posts()) : the_post();

$post_ID = get_the_ID();
$access_list = ['staff_manager', 'staff_member', 'staff_supervisor'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/header.php' );

?>
<div class="row">
  <div class="col-md-8">
    <?php the_content(); ?>
    <hr>
    <?php Staff_Area\Display\Single_Resource::display_checkbox( $post_ID, $current_user_ID, $marked ); ?>
    <hr>
    <?php Staff_Area\Display\Single_Resource::display_marked_resources( $current_user_ID ); ?>
  </div>
</div>

<?php endwhile; ?>
