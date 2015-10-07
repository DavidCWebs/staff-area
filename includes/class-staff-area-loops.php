<?php
namespace Carawebs\Staff;
/**
 * The file that defines the Loops class
 *
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 */

/**
 * The loops class - used to define custom loops.
 *
 * Passing an array of arguments when instantiating the class object allows the
 * default $args to be overridden.
 *
 * @since      1.0.0
 * @package    Staff_Area
 * @subpackage Staff_Area/includes
 * @author     David Egan <david@carawebs.com>
 */
class Loop {

  /**
   * Arguments to be passed to WP_Query()
   * @since    1.0.0
   * @var array
   */
  private $args;

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
      'post_type'              => array( 'staff_resource' ),
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => '-1',
      'order'                  => 'ASC',
      'orderby'                => 'menu_order',
      ),
      $override
    );

  }

  /**
   * Build a custom staff resource loop
   *
   * @since    1.0.0
   * @return HTML Output of a custom loop
   */
   public function staff_resource_loop() {

     $staff_resource_query = new \WP_Query( $this->args );

     if ( $staff_resource_query->have_posts() ) {

       while ( $staff_resource_query->have_posts() ) {
         
         $staff_resource_query->the_post();

         // The HTML for each teaser
         // -----------------------------------------------------------------------
         include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-teaser.php' );

       }

     } else {

       //echo "There are no posts";

     }

     wp_reset_postdata();

   }

}
