<?php
namespace Staff_Area\Display;

class Excerpt {

  function carawebs_custom_excerpt() {

  $str = get_the_excerpt();

  $shortexcerpt = wp_trim_words( get_the_excerpt(), $num_words = 10, $more = '… ' );

  $trimmed = rtrim ( $str, ".,:;!?" );

  // Echo to the page and add a Read More link
  ?><div class="post_content post_excerpt">

  	<p><?php echo $trimmed; ?>&hellip;<a class="readmore" href="<?php echo get_permalink();?>">&nbsp;Read More&nbsp;&raquo;</a></p>

  </div>
  <?php

  }

}
