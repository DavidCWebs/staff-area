<?php
namespace Staff_Area\Display;
use Staff_Area\User_Input;

/**
 * Class to conditionally show elements on staff resource templates
 */
class Single_Resource {

  /**
   * Conditionally display the checkbox on staff resource CPTs
   *
   * The checkbox form allows usermeta to be updated, tracking which resources have been read
   * by each staff member.
   *
   * The title is filtered with 'cw_staff_area_feedback_title'.
   *
   * @param  string|int $post_ID          Post ID of the staff resource
   * @param  string|int $current_user_ID  User ID of the current user
   * @param  mixed $marked                Returns false if the article has not been read yet
   * @return string                      HTML form markup
   */
  public static function display_checkbox( $post_ID, $current_user_ID, $marked ) {

    // Check if the admin has selected the confirm form for inclusion
    if( "1" === get_post_meta( $post_ID, 'include_status', true ) ) {

      if ( false === $marked ) {

        // The article has NOT been marked as read, so display the confirmation form
        echo apply_filters( 'cw_staff_area_feedback_title', '<h2>Feedback</h2>' );

        $confirm_status = new \Staff_Area\User_Input\Confirm( $post_ID, $current_user_ID );

        echo $confirm_status->form( $current_user_ID );

      }

    }

    return;

  }

  /**
   * Conditionally display resources marked as complete for this user
   *
   * @param  int|string $current_user_ID The current user's ID
   * @return string                      HTML unordered list
   */
  public static function display_marked_resources( $current_user_ID ) {

    $marked_resources = get_user_meta( $current_user_ID, 'resources_completed', true );

    if ( !empty( $marked_resources ) ) {

      echo apply_filters( 'cw_staff_area_completed_articles_title', '<h2>Articles You Have Marked Complete</h2>' );

      echo "<ul>";

      foreach ( $marked_resources as $marked_resource_ID => $data ) {

        $title  = get_the_title( $data['post_ID'] );
        $time   = date('l jS \of F Y h:i:s A', $data['time'] );
        echo "<li>Resource: $title Marked complete on $time</li>";

      }

      echo "</ul>";

    } else {

      return;

    }

  }

  public static function display_file_download () {

    $file = get_field( 'download' );

    if ( !$file ) { return; }

    $filesize = size_format( filesize( get_attached_file( $file['id'] ) ) );
    $filetype = wp_check_filetype( get_attached_file( $file['id'] ) );
    ob_start();
    ?>
    <h3>Download Resource Files</h3>
      <a href="<?= $file['url']; ?>" target="_blank">Download <?= $file['title']; ?></a> (<?= $filetype['ext']. ", " . $filesize ;?> )
    <?php
    echo ob_get_clean();

  }

  public static function get_repeater_file_download ( $post_ID ) {

    $files = get_post_meta( $post_ID, 'downloads', true );

    if ( $files ) {

      $file_data = [];

      for( $i = 0; $i < $files; $i++ ) {

        $file = get_post_meta( get_the_ID(), 'downloads_' . $i . '_file', true );
        //caradump( $file );

        $file_data[]  = [
          'filesize'  => size_format( filesize( get_attached_file( $file ) ) ),
          'filetype'  => wp_check_filetype( get_attached_file( $file ) ),
          'url'       => get_the_permalink( $file ),
          'title'     => get_the_title( $file )
        ];

      }

      return $file_data;

    } else {

      return;
      
    }

  }

}
