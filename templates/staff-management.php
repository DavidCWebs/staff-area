<?php
the_content();
$management_resources = new Staff_Area\Includes\Management_Resources( ['orderby' => 'date', 'order' => 'DESC'] );
$management_resources->resource_loop( null, true );
$all_resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'] );
$all_resources->resource_loop( null, true );
?>
