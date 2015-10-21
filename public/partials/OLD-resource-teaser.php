<?php
$resource_ID  = get_the_ID();
$taxonomy     = 'staff-resource' === $args['post_type'] ? 'resource-category' : 'management-resource-category' ;
$term_list    = implode( ' ', wp_get_post_terms( $resource_ID, $taxonomy, array( "fields" => "slugs" ) ) );
$slug_name    = get_post_field( 'post_name', $resource_ID );
?>
<div class="<?php echo $slug_name . ' ' . $term_list; ?> <?php echo 'management-resource' === $args['post_type'] ? "management-" : ''; ?>resource well">
  <h3>
    <a href="<?php the_permalink(); ?>">
      <?php the_title(); ?>
    </a>
  </h3>
  <?php the_excerpt(); ?>
  <a href="<?php the_permalink(); ?>">Read More &raquo;</a>
  <?php echo !empty( $term_list ) ? "<p>Categories: " . get_the_term_list( $resource_ID, $taxonomy, '', ', ' ) . "</p>" : null; ?>
</div>
