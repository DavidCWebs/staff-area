<?php
/**
 * The page template for 'staff_supervisor' and 'admin' user roles viewing
 * the 'staff' page.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

the_content();
$management_resources = new Staff_Area\Includes\Management_Resources( ['orderby' => 'date', 'order' => 'DESC'], $current_user_ID );
$management_resources->resource_loop( null, true );
$all_resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'], $current_user_ID );
$all_resources->resource_loop( null, true );
?>
