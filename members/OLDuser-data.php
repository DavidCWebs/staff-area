<?php
/**
 * File holding the User_Data() class
 *
 */
namespace Staff_Area\Members;

class User_Data {

  private $user_ID;

  private $userdata;

  public function __construct( $user_ID ) {

    $this->user_ID = $user_ID;
    $this->set_userdata();

  }

  /**
   * Build an array of user data
   *
   *
   */
  private function set_userdata() {

    $user_object = get_userdata( $this->user_ID );

    //$resources_completed = $this->completed_workbooks( $resources_meta );


    $this->userdata = [
      'first_name'    => $user_object->first_name,
      'second_name'   => $user_object->last_name,
      'full_name'     => $user_object->first_name . ' ' . $user_object->last_name,
      'email'         => $user_object->user_email,
      'registered'    => $user_object->user_registered,
      'completed'     => self::completed_resources( $this->user_ID, 'fields' ),
      'not_completed' => self::not_completed_resources( $this->user_ID )
    ];

  }

  public function get_userdata () {

    return $this->userdata;

  }

  /**
   * [completed_workbooks description]
   * @param  [type] $allmeta [description]
   * @return [type]          [description]
   */
  public static function completed_resources( $user_ID, $return = 'fields' ) {

    $resources_meta = get_user_meta( $user_ID, 'resources_completed', true );

    if ( empty ( $resources_meta ) ) {

      return false;

    }

    // Build array of data for completed resources
    // -------------------------------------------------------------------------
    if ( !empty ( $resources_meta ) ) {

      //$completed_resources = unserialize( $resources_meta[0] );
      $completed = [];
      foreach ( $resources_meta as $resource ) {

        if( 'fields' === $return ) {

          $completed[] = self::build_completed_resource_array( $resource );

        } elseif( 'IDs' === $return ) {

          $completed[] = (int) $resource['post_ID'];

        }

      }

    }

    return $completed;

  }

  public static function build_completed_resource_array ( $resource ) {

    $date = date('j-m-Y, G:i', $resource['time'] );

    return [
      'post_ID'         => (int) $resource['post_ID'],
      'completion_date' => esc_html( $date ),
      'title'           => sanitize_text_field( get_the_title( $resource['post_ID'] ) ),
      'permalink'       => esc_url( get_the_permalink( $resource['post_ID'] ) )
    ];

  }

  public static function build_resource_array_from_ID ( $resource_ID ) {

    return [
      'post_ID'         => (int) $resource_ID,
      'title'           => sanitize_text_field( get_the_title( $resource_ID ) ),
      'permalink'       => esc_url( get_the_permalink( $resource_ID ) )
    ];

  }

  /**
   * Return an array of post IDs for staff resources that have not been completed
   *
   * Refers to staff resources that have not been completed by this user.
   *
   * @param  int|string $user_ID  The user ID
   * @return array                Post IDs of not-complete staff resources for this user
   */
  public static function not_completed_resources( $user_ID, $return_format = 'IDs' ) {

    $all_resources            = \Staff_Area\Includes\Loop::get_post_IDs( 'staff-resource', false );
    $completed_resources      = self::completed_resources( $user_ID, 'IDs' );
    $not_completed_resources  = array_diff( $all_resources, $completed_resources );
    $not_completed_fields     = [];

    if ( 'IDs' === $return_format ) {

      return $not_completed_resources;

    } elseif ( 'fields' === $return_format ) {

      foreach( $not_completed_resources as $resource_ID ) {

        $not_completed_fields = self::build_resource_array_from_ID ( $resource_ID );

      }

      //$this->not_comp = $not_completed_fields;
      return $not_completed_fields;

    }

  }

  /**
   * [get_completed_resources description]
   * @return [type] [description]
   */
  public function get_completed_resources( /*$return_format = 'rows'*/ ) {

    $resources_array = self::completed_resources( $this->user_ID, 'IDs' );

    return self::completed_resources( $this->user_ID, 'fields' );//$resources_array;

  }

  /**
   * [resources_table description]
   *
   * @param  string $completion_status [description]
   * @param  string $compulsory        [description]
   * @return [type]                    [description]
   */
  public function resources_table ( $completion_status = 'not_complete', $compulsory = '' ) {

    caradump( $this->userdata, '$this->userdata');

    $resource_array = 'completed' === $completion_status ? $this->userdata['completed'] : self::not_completed_resources( $this->user_ID, 'fields');//$this->userdata['not_completed'];

    if ( is_array( $this->userdata['completed'] ) ){

      ?>
      <p>
        <?php echo $this->userdata['first_name']; ?> has marked the following staff resources as complete:
      </p>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Date Completed</th>
            <th>Compulsory?</th>
          </tr>
        </thead>
        <tbody>
          <?php

          foreach ( $resource_array as $resource ) {

            $compulsory = true === \Staff_Area\Resources\Data::is_compulsory( $resource['post_ID'] ) ? "Yes" : "No";

            echo "<tr>";
            echo "<td><a href='{$resource['permalink']}'>{$resource['title']}</a></td>";
            echo "<td>{$resource['completion_date']}</td>";
            echo "<td>$compulsory</td>";
            echo "</tr>";

          }

          ?>
        </tbody>
      </table>
      <?php

    } else {

    ?>
    <p>
      <?php echo $this->userdata['first_name']; ?> has not marked any staff resources as complete.
    </p>
    <?php

    }

  }

}
