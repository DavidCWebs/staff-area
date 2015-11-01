<?php
namespace Staff_Area\Display;
use Staff_Area\Members;

/**
 *
 */
class Resources_Status {

  private $personal_data;

  private $staff_member_ID;

  private $personal_details;

  private $resource_data;

  private $user_data;

  /**
   * Set up properties
   *
   * Use dependency injection and type hinting to ensure that 2nd parameter is an object
   * of the class Staff_Area\Members\User_Data()
   *
   * @param string|int  $staff_member_ID User ID of the staff member
   * @param object      $user_data       User_Data object
   */
  function __construct( $staff_member_ID, Members\User_Data $user_data ) {

    $this->staff_member_ID = $staff_member_ID;
    $this->user_data = $user_data;
    $this->set_resource_data();
    $this->set_personal_data();

  }

  private function set_personal_data() {

    $this->personal_data = $this->user_data->get_userdata();

  }

  public function get_personal_data() {

    return $this->personal_data;

  }

  private function set_resource_data() {

    $resource_data = [];

    // Completed Workbooks
    $resource_data['completed_workbooks'] = $this->user_data->get_completed_resource_data();

    // Not Completed Workbooks
    $resource_data['not_completed_workbooks'] = $this->user_data->get_not_completed_resource_data();

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
      <h3>Staff Resources: Completed by <?php echo $this->personal_data['first_name']; ?></h3>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Date Completed</th>
            <th>Is this Resource Compulsory?</th>
          </tr>
        </thead>
        <tbody>
          <?php

          foreach ( $resource_array as $resource ) {

            $compulsory = true === \Staff_Area\Resources\Data::is_compulsory( $resource['post_ID'] ) ? "Yes" : "No";

            echo "<tr class='post-ID-{$resource['post_ID']}'>";
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
      <?php echo $this->personal_data['first_name']; ?> has not marked any staff resources as complete.
    </p>
    <?php

    }

  }

  /**
   * Build not completed resources table
   *
   * Outputs a table of not-completed staff-resource CPTs, specific to the given staff member.
   *
   * @return string HTML table markup, showing not completed resources for the given staff member
   */
  public function not_completed_resources_table () {

    $resource_array = $this->resource_data['not_completed_workbooks'];

    if ( is_array( $resource_array ) ){

      ?>
      <h3>Staff Resources: Not Complete</h3>
      <p>
        <?php echo $this->personal_data['first_name']; ?> has not completed the following staff resources:
      </p>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Is this Resource Compulsory?</th>
          </tr>
        </thead>
        <tbody>
        <?php

        foreach ( $resource_array as $resource ) {

          $compulsory = true === \Staff_Area\Resources\Data::is_compulsory( $resource['post_ID'] ) ? "Yes" : "No";

          echo "<tr class='post-ID-{$resource['post_ID']}'>";
          echo "<td><a href='{$resource['permalink']}'>{$resource['title']}</a></td>";
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
