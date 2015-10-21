<?php
/**
 * Resource teaser in table row format
 * @var [type]
 */
$resource_ID  = get_the_ID();
$taxonomy     = 'staff-resource' === $args['post_type'] ? 'resource-category' : 'management-resource-category' ;
$term_list    = implode( ' ', wp_get_post_terms( $resource_ID, $taxonomy, array( "fields" => "slugs" ) ) );
$slug_name    = get_post_field( 'post_name', $resource_ID );
?>
<tr style="width:100%" class="<?php echo $slug_name . ' ' . $term_list; ?> <?php echo 'management-resource' === $args['post_type'] ? "management-" : ''; ?>resource">

    <td>
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </td>
    <td>
      <?php echo wp_trim_words( get_the_excerpt(), $num_words = 10, $more = '…' ); ?>
    </td>
    <td> ! </td>
    <td>
      <?php echo !empty( $term_list ) ? "<p>Categories: " . get_the_term_list( $resource_ID, $taxonomy, '', ', ' ) . "</p>" : null; ?>
    </td>
</tr>
