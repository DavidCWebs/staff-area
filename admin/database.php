<?php
namespace Staff_Area\Admin;

/**
 * Database class
 *
 * This class is for interacting with the custom database table
 *
 * @package     Staff_Area
 * @copyright   Copyright (c) 2015, David Egan
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

class Database {

  public function __construct(  ) {

    //$this->new_version      = $version;
    //$this->current_version  = get_option( 'staff_area_db_version', 0 );

  }

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
      PRIMARY KEY  (ID)
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

  public static function setup_business_units_table( $new_version ) {

    global $wpdb;
    global $custom_table_db_version;
    $current_version  = get_option( 'staff_area_db_version', 0 );
    // shortcuts for Staff Area DB tables
  	$wpdb->staff_area_business_units = $wpdb->prefix . 'staff_area_business_units';

    $table_name = $wpdb->prefix . 'staff_area_business_units'; // do not forget about tables prefix

    if ($current_version != $new_version ) {
      $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      name tinytext NOT NULL,
      email VARCHAR(200) NOT NULL,
      location VARCHAR(200) NULL,
      supervisor int(11) NULL,
      PRIMARY KEY  (id)
    );";

    //$wpdb->query( "ALTER TABLE $table_name DROP COLUMN age" );

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // notice that we are updating option, rather than adding it
    update_option( 'staff_area_db_version', $new_version );

    }

  }

  public static function custom_table_dummy_data() {

    global $wpdb;

    $table_name = $wpdb->prefix . 'staff_area_business_units'; // do not forget about tables prefix

    $wpdb->insert($table_name, array(
      'name'        => 'Ennis Community College',
      'email'       => 'alex@example.com',
      'location'    => 'Ennis',
      'supervisor'  =>  11
    ));
    
    $wpdb->insert($table_name, array(
      'name' => 'Scoil Mhuire',
      'email' => 'maria@example.com',
      'location'    => 'Limerick',
      'supervisor'  =>  12
    ));

  }

}
