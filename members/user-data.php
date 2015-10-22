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
      'completed'     => $this->completed_workbooks()
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
  private function completed_workbooks() {

    $resources_meta = get_user_meta( $this->user_ID, 'resources_completed', true );

    if ( empty ( $resources_meta ) ) {

      return false;

    }

    // Build array of data for completed resources
    // -------------------------------------------------------------------------
    if ( !empty ( $resources_meta ) ) {

      //$completed_resources = unserialize( $resources_meta[0] );
      $completed = [];
      foreach ( $resources_meta as $resource ) {

        $date = date('j-m-Y, G:i', $resource['time'] );

        $completed[] = [
          'post_ID'         => (int) $resource['post_ID'],
          'completion_date' => esc_html( $date ),
          'title'           => sanitize_text_field( get_the_title( $resource['post_ID'] ) ),
          'permalink'       => esc_url( get_the_permalink( $resource['post_ID'] ) )
        ];

      }

    }

    return $completed;

  }

  public function get_completed_resources( $return_format = 'rows' ) {

    $resources_array = $this->completed_workbooks();

    $rows = '';

    foreach( $resources_array as $resource ) {

      $rows .= "<tr><td>{$resource['title']}</td><td>{$resource['completion_date']}</td></tr>";

    }

    return $rows;

  }

}
