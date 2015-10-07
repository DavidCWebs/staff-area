<?php
the_content();
//echo "<h2>All Staff Resources</h2>";
$all_resources = new Carawebs\Staff\Loop( ['orderby' => 'date', 'order' => 'ASC'] );
//$all_resources->staff_resource_loop();
//echo "<hr>";
$all_resources->staff_resources_by_term();
