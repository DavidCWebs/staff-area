<div class="row">
<article <?php post_class(); ?>>
  <div class="col-md-8">
    <header><?php caradump($marked_status); ?>-XXXX
      <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    </header>
    <div class="entry-summary">
      <?php the_excerpt(); ?>
      <p><a href="<?php the_permalink(); ?>">Go to the full article&nbsp;&raquo;</a></p>
      <?php get_template_part('templates/entry-meta'); ?>
    </div>
  </div>
  <hr>
</article>
</div>
