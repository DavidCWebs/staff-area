<?php
namespace Staff_Area\Members;

class Staff_Dashboard {

  /**
   * A multi-dimensional array of staff members,
   * @var array
   */
  public $staff_data_arrays;

  /**
   * The user role for this object
   * @var string
   */
  private $role;

  public function __construct( $role = 'staff_member' ) {

    $this->role = $role;
    $this->set_title();
    $this->set_intro();
    $this->set_staff_member_objects();
    $this->set_staff_data_arrays();
    $this->set_naughty_users();

  }

  private function set_title() {

    if ( 'staff_member' === $this->role ) {

      $this->title = __( 'Staff Records', 'staff-area' );

    }

    if ( 'staff_supervisor' === $this->role ) {

      $this->title = __( 'Supervisor Records', 'staff-area' );

    }

  }

  private function set_intro() {

    if ( 'staff_member' === $this->role ) {

      $this->intro = __( 'This table shows all ordinary staff members.', 'staff-area' );

    }

    if ( 'staff_supervisor' === $this->role ) {

      $this->intro = __( 'This table shows all Supervisor staff members.', 'staff-area' );

    }

  }

  /**
   * Set an array of user data for users with the role 'staff_member'
   *
   */
  public function set_staff_data_arrays() {

    $args = [
      'role' => $this->role,
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

      $allmeta        = get_user_meta( $staff_member->ID );
      $completed      = $this->completed_workbooks( $allmeta );
      $index          = 'staff_member_' . $staff_member->ID;
      $business_unit  = !empty( $allmeta['business_unit'][0] ) ? $allmeta['business_unit'][0] : null;
      $phone_number   = !empty( $allmeta['phone_number'][0] ) ? $allmeta['phone_number'][0] : null;

      $this->staff_data_arrays[$index] = [
        'user_ID'             => $staff_member->ID,
        'first_name'          => $allmeta['first_name'][0],
        'last_name'           => $allmeta['last_name'][0],
        'business_unit'       => get_the_title( $business_unit ),
        'email'               => $staff_member->user_email,
        'completed_resources' => $completed,
        'display_phone'       => $phone_number,
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

      $completed_IDs = $this->get_completed_resource_IDs( $value );

      // Check compulsory completion for this user
      $outstanding = array_diff( $compulsory_resources, $completed_IDs );

      // There ARE outstanding compulsory resources, so add this user to the $this->naughty_users array
      if ( !empty ( $outstanding ) ) {

        $this->naughty_users[] = [
          'first_name'              => $value['first_name'],
          'last_name'               => $value['last_name'],
          'email'                   => $value['email'],
          'user_ID'                 => $value['user_ID'],
          'outstanding_compulsory'  => $this->not_completed_resource_return_data( $outstanding )
        ];

      }

    }

  }

  private function get_completed_resource_IDs( $value ) {

    // This will stay empty if the user has not marked any resources as complete
    $completed_IDs = [];

    // This user has completed some resources - check for completion of compulsory resources
    if( !empty ( $value['completed_resources'] ) ) {

      // Build an array of completed IDs of completed resources
      foreach ( $value['completed_resources'] as $completed ) {

        $completed_IDs[] = $completed['post_ID'];

      }

    }

    return $completed_IDs;

  }

  /**
   * Build an array of not completed resource data for display purposes
   *
   * @param  array  $resources_IDs  An array of not completed resource post IDs
   * @return array                  An array of data to be displayed
   */
  public function not_completed_resource_return_data ( array $resources_IDs ) {

    $resources_data_array = [];

    foreach( $resources_IDs as $resource_ID ) {

      $resources_data_array[] = [
        'post_ID'         => (int) $resource_ID,
        'title'           => sanitize_text_field( get_the_title( $resource_ID ) ),
        'permalink'       => esc_url( get_the_permalink( $resource_ID ) )
      ];

    }

    return $resources_data_array;

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

  private function outstanding_resources_string ( $staff_member ) {

    $outstanding_resources = '';

    if ( !empty ( $staff_member['outstanding_compulsory'] ) ) {

      // Loop through the outstanding compulsory resources array
      foreach( $staff_member['outstanding_compulsory'] as $outstanding_resource ) {

        // Build a string of completed resource info
        $outstanding_resources .= "<a href='{$outstanding_resource['permalink']}'>{$outstanding_resource['title']}</a>";
        $outstanding_resources .= "<br>";

      }

    } elseif ( false === $staff_member['outstanding_compulsory'] ) {

      $outstanding_resources = "-";

    }

    return $outstanding_resources;

  }

  /**
   * Output a table with staff information
   *
   * @return string HTML table markup
   */
  public function render_table(){

    $return_html  = "<h3>$this->title</h3><p>$this->intro</p>";

    $i = 1;

    ob_start();

      ?>
      <div>
        <table class="table table-striped table-responsive">
          <tr>
            <th>Name</th>
            <th>Business Unit</th>
            <th class="hidden-xs">Email Address</th>
            <th>Phone Number</th>
            <th>Outstanding Compulsory Resources</th>
          </tr>
          <?php

          // Add a new table row for each staff member
          foreach( $this->staff_data_arrays as $staff_member ) {

            $completed    = $this->completed_resources_string( $staff_member );
            $member_link  = esc_url( home_url('/staff-member') ) . '?staff_member=' . (int) $staff_member['user_ID'];
            $status       = false === $this->has_outstanding( $staff_member ) ? "none-outstanding" : "outstanding";
            $outstanding  = "none-outstanding" === $status ? 'No' : '<a href="' . $member_link . '" title="View ' . $staff_member['first_name'] . '\'s full record">Yes</a>';
            $name         = $staff_member['first_name'] . ' ' . $staff_member['last_name'];
            $tel          = preg_replace( '/\s+/', '', $staff_member['display_phone'] );

            ?>
            <tr class="<?= $status; ?>">
              <td>
                <a href="<?= $member_link; ?>" title="View <?= $staff_member['first_name']; ?>'s full record"><?php echo $name; ?></a>
              </td>
              <td>
                <?= $staff_member['business_unit']; ?>
              </td>
              <td class="hidden-xs">
                <a href="mailto:<?php echo $staff_member['email'] ; ?>" title="Click here to email <?= $staff_member['first_name']; ?>"><?php echo $staff_member['email'] ; ?></a>
              </td>
              <td>
                <span class="hidden-xs">
                  <?= $staff_member['display_phone']; ?>
                </span>
                <span class="visible-xs hidden-sm hidden-md hidden-lg">
                <a href="tel:<?= $tel;?>" class="btn-primary btn">
                  <i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;Click to call <?php //echo $user_resources->get_personal_data()['first_name']; ?></a>
                </span>
              </td>
              <td><?php echo $outstanding; ?></td>
            </tr>
            <?php

          }
          ?>
        </table>
      </div>
      <?php

      $return_html .= ob_get_clean();

      $i ++;

    return $return_html;

  }

  private function has_outstanding( $staff_member ) {

    $compulsory_resources = \Staff_Area\Resources\Data::get_compulsory_resources();

    $completed_IDs = $this->get_completed_resource_IDs( $staff_member );

    // Check compulsory completion for this user
    $outstanding = empty( array_diff( $compulsory_resources, $completed_IDs ) ) ? false : true;

    return $outstanding;

  }

  /**
   * Output a table with staff information
   *
   * @return string HTML table markup
   */
  public function naughty_users_table(){

    $intro        = __( 'This table shows staff members who have not completed compulsory staff resources.', 'staff-area' );
    $title        = __( 'Training Records: Outstanding Compulsory Resources', 'staff-area' );
    $return_html  = "<h3>$title</h3><p>$intro</p>";

    $i = 1;

    ob_start();

      ?>
      <table class="table">
        <tr>
          <th>Name</th>
          <th>Email Address</th>
          <th>Compulsory Resources Outstanding</th>
        </tr>
        <?php

        // Add a new table row for each staff member
        foreach( $this->naughty_users as $staff_member ) {

          $completed_resources  = $this->outstanding_resources_string( $staff_member );
          $name                 = $staff_member['first_name'] . ' ' . $staff_member['last_name'];

          ?>
          <tr>
          <td><a href="<?php echo esc_url( home_url('/staff-member') ) . '?staff_member=' . (int) $staff_member['user_ID']; ?>"><?php echo $name; ?></a></td>
          <td><a href="mailto:<?php echo $staff_member['email'] ; ?>"><?php echo $staff_member['email'] ; ?></a></td>
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
