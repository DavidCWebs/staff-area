<?php
namespace Staff_Area\Helpers;

class Post_Data {

/**
  * Return an array of post IDs for the given CPT
  *
  * Builds an efficient query by only returning an array of post IDs.
  * By default, returns an array of IDs for all published posts of the custom
  * post type specified by the first argument ($cpt). The second argument accepts
  * an array that allows the default WP_Query() arguments used in this method
  * to be overridden.
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

  public static function resource_info ( $resource_ID ) {

    $resource_info = [
      'compulsory'                => \Staff_Area\Resources\Data::is_compulsory( $resource_ID ),
      'associated_business_units' => get_post_meta( $resource_ID, 'associated_business_units', true )
    ];

  }

  /**
   * Build an array of resource IDs that are associated with a given business unit
   *
   * @param  int|string $unit_ID Business Unit CPT post ID
   * @return array          Post IDs of associated staff-resource CPTs
   */
  public static function resources_linked_to_business_unit( $unit_ID ) {

    $staff_resource_IDs   = self::custom_post_type_IDs( 'staff-resource' );
    $associated_resources = [];

    foreach( $staff_resource_IDs as $resource_ID ) {

      // associated business units for THIS resource
      $associated_units = get_post_meta( $resource_ID, 'associated_business_units', true );

      // If the $unit_ID is in this array, push the resource ID to $associated_resources array
      if ( is_array( $associated_units ) && in_array( $unit_ID, $associated_units ) ) {

        $associated_resources [] = $resource_ID;

      }

    }

    return $associated_resources;

  }

}
