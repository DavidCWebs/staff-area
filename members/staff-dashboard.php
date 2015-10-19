<?php
namespace Staff_Area\Members;

class Staff_Dashboard {

  public $staff;

  public function __construct() {

    $this->set_staff();

  }

  /**
   * Set an array of user IDs for users with the role 'staff_member'
   *
   */
  public function set_staff() {

    $args = [
      'role' => 'staff_member',
      'fields' => [
        'ID',
        'user_email'
        ]
    ];

    $staff_members = get_users( $args );

    $this->staff = [];

    foreach ( $staff_members as $key => $staff_member ) {

      $allmeta = get_user_meta( $staff_member->ID );

      $this->allmeta = $allmeta;

      $completed_resources = !empty ( $allmeta['resources_completed'] ) ? $allmeta['resources_completed'] : null;

      $completed = [];
      // Build info on completed resources
      if ( !empty ($completed_resources ) ) {

        $completed_resources = unserialize( $completed_resources[0] );
        $completed = [];
        foreach ( $completed_resources as $resource ) {

          //$date = date('jS M Y G:i', $resource['time'] );
          $date = date('j-m-Y, G:i', $resource['time'] );

          $completed[] = [
            'post_ID'         => (int) $resource['post_ID'],
            'completion_date' => esc_html( $date ),
            'title'           => sanitize_text_field( get_the_title( $resource['post_ID'] ) ),
            'permalink'       => esc_url( get_the_permalink( $resource['post_ID'] ) )
          ];

        }

      }

      $this->staff[] = [
        'first_name'          => $allmeta['first_name'][0],
        'last_name'           => $allmeta['last_name'][0],
        'email'               => $staff_member->user_email,
        'completed_resources' => $completed
      ];

    }

  }

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
          <th>Resources Completed</th>
        </tr>
        <?php

        foreach( $this->staff as $staff_member ) {

          $completed_resources = '';

          if ( !empty ( $staff_member['completed_resources'] ) ) {

            foreach( $staff_member['completed_resources'] as $completed_resource ) {

              $completed_resources .= $completed_resource['title'] . ", on {$completed_resource['completion_date']}<br>";

            }

          }
          // Each staff member: add a new table row
          // ---------------------------------------------------------------
          ?>
          <tr>
          <td><?php echo $staff_member['first_name'] . ' ' . $staff_member['last_name']; ?></td>
          <td><?php echo $staff_member['email'] ; ?></td>
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
