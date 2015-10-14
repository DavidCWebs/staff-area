<?php
namespace Staff_Area\Members;

class Toolbar {

  function  edit_toolbar( $wp_toolbar ) {

    $wp_toolbar->remove_node('wp-logo');
    //$wp_toolbar->remove_node('site-name');
    $wp_toolbar->remove_node('updates');
    $wp_toolbar->remove_node('comments');
    $wp_toolbar->remove_node('new-content');
    //$wp_toolbar->remove_node('top-secondary');
    $wp_toolbar->remove_node('customize');

  }

  /**
	 * Control access to the WordPress toolbar
	 *
	 * @return [type] [description]
	 */
	function control_admin_toolbar() {

	  if ( current_user_can( 'administrator' ) ||  current_user_can( 'edit_pages' ) ) {

	    show_admin_bar( true );

	  } else {

	    show_admin_bar( false );

	  }

	}

}
