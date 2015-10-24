<?php
namespace Staff_Area\Resources;

/**
 *
 */
class Data {

  public static function is_compulsory( $resource_ID ) {

    $compulsory = "1" == get_post_meta( $resource_ID, 'compulsory_status', true ) ? true : false;

    return $compulsory;

  }

}
