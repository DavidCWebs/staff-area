<?php
namespace Carawebs\Staff;
include_once( plugin_dir_path( __FILE__ ) . 'class-staff-area-loop.php' );
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

  /**
   * Set up the default arguments for WP_Query.
   *
   * Pass in an array to override or extend the default arguments.
   *
   * @since    1.0.0
   * @param array $override Array of WP_Query arguments
   */
  public function __construct( $override = [] ) {

    $this->args = array_merge( array (
      'post_type'              => 'management_resource',
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => '-1',
      'order'                  => 'ASC',
      'orderby'                => 'menu_order',
      ),
      $override
    );

    $this->div_class = "management-resources";

  }

}
