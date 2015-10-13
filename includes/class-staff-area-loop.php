<?php
namespace Carawebs\Staff;
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
class Loop {

  /**
   * Arguments to be passed to WP_Query()
   * @since    1.0.0
   * @var array
   */
  protected $args;

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
   * @since   1.0.0
   * @uses    WP_Query()
   * @return  string HTML staff resource teasers
   */
   public function resource_loop( $args = null, $filter = false ) {

     // Allow arguments to be added to this method directly. If none passed, use defaults
     // ------------------------------------------------------------------------
     $args = empty ( $args ) ?  $this->args : array_merge( $args, $this->args );

     $staff_resource_query = new \WP_Query( $args );

     if ( $staff_resource_query->have_posts() ) {

       if ( true === $filter ) {

         include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-filter.php' );

       }

       echo "<div id='resources'>";

       while ( $staff_resource_query->have_posts() ) {

         $staff_resource_query->the_post();

         // The HTML for each teaser
         // --------------------------------------------------------------------
         include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-teaser.php' );

       }

       echo "</div>";

     } else {

       //echo "There are no posts";

     }

     wp_reset_postdata();

   }

   /**
   * Loop through all terms in the given taxonomy
   *
   * Display staff resource teasers grouped by resource category.
   * @uses    get_terms()
   * @since   1.0.0
   * @return  string HTML staff resource teasers
   */
   public function staff_resources_by_term() {

     // Get all the term objects
     // ------------------------------------------------------------------------
     $terms = get_terms( 'resource_category' );

     foreach( $terms as $term )  {

      $name = $term->name;

       $tax_query = array( 'tax_query' => array(
         array(
           'taxonomy' => 'resource_category',
           'field'    => 'slug',
           'terms'    => $term->slug,
         ),
       )
     );

     echo "<h2>Staff Resources: $name</h2>";

     $this->resource_loop( $tax_query, false );

   }

 }

}
