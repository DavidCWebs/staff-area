<?php
/**
 * The page template for 'staff_supervisor' and 'admin' user roles viewing
 * the 'staff' page.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$access_list = ['staff_manager', 'staff_supervisor'];
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

if ( 'no_access' == $access ) {

	return;

}

include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/welcome.php' );

$management_resources = new Staff_Area\Includes\Management_Resources( ['orderby' => 'date', 'order' => 'DESC'], $current_user_ID );
$management_resources->resource_loop( null, true );
?>
