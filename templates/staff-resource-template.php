<?php
/**
 * Staff Resource template
 * This is used for 'staff-resource' and 'management-resource' custom post types.
 */
while (have_posts()) : the_post();

$post_ID = get_the_ID();

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

  return;

}

$marked = Staff_Area\User_Input\Confirm::is_marked_read( $current_user_ID, $post_ID );

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/header.php' );

the_content();

if( "1" === get_post_meta( get_the_ID(), 'include_status', 'text', TRUE ) ) {

  if ( false === $marked ){

    $confirm_status = new Staff_Area\User_Input\Confirm( $post_ID, $current_user_ID );

    echo $confirm_status->form( $current_user_ID );

  }

}

$marked_resources = get_user_meta( $current_user_ID, 'resources_completed', true );

if ( !empty( $marked_resources ) ) {

  echo "<ul>";
  foreach ( $marked_resources as $marked_resource_ID => $data ) {

    $title  = get_the_title( $data['post_ID'] );
    $time   = date('l jS \of F Y h:i:s A', $data['time'] );
    echo "<li>Resource: $title Marked complete on $time</li>";

  }
  echo "</ul>";

}

endwhile; ?>
