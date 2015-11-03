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

  protected $table_ID_base;

  protected $data_ID;

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
  * @param array $override Array of WP_Query arguments - overrides defaults
  * @param string $meta_query 'compulsory' | 'not-compulsory'
  */
  public function __construct( $override, $current_user_ID, $meta_query = '' ) {

    $this->current_user_ID  = $current_user_ID;
    $this->div_class        = "resources";
    $this->section_title    = "Staff Resources";
    $this->meta_query       = $meta_query;
    $this->set_query_arguments( $override, $meta_query );
    //$this->set_table_base();

  }

  public function set_table_base(){

    $this->table_ID_base = $this->args['post_type'];

  }

  /**
   * Set the query arguments for WP_Query
   *
   * An array of default arguments is set in this method.
   * These can be overridden by passing in an override array when instantiating the object:
   *
   * For example, to return posts ordered by date in descending order:
   * - `$resources = new Staff_Area\Includes\Loop( ['orderby' => 'date', 'order' => 'ASC'], $current_user_ID );`
   *
   * The query can be further customised by passing either 'compulsory' or 'not-compulsory'
   * as arguments. This will return either staff resources that have
   * "1" == compulsory_status as a postmeta field (compulsory resources), or those
   * that haven't (non-compulsory resources).
   *
   * For example, this will return compulsory staff resources:
   * - `$compulsory_resources = new Staff_Area\Includes\Loop( '', $current_user_ID, 'compulsory' );`
   *
   * @param array|null $override  An array of WP_Query arguments to override/extend defaults
   * @param string $meta_query    'compulsory', 'not-compulsory' null
   */
  protected function set_query_arguments ( $override, $meta_query = '' ) {

    // If no $override array has been passed in, set it to an empty array
    $override = empty( $override ) ? [] : $override;

    // The default arguments, merged with the $override array
    $args = array_merge( array (
      'post_type'       => 'staff-resource',
      'post_status'     => array( 'publish' ),
      'posts_per_page'  => '-1',
      'order'           => 'ASC',
      'orderby'         => 'menu_order',
      ),
      $override
    );

    switch ( $meta_query ) {

      // Return only compulsory staff resources
      case 'compulsory':

        $this->section_title  = "Compulsory Staff Resources";
        $this->table_ID_base  = "compulsory";
        $this->data_ID  = $this->table_ID_base . '-' . $args['post_type'] . '-table';

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
        break;

      // Return only staff resources that are NOT marked compulsory
      case 'not-compulsory':

        $this->section_title  = "Not Compulsory Staff Resources";
        $this->table_ID_base  = "not-compulsory";
        $this->data_ID  = $this->table_ID_base . '-' . $args['post_type'] . '-table';

        $meta = array(
          'meta_query' => array(
            array(
              'key'        => 'compulsory_status',
              'value'      => '0',
              'compare'    => '='//'NOT EXISTS',
            ),
          ),
        );
        break;

      default:
        $meta = [];
        $this->data_ID  = $args['post_type'] . '-table';
        break;

    }

    $this->args     = array_merge( $args, $meta );

    //$this->data_ID  = $this->table_ID_base . '-' . $this->args['post_type'] . '-table';

  }

  /**
  * Build a custom staff resource loop
  *
  * @since   1.0.0
  * @uses    WP_Query()
  * @return  string HTML staff resource teasers
  */
  public function resource_loop( $args = null, $filter = false, $name = '' ) {

    // Allow arguments to be added to this method directly. If none passed, use defaults
    // -------------------------------------------------------------------------
    $args = empty ( $args ) ?  $this->args : array_merge( $args, $this->args );

    $staff_resource_query = new \WP_Query( $args );

    if ( $staff_resource_query->have_posts() ) {

      $term = '';

      if ( ! empty( $name ) ) {

        $term = ': ' . $name;
        // Slugify the term name to use it as a CSS class/ID
        $name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name ) ) ) . '-';

      }

      printf ("<h3>%s%s</h3>", $this->section_title, $term );

      /**
      * Use output buffering to capture the output of the loop.
      * In this way, we can determine the terms attached to each post returned.
      * This data will be used to build a filter BEFORE the loop is output.
      */
      ob_start();

      ?>
      <div id="<?= $this->data_ID; ?>-<?= $name; ?>container">
        <table id="<?= $this->data_ID . $name ; ?>" style="width:100%; table-layout: fixed;" class="table-responsive table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <?php echo empty( $this->meta_query ) ? "<th style='text-align:center'>Compulsory?</th>" : null ; ?>
              <th style='text-align:center'>Status</th>
              <th>Categories</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $terms_array = [];

            while ( $staff_resource_query->have_posts() ) {

              $staff_resource_query->the_post();

              $resource_ID    = get_the_ID();
              $taxonomy       = 'staff-resource' === $args['post_type'] ? 'resource-category' : 'management-resource-category' ;
              $terms          = wp_get_post_terms( $resource_ID, $taxonomy );
              $term_list      = implode( ' ', wp_get_post_terms( $resource_ID, $taxonomy, array( "fields" => "slugs" ) ) );
              $slug_name      = get_post_field( 'post_name', $resource_ID );
              $required       = ! empty( get_post_meta( $resource_ID, 'compulsory_status', true ) ) ? true: false ;
              $marked_status  = \Staff_Area\User_Input\Confirm::is_marked_read( $this->current_user_ID, $resource_ID );
              if ( false == $marked_status ) {

                $marked = "Not Completed&nbsp;&nbsp;<span class='glyphicon glyphicon-exclamation-sign'></span>";

              } else {

                $marked = "Completed";


              }
              $marked_class   = false == $marked_status ? "not-read" : "read";

              // Make an array of all terms in this loop - in order to build the dropdown menu in the filter
              foreach( $terms as $term ) {

                // If we don't already have it, add it!
                if( ! in_array( $term, $terms_array ) ) {

                  $terms_array [] = $term;

                }

              }

              include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/resource-teaser-row.php' );

            }

            echo "</tbody></table></div>";

          } else {

            //echo "There are no posts";

          }

        wp_reset_postdata();

        $table = ob_get_clean();

        // Include filter markup if filter is specified
        if ( true === $filter ) {

          $this->filter( $args['post_type'], $terms_array );

        }

        echo $table;

  }

  /**
   * Get post IDs for staff resources
   *
   * @param  string $type         Custom Post Type for the query
   * @param  boolean $compulsory  true denotes resources with "1" == 'compulsory_status' in the postmeta table
   * @return array                Array of custom post type IDs
   */
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
  * @param  boolean $filter true tells the resource_loop() mehtod to output a filter
  * @return  string HTML staff resource teasers
  */
  public function staff_resources_by_term( $filter = false) {

    // Get all the term objects
    // -------------------------------------------------------------------------
    $terms = get_terms( 'resource-category' );

    foreach( $terms as $term )  {

      $name = $term->name;

      $tax_query = array( 'tax_query' => array(
        array(
          'taxonomy' => 'resource-category',
          'field'    => 'slug',
          'terms'    => $term->slug,
          ),
        )
      );

      add_action( 'cw_staff_area_resources_after_section_title', function( $name ) {

        return $name;
      });

      $this->resource_loop( $tax_query, $filter, $name );

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
  public function filter( $post_type, $terms_array ) {

    $filter_tax = '';

    if ( 'staff-resource' === $post_type ) {

      $filter_tax = 'resource-category';
      $filter_ID  = 'select-resource-category';
      $search_ID  = $this->table_ID_base . '-' . $this->div_class . '-search';
      $data_ID    = $this->table_ID_base . '-' . $this->div_class . 'resource';

    }

    if ( 'management-resource' === $post_type ) {

      $filter_tax = 'management-resource-category';
      $filter_ID  = 'select-management-resource-category';
      $search_ID  = $this->table_ID_base . '-' . 'management-resource-search';
      $data_ID    = $this->table_ID_base . '-' . $this->div_class . 'management-resource';

    }

    include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/partials/resource-filter-pills.php' );

  }

}
