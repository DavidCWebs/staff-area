<?php
namespace Staff_Area\Display;

/**
 *
 */
class Member_Resource_Status {

  private $staff_member_ID;

  private $personal_details;

  private $resource_data;

  function __construct( $staff_member_ID ) {

    $this->staff_member_ID = $staff_member_ID;
    $this->set_user_data();
    $this->set_resource_data();

  }

  private function get_user_data() {

    $userdata = new \Staff_Area\Members\User_Data( $this->staff_member_ID );

    $this->personal_details = $userdata->get_userdata();

  }

  private function set_resource_data() {

    $resource_data = [];

    // Completed Workbooks
    $resource_data['completed_workbooks'] = $userdata->get_completed_resource_data();

    // Not Completed Workbooks
    $resource_data['not_completed_workbooks'] = $userdata->get_not_completed_resource_data();

    $this->resource_data = $resource_data;

  }

  /**
   * [resources_table description]
   *
   * @param  string $completion_status [description]
   * @param  string $compulsory        [description]
   * @return [type]                    [description]
   */
  public function completed_resources_table () {

    $resource_array = $this->resource_data['completed_workbooks'] ;

    if ( is_array( $resource_array ) ){

      ?>
      <p>
        <?php echo $this->personal_details['first_name']; ?> has marked the following staff resources as complete:
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
            echo "<td><a href='{$resource['permalink']}'>{$resource['title']}: {$resource['post_ID']}</a></td>";
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
      <?php echo $this->personal_details['first_name']; ?> has not marked any staff resources as complete.
    </p>
    <?php

    }

  }

  public function not_completed_resources_table () {

    $resource_array = $this->resource_data['not_completed_workbooks'];

    if ( is_array( $resource_array ) ){

      ?>
      <p>
        <?php echo $this->personal_details['first_name']; ?> has not completed the following staff resources:
      </p>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Compulsory?</th>
          </tr>
        </thead>
        <tbody>
          <?php

          foreach ( $resource_array as $resource ) {

            $compulsory = true === \Staff_Area\Resources\Data::is_compulsory( $resource['post_ID'] ) ? "Yes" : "No";

            echo "<tr>";
            echo "<td><a href='{$resource['permalink']}'>{$resource['title']} :{$resource['post_ID']}</a></td>";
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
