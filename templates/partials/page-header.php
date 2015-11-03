<?php
/**
 * Header partial for staff resources
 */
?>
<h1><?php echo the_title(); ?>
<?php
if ( ( is_page ('staff-member' ) ) && isset( $_GET['staff_member'] ) ) {
  echo ": {$user_resources->get_personal_data()['full_name']}";
}
?>
</h1>
<?php //echo Staff_Area\Helpers\Navigation::breadcrumbs( $post_ID ); ?>
