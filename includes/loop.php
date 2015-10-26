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
class Loop {

  /**
  * Arguments to be passed to WP_Query()
  * @since    1.0.0
  * @var array
  */
  protected $args;

  /**
  * CSS class for the container div
  * @var string
  */
  protected $div_class;

  protected $current_user_ID;

  /**
   * Section Title
   * @var string
   */
  protected $section_title;
  /**
  * Set up the default arguments for WP_Query.
  *
  * Pass in an array to override or extend the default arguments.
  *
  * @since    1.0.0
  * @param array $override Array of WP_Query arguments
  */
  public function __construct( $override = [], $current_user_ID, $meta_query = '' ) {

    $this->current_user_ID  = $current_user_ID;
    $this->div_class        = "resources";
    $this->section_title    = "Staff Resources";
    $this->set_query_arguments( $override, $meta_query );

  }

  private function set_query_arguments ( $override, $meta_query ) {

    $meta = [];

    $args = array_merge( array (
      'post_type'              => 'staff-resource',
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => '-1',
      'order'                  => 'ASC',
      'orderby'                => 'menu_order',
      ),
      $override
    );

    if ( 'compulsory' === $meta_query ) {

      $this->section_title = "Compulsory Staff Resources";

      $meta = array(
        'meta_query' => array(
          array(
            'key'        => 'compulsory_status',
            'value'      => '1',
            'compare'    => '=',
            'type'       => 'CHAR',
          ),
        ),
      );

    }

    $this->args = array_merge( $args, $meta );

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
    // -------------------------------------------------------------------------
    $args = empty ( $args ) ?  $this->args : array_merge( $args, $this->args );

    $staff_resource_query = new \WP_Query( $args );

    if ( $staff_resource_query->have_posts() ) {

      echo"<h3>$this->section_title</h3>";

      // Include filter markup if filter is specified
      // -----------------------------------------------------------------------
      if ( true === $filter ) {

        $this->filter( $args['post_type'] );

      }

      echo "<div id='{$this->div_class}-table-container'>";
      //echo "<div id='{$this->div_class}'>";
      echo "<table id='{$this->div_class}-table' style='width:100%; table-layout: fixed;' class='table'>";
      echo "<thead><tr><th>Title</th><th>Description</th><th>Compulsory?</th><th>Status</th><th>Categories</th></thead><tbody>";

      while ( $staff_resource_query->have_posts() ) {

        $staff_resource_query->the_post();

        $resource_ID  = get_the_ID();
        $taxonomy     = 'staff-resource' === $args['post_type'] ? 'resource-category' : 'management-resource-category' ;
        $term_list    = implode( ' ', wp_get_post_terms( $resource_ID, $taxonomy, array( "fields" => "slugs" ) ) );
        $slug_name    = get_post_field( 'post_name', $resource_ID );
        $required     = ! empty( get_post_meta( $resource_ID, 'compulsory_status', true ) ) ? true: false ;
        $marked_status= \Staff_Area\User_Input\Confirm::is_marked_read( $this->current_user_ID, $resource_ID );
        $marked       = false == $marked_status ? "Not Read" : "Read";
        $marked_class = false == $marked_status ? "not-read" : "read";

        // The HTML for each teaser
        // ---------------------------------------------------------------------
        //include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-teaser.php' );
        include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-teaser-row.php' );

      }

      //echo "</div>";
      echo "</tbody></table></div>";

    } else {

      //echo "There are no posts";

    }

    wp_reset_postdata();

  }

  public static function get_post_IDs( $type = 'staff-resource', $compulsory = false ) {

    if ( true == $compulsory ) {
      $args = array(
        'post_type'       => $type,
        'post_status'     => 'publish',
        'fields'          => 'ids',
        'posts_per_page'  =>  -1,
        'meta_query'      => array(
          array(
            'key'        => 'compulsory_status',
            'value'      => '1',
            'compare'    => '=',
            'type'       => 'CHAR',
          ),
        ),
      );
    } else {

      $args = array(
        'post_type'       => $type,
        'post_status'     => 'publish',
        'fields'          => 'ids',
        'posts_per_page'  =>  -1
      );

    }


  $result_query = new \WP_Query( $args );
  $ID_array = $result_query->posts;
  wp_reset_postdata();

  return $ID_array;

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

      echo "<h3>Staff Resources: $name</h3>";

      $this->resource_loop( $tax_query, false );

    }

  }

  /**
  * Build a filter
  *
  * Markup needs to be slightly different for the different post types, so that
  * more than one filter can be displayed on a page.
  *
  * @param  string $post_type [description]
  * @return string            [description]
  */
  public function filter( $post_type ) {

    $filter_tax = '';

    if ( 'staff-resource' === $post_type ) {

      $filter_tax = 'resource-category';
      $filter_ID  = 'select-resource-category';

    }

    if ( 'management-resource' === $post_type ) {

      $filter_tax = 'management-resource-category';
      $filter_ID  = 'select-management-resource-category';

    }

    include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-filter.php' );

  }

}
