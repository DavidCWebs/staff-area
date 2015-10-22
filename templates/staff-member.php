<?php
the_content();
//echo "<h2>All Staff Resources</h2>";
$all_resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'], $current_user_ID );
$all_resources->resource_loop( null, true );
//echo "<hr>";
//$all_resources->staff_resources_by_term();
