<?php
namespace Staff_Area\Members;

class Staff_Dashboard {

  /**
   * A multi-dimensional array of staff members,
   * @var array
   */
  public $staff_data_arrays;

  public function __construct() {

    $this->set_staff_member_objects();
    $this->set_staff_data_arrays();
    $this->set_naughty_users();

  }

  /**
   * Set an array of user data for users with the role 'staff_member'
   *
   */
  public function set_staff_data_arrays() {

    $args = [
      'role' => 'staff_member',
      'fields' => [
        'ID',
        'user_email'
        ]
    ];

    // User IDs, emails for role 'staff_member', returned as an array of stdClass objects
    // -------------------------------------------------------------------------
    $staff_members = get_users( $args );

    $this->staff_data_arrays = [];

    // Loop through the array of stdClass objects, build useful array of data
    foreach ( $staff_members as $key => $staff_member ) {

      $allmeta = get_user_meta( $staff_member->ID );
      $completed = $this->completed_workbooks( $allmeta );

      $this->staff_data_arrays[] = [
        'user_ID'             => $staff_member->ID,
        'first_name'          => $allmeta['first_name'][0],
        'last_name'           => $allmeta['last_name'][0],
        'email'               => $staff_member->user_email,
        'completed_resources' => $completed
      ];

    }

  }

  /**
   * Set an array of users who have outstanding compulsory resources
   *
   * Set an object property array that contains the user ID and an array of resource IDs
   * for any outstanding resources.
   *
   */
  private function set_naughty_users() {

    $compulsory_resources = \Staff_Area\Resources\Data::get_compulsory_resources ();

    $this->naughty_users = [];

    // Loop through all 'staff_member' users
    foreach( $this->staff_data_arrays as $key => $value ) {

      // This will stay empty if the user has not marked any resources as complete
      $completed_IDs = [];

      // This user has completed some resources - check for completion of compulsory resources
      if( !empty ( $value['completed_resources'] ) ) {

        // Build an array of completed IDs of completed resources
        foreach ( $value['completed_resources'] as $completed ) {

          $completed_IDs[] = $completed['post_ID'];

        }

      }

      // Check compulsory completion for this user
      $outstanding = array_diff( $compulsory_resources, $completed_IDs );

      // There ARE outstanding compulsory resources, so add this user to the $this->naughty_users array
      if ( !empty ( $outstanding ) ) {

        $this->naughty_users[] = [
          'user_ID'                 => $value['user_ID'],
          'outstanding_compulsory'  => $outstanding
        ];

      }

    }

  }

  public function get_naughty_users() {

    return $this->naughty_users;

  }

  /**
   * A method to set staff member objects
   *
   * @TODO This should be broken out into a standalone class
   *
   */
  public function set_staff_member_objects() {

    $args = [
      'role' => 'staff_member',
      'fields' => [
        'ID',
        'user_email'
        ]
    ];

    // User IDs, emails for role 'staff_member', returned as an array of stdClass objects
    // -------------------------------------------------------------------------
    $staff_members = get_users( $args );

    $this->staff_objects = [];

    // Loop through the array of stdClass objects, build useful array of data
    foreach ( $staff_members as $key => $staff_member ) {

      $allmeta = get_user_meta( $staff_member->ID );
      $completed = $this->completed_workbooks( $allmeta );

      $this->staff_objects[] = (object)[
        'user_ID'             => $staff_member->ID,
        'first_name'          => $allmeta['first_name'][0],
        'last_name'           => $allmeta['last_name'][0],
        'email'               => $staff_member->user_email,
        'completed_resources' => $completed
      ];

    }

  }

  /**
   * [completed_workbooks description]
   * @param  [type] $allmeta [description]
   * @return [type]          [description]
   */
  private function completed_workbooks( $allmeta ) {

    if ( empty ( $allmeta['resources_completed'] ) ) {

      return false;

    }

    // Serialized data relating to completed workbooks, from usermeta entry
    // -------------------------------------------------------------------------
    $completed_resources = !empty ( $allmeta['resources_completed'] ) ? $allmeta['resources_completed'] : null;

    // Build array of data for completed resources
    // -------------------------------------------------------------------------
    if ( !empty ( $completed_resources ) ) {

      $completed_resources = unserialize( $completed_resources[0] );
      $completed = [];
      foreach ( $completed_resources as $resource ) {

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

  /**
   * Build a string of completed resource data for inclusion in the dashboard table
   *
   * @param  array $staff_member Data for this staff member
   * @return string              Completed resource output
   */
  private function completed_resources_string ( $staff_member ) {

    $completed_resources = '';

    if ( !empty ( $staff_member['completed_resources'] ) ) {

      // Loop through the completed resources array
      foreach( $staff_member['completed_resources'] as $completed_resource ) {

        // Build a string of completed resource info
        $completed_resources .= "<a href='{$completed_resource['permalink']}'>{$completed_resource['title']}</a>";
        $completed_resources .= ", on {$completed_resource['completion_date']}<br>";

      }

    } elseif ( false === $staff_member['completed_resources'] ) {

      $completed_resources = "-";

    }

    return $completed_resources;

  }

  /**
   * Output a table with staff information
   *
   * @return string HTML table markup
   */
  public function render_table(){

    $return_html = '';

    $i = 1;

    ob_start();

      ?>
      <table class="table">
        <tr>
          <th>Name</th>
          <th>Email Address</th>
          <th>Phone Number</th>
          <th>Resources Marked Complete</th>
        </tr>
        <?php

        // Add a new table row for each staff member
        foreach( $this->staff_data_arrays as $staff_member ) {

          $completed_resources  = $this->completed_resources_string( $staff_member );
          $name                 = $staff_member['first_name'] . ' ' . $staff_member['last_name'];

          ?>
          <tr>
          <td><a href="<?php echo esc_url( home_url('/staff-member') ) . '?staff_member=' . (int) $staff_member['user_ID']; ?>"><?php echo $name; ?></a></td>
          <td><a href="mailto:<?php echo $staff_member['email'] ; ?>"><?php echo $staff_member['email'] ; ?></a></td>
          <td> - </td>
          <td><?php echo $completed_resources; ?></td>
          </tr>
          <?php

        }
        ?>
      </table>
      <?php

      $return_html .= ob_get_clean();

      $i ++;

    return $return_html;

  }

}
