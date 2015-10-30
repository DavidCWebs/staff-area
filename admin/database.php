<?php
namespace Staff_Area\Admin;

class Database {

  public static function setup_database_business_units_table() {

    global $wpdb;

  	// shortcuts for SchoolPress DB tables
  	$wpdb->staff_area_business_units = $wpdb->prefix . 'staff_area_business_units';

  	$db_version = get_option( 'staff_area_db_version', 0 );

  	// create tables on new installs
  	if ( empty( $db_version ) ) {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'staff_area_business_units';

  	$sqlQuery =	"CREATE TABLE $wpdb->staff_area_business_units (
      ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      unit_name varchar(60) NOT NULL DEFAULT '',
      supervisor_ID bigint(20) unsigned,
      address longtext NOT NULL DEFAULT '',
      PRIMARY KEY (ID)
    	) $charset_collate;";

  		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  		dbDelta( $sqlQuery );

  		$db_version = '1.0';
  		update_option( 'staff_area_db_version', '1.0' );

  	}
    /*

    if ( version_compare( $db_version, '2.0' ) < 0 ) {

      $sqlQuery =	"CREATE TABLE $wpdb->staff_area_business_units (
        ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        unit_name varchar(60) NOT NULL DEFAULT '',
        supervisor_ID bigint(20) unsigned,
        address longtext NOT NULL DEFAULT '',
        phone_number bigint(20),
        PRIMARY KEY (ID)
      	) $charset_collate;";

    		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    		dbDelta( $sqlQuery );

    		update_option( 'staff_area_db_version', '2.0' );

    }

    */

  }

}
