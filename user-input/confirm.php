<?php
namespace Staff_Area\User_Input;

/**
 * A class to confirm that a user has read a resource
 *
 * Outputs a simple checkbox form. Checking the form indicates that the user
 * has read and understood the article.
 *
 * Form submission is by AJAX with PHP fallback.
 *
 * Form submission causes the usermeta for the user to be amended, to reflect the
 * read status with a timestamp.
 *
 */
class Confirm {

  /**
   * The confriming user's ID
   * @var [type]
   */
  private $user_ID;

  /**
   * ID of the post being checked
   * @var [type]
   */
  private $post_ID;

  public function __construct( $post_ID, $user_ID ) {

    $this->user_ID = $user_ID;
    $this->post_ID = $post_ID;

  }

  /**
   * this needs to be output in <head>
   *
   * @return void
   */
  public static function form_processor() {

    if ( !empty( $_POST ) ) {

			// check to see if the submitted nonce matches the generated nonce: $_POST['name'], action
			if (
        ! isset( $_POST['staff_area_cw_read_confirm'] )
        || ! wp_verify_nonce( $_POST['staff_area_cw_read_confirm'], 'staff_area_action_read_confirm' ) ) {

				die ( 'Sorry, but the security check has failed and we can\'t process this form');

			}

      //$_SESSION['confirm'] = $_POST;

      if ( isset( $_POST['cw_confirm'] ) && "1" === $_POST['read_confirmation']) {

        Confirm::update_user_records( get_current_user_id(), get_the_ID() );

        //$_SESSION['check'] = array( 'post_ID' => get_the_ID(), 'user_ID' => get_current_user_id() );

        header('Location: '.$_SERVER['REQUEST_URI']);

        exit();

      }

    }

  }

  /**
   * Output a simple checkbox with a message
   *
   * @return HTML form
   */
  public function form() {

    $checked = false !== $this->is_marked_read() ? 'checked' : '';

    ob_start();

    ?>
    <p>Please tick the box and click the button to show that you have read this article:</p>
    <form class="form-horizontal" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?> ">
      <fieldset>
        <div class="form-group">
          <div class="col-md-1">
            <div class="checkbox">
              <label for="checkboxes-0">
                <input type="checkbox" name="read_confirmation" id="checkboxes-0" value="1" style="transform: scale(1.5);-webkit-transform: scale(1.5);"<?php echo $checked; ?>>
              </label>
          	</div>
          </div>
          <?php wp_nonce_field( 'staff_area_action_read_confirm','staff_area_cw_read_confirm', true, true ); // action, name (for $_POST) ?>
            <input type="submit" name="cw_confirm" class="btn btn-primary btn-lg" id="submit-confirmation" value="Yes! I've read this" />
        </div>
      </fieldset>
    </form>
    <?php

    if ( isset( $_POST['cw_confirm']) && !isset( $_POST['read_confirmation'] ) ) {

      echo "<div class='alert alert-warning'>Don't forget to tick the box!</div>";

    }

    return ob_get_clean();

  }

  /**
   * [update_user_records description]
   * @param  [type] $user_ID [description]
   * @param  [type] $post_ID [description]
   * @return [type]          [description]
   */
  public static function update_user_records( $user_ID, $post_ID ) {

    $resources_completed = get_user_meta( $user_ID, 'resources_completed', true );

    $time = time();

    // First article confirmed - so create the metadata field
    if ( empty ( $resources_completed ) ) {

      $completion_record = [];
      $completion_record['resource_' . $post_ID] = array(
          'post_ID' => $post_ID,
          'time'    => $time
      );

      add_user_meta( $user_ID, 'resources_completed', $completion_record );

    }

    // There are existing records, so add and update
    if( !empty ( $resources_completed ) ) {

      $completion_record = array(
          'post_ID' => $post_ID,
          'time'    => $time
      );

      $resources_completed['resource_' . $post_ID] = $completion_record;

      update_user_meta( $user_ID, 'resources_completed', $resources_completed );

    }

  }

  public function is_marked_read() {

    $key = 'resource_' . $this->post_ID;

    $marked_resources = get_user_meta( $this->user_ID, 'resources_completed', true );

    if ( array_key_exists( $key, $marked_resources ) ) {

      return $marked_resources[$key];

    } else {

      return false;

    }

  }

}
