<?php
namespace Staff_Area\Members;

/**
 *
 */
class User_Data {

  /**
   * The user's ID
   * @var int|string
   */
  private $user_ID;

  private $userdata;

  /**
   * Array of post IDs of all resources completed by this user
   * @var array
   */
  private $completed_resource_IDs;

  /**
   * Array of post IDs of all resources not completed by this user
   * @var array
   */
  private $not_completed_resource_IDs;

  /**
   * Array of completed resource data
   * @var array
   */
  private $completed_resource_data;

  function __construct( $user_ID ) {

    $this->user_ID = $user_ID;
    $this->set_completed_resource_IDs();
    $this->set_not_completed_resource_IDs();
    $this->set_completed_resource_data();
    $this->set_not_completed_resource_data();
    $this->set_userdata();

  }

  /**
   * Array of post IDs of completed resources for this user
   */
  private function set_completed_resource_IDs() {

    $resources_meta = get_user_meta( $this->user_ID, 'resources_completed', true );

    if ( empty ( $resources_meta ) ) {

      return false;

    }

    if ( !empty ( $resources_meta ) ) {

      $completed = [];

      foreach ( $resources_meta as $resource ) {

        $completed[] = (int) $resource['post_ID'];

      }

    }

    $this->completed_resource_IDs = $completed;

  }

  /**
   * Array of post IDs of not completed resources for this user
   */
  private function set_not_completed_resource_IDs() {

    $all_resources                    = \Staff_Area\Includes\Loop::get_post_IDs( 'staff-resource', false );
    $completed_resources              = $this->completed_resource_IDs;

    if ( !empty ( $completed ) ) {

      $this->not_completed_resource_IDs = array_diff( $all_resources, $completed_resources );

    } else {

      $this->not_completed_resource_IDs = $all_resources;

    }

  }

  /**
   * Multi dimensional array of completed resources for this user
   *
   * Used for display purposes
   *
   */
  private function set_completed_resource_data() {

    $resources_meta = get_user_meta( $this->user_ID, 'resources_completed', true );

    if ( empty ( $resources_meta ) ) {

      return false;

    }

    if ( !empty ( $resources_meta ) ) {

      $completed = [];

      foreach ( $resources_meta as $resource ) {

        $completed[] = $this->build_completed_resource_array( $resource );

      }

    }

    $this->completed_resource_data = $completed;

  }

  /**
   * Data for not completed resources for this user
   *
   */
  private function set_not_completed_resource_data() {

    $not_complete_IDs = $this->not_completed_resource_IDs;

    if ( empty ( $not_complete_IDs ) ) {

      return false;

    } else {

      $not_completed = [];

      foreach ( $not_complete_IDs as $ID ) {

        $not_completed[] = $this->build_not_completed_resource_array( $ID );

      }

    }

    $this->not_completed_resource_data = $not_completed;

  }


  /**
   * Build completed resource array
   *
   * The multidimensional array holds data that is useful for display purposes.
   *
   * @param  array $resource Returned completed resource array from the users usermeta table
   * @return array           Data for display purposes
   */
  private function build_completed_resource_array ( $resource ) {

    $date = date('j-m-Y, G:i', $resource['time'] );

    return [
      'post_ID'         => (int) $resource['post_ID'],
      'completion_date' => esc_html( $date ),
      'title'           => sanitize_text_field( get_the_title( $resource['post_ID'] ) ),
      'permalink'       => esc_url( get_the_permalink( $resource['post_ID'] ) )
    ];

  }

  /**
   * Build not completed resource array
   *
   * @param  int|string $ID Post ID of the resource
   * @return array          Data to display
   */
  private function build_not_completed_resource_array ( $ID ) {

    return [
      'post_ID'         => (int) $ID,
      'title'           => sanitize_text_field( get_the_title( $ID ) ),
      'permalink'       => esc_url( get_the_permalink( $ID ) )
    ];

  }

  /**
   * Build an array of user data
   *
   *
   */
  private function set_userdata() {

    $user_object = get_userdata( $this->user_ID );

    $this->userdata = [
      'first_name'    => $user_object->first_name,
      'second_name'   => $user_object->last_name,
      'full_name'     => $user_object->first_name . ' ' . $user_object->last_name,
      'email'         => $user_object->user_email,
      'registered'    => $user_object->user_registered,
      'business_unit' => !empty( $user_object->business_unit ) ? $user_object->business_unit : null
      //'completed'     => $this->completed_resource_IDs,
      //'not_completed' => $this->not_completed_resource_IDs
    ];

  }

  public function get_userdata () {

    return $this->userdata;

  }

  /**
   * Getter method for $this->completed_resource_IDs
   *
   * @return array
   */
  public function get_completed_resource_IDs() {

    return $this->completed_resource_IDs;

  }

  /**
   * Getter method for not_completed_resource_IDs
   *
   * @return array
   */
  public function get_not_completed_resource_IDs() {

    return $this->not_completed_resource_IDs;

  }

  /**
   * Getter method for completed_resource_data
   *
   * @return array
   */
  public function get_completed_resource_data() {

    return $this->completed_resource_data;

  }

  /**
   * Getter method for not_completed_resource_data
   *
   * @return array
   */
  public function get_not_completed_resource_data() {

    return $this->not_completed_resource_data;

  }

}
