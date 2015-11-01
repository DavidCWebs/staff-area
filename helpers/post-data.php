<?php
namespace Staff_Area\Helpers;

class Post_Data {

/**
  * Return an array of post IDs for the given CPT
  *
  * Builds an efficient query by only returning an array of post IDs.
  * By default, returns an array of IDs for all published posts of the custom
  * post type specified by the first argument ($cpt). The second argument allows
  * accepts an array that allows the default WP_Query() arguments to be overridden.
  *
  * @author David Egan
  * @see http://codex.wordpress.org/Class_Reference/WP_Query#Return_Fields_Parameter
  * @param  string $cpt The custom post type to fetch
  * @return array Array of post IDs
  */
  public static function custom_post_type_IDs( $cpt, $override = '' ) {

    $override = empty( $override ) ? [] : $override;

    $args = array_merge( array(
      'post_type'     => $cpt,
      'post_status'   => 'publish',
      'fields'        => 'ids',
      'posts_per_page'  => '-1',
      ), $override
    );

    $result_query = new \WP_Query( $args );

    $ID_array = $result_query->posts;

    wp_reset_postdata();

    return $ID_array;

  }

}
