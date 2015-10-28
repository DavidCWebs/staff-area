<?php
namespace Staff_Area\Includes;
/**
 * The file that defines the Loops class
 *
 *
 * @link       http://carawebs.com/plugins/staff-area
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The loop class - used to define custom loops.
 *
 * Passing an array of arguments when instantiating the class object allows the
 * default $args to be overridden.
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Management_Resources extends Loop {

  protected $current_user_ID;

  /**
   * Set up the default arguments for WP_Query.
   *
   * Pass in an array to override or extend the default arguments.
   *
   * @since    1.0.0
   * @param array $override Array of WP_Query arguments
   */
  public function __construct( $override = [], $current_user_ID, $meta_query = '' ) {

    $this->current_user_ID = $current_user_ID;
    $this->set_arguments( $override, $current_user_ID, $meta_query );
    $this->div_class      = "management-resources";
    $this->section_title  = "Management Resources";

  }

  private function set_arguments( $override, $current_user_ID, $meta_query) {

    $override = array_merge([
      'post_type' => 'management-resource',
    ], $override);

    $this->set_query_arguments( $override );

  }

}
