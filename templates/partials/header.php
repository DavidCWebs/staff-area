<?php
/**
 * Header partial for staff resources
 */
$marked = Staff_Area\User_Input\Confirm::is_marked_read( $current_user_ID, $post_ID );
?>
<h1><?php echo the_title(); ?></h1>
<?php
echo false !== $marked ? "<div class='alert alert-success'>You have read this article.</div>" : '';
echo Staff_Area\Helpers\Navigation::breadcrumbs( $post_ID );
?>
