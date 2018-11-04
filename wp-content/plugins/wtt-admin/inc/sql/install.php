<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/14/18
 * Time: 11:17 AM
 */


global $wtt_version_db_log;
$wtt_version_db_log = '2.0';

function wtt_install_db_log()
{
	global $wpdb;
	global $wtt_version_db_log;
	
	$installed_ver = get_option("wtt_version_db_log");
	
	if ($installed_ver != $wtt_version_db_log) {
		
		$table_name = $wpdb->prefix . 'log_push_question';
		
		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		content text NOT NULL,
		ids text NOT NULL,
		PRIMARY KEY  (id)
	);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		update_option("wtt_version_db_log", $wtt_version_db_log);
	}
	
	//add column "rules"
	if ($installed_ver != $wtt_version_db_log) {
		$table_name = $wpdb->prefix . 'log_push_question';
		$sql = "ALTER TABLE $table_name ADD rules text NULL;";
		$wpdb->query($sql);
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		update_option("wtt_version_db_log", $wtt_version_db_log);
	}
	
	add_option('wtt_version_db_log', $wtt_version_db_log);
}


