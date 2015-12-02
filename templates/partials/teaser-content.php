<div class="row">
<article <?php post_class(); ?>>
  <div class="col-md-8">
    <header>
      <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-summary">
      <?php the_excerpt(); ?>
      <p><a href="<?php the_permalink(); ?>">Go to the full article&nbsp;&raquo;</a></p>
    </div>
  </div>
  <hr>
</article>
</div>
