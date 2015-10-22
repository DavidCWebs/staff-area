<?php
the_content();
$all_resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'], $current_user_ID );
$all_resources->resource_loop( null, true );
//$all_resources->staff_resources_by_term();
