<?php
namespace Staff_Area\Helpers;

class Post_Data {

/**
  * Return an array of post IDs
  *
  * Build a more efficient query by only returning an array of post IDs.
  *
  * @author David Egan
  * @see http://codex.wordpress.org/Class_Reference/WP_Query#Return_Fields_Parameter
  * @param  string $cpt The custom post type to fetch
  * @return array Array of post IDs
  */
  public static function custom_post_type_IDs( $cpt ) {

    $args = array(
      'post_type'     => $cpt,
      'post_status'   => 'publish',
      'fields'        => 'ids',
    );

    // The Query
    $result_query = new \WP_Query( $args );

    $ID_array = $result_query->posts;

    wp_reset_postdata();

    return $ID_array;

  }

}
