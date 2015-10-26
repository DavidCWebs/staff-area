<?php
/**
 * Resource teaser in table row format
 * @var [type]
 */
?>
<tr style="width:100%" class="<?php echo $slug_name . ' ' . $term_list . ' ' . $marked_class; ?> <?php echo 'management-resource' === $args['post_type'] ? "management-" : ''; ?>resource">
  <td>
    <a href="<?php the_permalink(); ?>">
      <?php the_title(); ?>
    </a>
  </td>
  <td>
    <?php echo wp_trim_words( get_the_excerpt(), $num_words = 10, $more = '…' ); ?>
  </td>
  <td>
    <?php echo true === $required ? "Yes" : "-"; ?>
  </td>
  <td><?= $marked; ?></td>
  <td>
    <?php echo !empty( $term_list ) ? "<p>" . get_the_term_list( $resource_ID, $taxonomy, '', ', ' ) . "</p>" : null; ?>
  </td>
</tr>
