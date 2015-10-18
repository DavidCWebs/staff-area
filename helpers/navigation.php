<?php
namespace Staff_Area\Helpers;

class Navigation {

  public static function breadcrumbs( $post_ID ) {

    $post_type  = get_post_type( $post_ID );
    $taxonomy   = 'staff-resource' === $post_type ? 'resource-category' : 'management-resource-category';

    ob_start();

    ?>
    <ul class="breadcrumb">
      <li><a href="<?php echo esc_url( home_url('/staff') ) ;?>">Staff Home</a></li>
      <?php self::term_list( $post_ID, $taxonomy ); ?>
      <li class="active"><?php the_title(); ?></li>
    </ul>
    <?php
    return ob_get_clean();

  }

  public static function get_terms( $taxonomy ) {

    $terms = get_terms( $taxonomy );

  }

  private static function term_list( $post_ID, $taxonomy ) {

    $terms = wp_get_post_terms( $post_ID, $taxonomy );

    if ( !empty( $terms ) ) {

      foreach( $terms as $term ) {

        $link = '/' . $taxonomy . '/' . $term->slug;

        echo "<li><a href=" . esc_url( home_url( $link ) ) . ">" . $term->name . "</a></li>";

      }

    }

  }

}
