<?php
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-staff-area-management-resources.php' );
the_content();
echo "<h2>Management Resources</h2>";
$management_resources = new Carawebs\Staff\Management_Resources( ['orderby' => 'date', 'order' => 'DESC'] );
$management_resources->resource_loop( null, true );
echo "<hr><h2>Staff Resources</h2>";
$all_resources = new Carawebs\Staff\Loop( ['orderby' => 'date', 'order' => 'ASC'] );
$all_resources->resource_loop( null, true );
?>
