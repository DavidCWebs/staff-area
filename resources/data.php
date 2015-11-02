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

  public static function get_compulsory_resources () {

    return \Staff_Area\Includes\Loop::get_post_IDs( 'staff-resource', true );;

  }

  public static function get_all_resources() {

    return \Staff_Area\Includes\Loop::get_post_IDs( 'staff-resource', false );

  }

  public static function associated_business_units( $resource_ID ) {

    return get_post_meta( $resource_ID, 'associated_business_units', true );

  }

}
