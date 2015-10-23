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
   * Form processor for the "mark as read" form.
   *
   * This needs to be output in <head> - it is for PHP form submission.
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
   * User is prompted to check the box - indicating that they have read the resource.
   *
   * @return string HTML form
   */
  public function form( $current_user_ID ) {

    $checked = false !== $this->is_marked_read($current_user_ID, get_the_ID() ) ? 'checked' : '';

    ob_start();

    ?>
    <form id="mark-as-read" class="form-horizontal" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?> ">
      <p>Please tick the box and click the button to show that you have read this article:</p>
      <fieldset>
        <div class="form-group">
          <div class="col-md-1">
            <div class="checkbox">
              <label for="checkboxes-0">
                <input type="checkbox" name="read_confirmation" id="confirmation-checkbox" value="1" style="transform: scale(1.5);-webkit-transform: scale(1.5);"<?php echo $checked; ?>>
              </label>
          	</div>
          </div>
          <?php wp_nonce_field( 'staff_area_action_read_confirm','staff_area_cw_read_confirm', true, true ); // action, name (for $_POST) ?>
          <input type="hidden" name="post_id" id="cwPostID" value="<?php echo get_the_ID(); ?>">
          <input type="hidden" name="user_id" id="cwUserID" value="<?php echo $current_user_ID; ?>">
          <input type="submit" name="cw_confirm" class="btn btn-primary btn-lg" id="submit-confirmation" value="Yes! I've read this" />
        </div>
      </fieldset>
    </form>
    <div id="cw-result-message">Test</div>
    <?php

    if ( isset( $_POST['cw_confirm']) && !isset( $_POST['read_confirmation'] ) ) {

      echo "<div class='alert alert-warning'>Don't forget to tick the box!</div>";

    }

    return ob_get_clean();

  }

  /**
   * Keep a record of resources marked as read in the user's postmeta
   *
   * An array called 'resources_completed' is added to the user's postmeta table.
   * The resource post ID is held along with a timestamp that is created when the user
   * submits the "I have read this article" form.
   *
   * This must be accessible by the form processing function which is hooked to 'wp',
   * so the method is static.
   *
   * @uses get_user_meta()
   * @uses current_time()
   * @uses add_user_meta()
   * @uses update_user_meta()
   * @param  string|int $user_ID The user ID
   * @param  string|int $post_ID The post ID for the resource
   * @return void
   */
  public static function update_user_records( $user_ID, $post_ID ) {

    $resources_completed = get_user_meta( $user_ID, 'resources_completed', true );

    $time = current_time( 'timestamp' );

    // First article confirmed - so create the metadata field
    if ( empty ( $resources_completed ) ) {

      $completion_record = [];
      $completion_record['resource_' . $post_ID] = array(
          'post_ID' => $post_ID,
          'time'    => $time
      );

      add_user_meta( $user_ID, 'resources_completed', $completion_record );

      return;

    }

    // There are existing records, so add and update
    if( !empty ( $resources_completed ) ) {

      $completion_record = array(
          'post_ID' => $post_ID,
          'time'    => $time
      );

      $resources_completed['resource_' . $post_ID] = $completion_record;

      update_user_meta( $user_ID, 'resources_completed', $resources_completed );

      return;

    }

  }

  /**
   * Determine if a resource CPT has been marked as "read" by the user
   *
   * If it has, return an array of the post_ID and the timestamp. If it has NOT,
   * return boolean false.
   *
   * @uses get_user_meta()
   * @return array|boolean array of post ID and timestamp or false
   */
  public static function is_marked_read( $user_ID, $post_ID ) {

    $key = 'resource_' . $post_ID;

    $marked_resources = get_user_meta( $user_ID, 'resources_completed', true );

    if ( empty ( $marked_resources ) ) {

      return false;

    }

    if ( array_key_exists( $key, $marked_resources ) ) {

      return $marked_resources;

    } else {

      return false;

    }

  }

  /**
   * Ajax processor for the "mark as read" form
   *
   * @TODO double check validation as I'm tired!
   * @TODO add success/error report from the update user records method
   * @return [type] [description]
   */
  public static function ajax_form_processor() {

    // Not too performant - better to pass hidden values in form
    //$url     = wp_get_referer();
    //$post_id = url_to_postid( $url );

    if ( !empty( $_POST ) ) {

			// check to see if the submitted nonce matches the generated nonce: $_POST['name'], action
			if ( ! wp_verify_nonce( $_POST['cwMarkNonce'], 'staff_area_action_read_confirm' ) ) {

				die ( 'Sorry, but the security check has failed and we can\'t process this form');

			}

      // do something if the box not checked
      // -----------------------------------------------------------------------
      //isset( $_POST['cwMarkRead'] )

      if ( isset( $_POST['cwSubmitted'] ) && "1" === $_POST['cwMarkRead']) {

        $post_ID = (int) $_POST['cwPostID'];
        $user_ID = (int) $_POST['cwUserID'];

        Confirm::update_user_records( $user_ID, $post_ID );
        Confirm::success_message();

      } elseif ( isset( $_POST['cwSubmitted'] ) && "0" === $_POST['cwMarkRead']) {

        Confirm::prompt_tick_message();

      }

    }

  }

  public static function success_message(){

		// Build a success message
		// -------------------------------------------------------------------------
		$success = "You've just told us that you have read this staff-resource. Thanks!";

		// Success Return for Ajax
		// -------------------------------------------------------------------------
		//if ( true == $ajax ) {

			$response               = array(); // this will be a JSON array
			$response['status']     = 'success';
			$response['message']    = $success;

			return wp_send_json( $response ); // sends $response as a JSON object

		//}

	}

  public static function prompt_tick_message() {

    // Build a success message
		// -------------------------------------------------------------------------
		$success = "Don't forget to tick the box. Thanks!";

		// Success Return for Ajax
		// -------------------------------------------------------------------------
		//if ( true == $ajax ) {

			$response               = array(); // this will be a JSON array
			$response['status']     = 'not_ticked';
			$response['message']    = $success;

			return wp_send_json( $response ); // sends $response as a JSON object

  }

}
