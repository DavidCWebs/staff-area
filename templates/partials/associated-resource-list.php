<?php

echo "<h3>Resources for ". get_the_title( $business_unit ) . "</h3>";
$resources = Staff_Area\Helpers\Post_Data::resources_linked_to_business_unit( $business_unit );
?>
<ul>
  <?php foreach( $resources as $resource ) : ?>
  <li><a href="<?= get_the_permalink; ?>"><?= get_the_title( $resource );?></a></li>
<?php endforeach; ?>
</ul>
