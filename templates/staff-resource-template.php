<?php
/**
 * Staff Resource template
 * This is used for 'staff-resource' and 'management-resource' custom post types.
 */
while (have_posts()) : the_post();

//get_template_part('templates/page', 'header');

the_content();

if( "1" === get_post_meta( get_the_ID(), 'include_status', 'text', TRUE ) ) {

  echo "PRINT FORM";

}

//caradump( $dump, 'postmeta');

$input = new Staff_Area\Includes\Input( 'checkbox' );

endwhile; ?>
