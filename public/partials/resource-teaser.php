<?php
$resource_ID  = get_the_ID();
$taxonomy     = 'staff-resource' === $args['post_type'] ? 'resource-category' : 'management-resource-category' ;
$term_list    = implode( ' ', wp_get_post_terms( $resource_ID, $taxonomy, array( "fields" => "slugs" ) ) );
$slug_name    = get_post_field( 'post_name', $resource_ID );
?>
<div class="<?php echo $slug_name . ' ' . $term_list; ?> <?php echo 'management-resource' === $args['post_type'] ? "management-" : ''; ?>resource">
  <div class="row">
    <div class="col-md-3">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </div>
    <div class="col-md-5">
      <?php echo wp_trim_words( get_the_excerpt(), $num_words = 10, $more = '…' ); ?>
      <p><a href="<?php the_permalink(); ?>">Read More &raquo;</a></p>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
      <?php echo !empty( $term_list ) ? "<p>Categories: " . get_the_term_list( $resource_ID, $taxonomy, '', ', ' ) . "</p>" : null; ?>
    </div>
  </div>
  <hr>
</div>
