<?php
?>
<h3>
  <a href="<?php the_permalink(); ?>">
    <?php the_title(); ?>
  </a>
</h3>
<?php the_excerpt(); ?>
<a href="<?php the_permalink(); ?>">Read More &raquo;</a>
<p>
  <?php echo "Resource Categories: ". get_the_term_list( get_the_ID(), 'resource_category', '', ', ' ); ?>
</p>
