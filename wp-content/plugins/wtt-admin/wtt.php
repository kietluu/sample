<?php
/**
 * @package WTT Admin
 * @version 1.0
 */
/*
Plugin Name: WTT Admin
Plugin URI:
Description: WTT Admin
Author: AnhKiet
Version: 1.0
Author URI:
*/

include(plugin_dir_path(__FILE__) . 'inc/roles.php');
include(plugin_dir_path(__FILE__) . 'inc/helper.php');
include(plugin_dir_path(__FILE__) . 'inc/post.php');
include(plugin_dir_path(__FILE__) . 'inc/custom-columns-post.php');
include(plugin_dir_path(__FILE__) . 'inc/metabox-level.php');
include(plugin_dir_path(__FILE__) . 'inc/sql/install.php');
include(plugin_dir_path(__FILE__) . 'inc/push-question.php');
include(plugin_dir_path(__FILE__) . 'inc/filter.php');

if ( !defined( 'WTT_CUSTOM_POST_URL' ) ) {
	define( 'WTT_CUSTOM_POST_URL', plugins_url( 'wtt-admin' ) );
}

if ( ! defined( 'WTT_CUSTOM_POST_DIR' ) ) {
	define( 'WTT_CUSTOM_POST_DIR', trailingslashit(WTT_CUSTOM_POST_URL ) );
}

function addCustomScript()
{
	$version = '1.0.1';
	wp_register_script('jquery-confirm', WTT_CUSTOM_POST_DIR . 'js/jquery-confirm/jquery-confirm.min.js', array('jquery'), true);
	wp_register_script('custom-post', WTT_CUSTOM_POST_DIR . 'js/custom-post.js', array('jquery'), $version);
	wp_register_script('boostrapjs', WTT_CUSTOM_POST_DIR . 'js/bootstrap.min.js', array('jquery'), $version);
	wp_register_style('jquery-confirm-css', WTT_CUSTOM_POST_DIR . 'js/jquery-confirm/jquery-confirm.min.css');
	
	wp_enqueue_script('custom-post');
	wp_enqueue_script('boostrapjs');
	wp_enqueue_script('jquery-confirm');
	wp_enqueue_style('jquery-confirm-css');
	wp_enqueue_style('style-name', WTT_CUSTOM_POST_DIR . 'inc/admin.css');
	
}

add_action('admin_enqueue_scripts', 'addCustomScript');
register_activation_hook( __FILE__, 'wtt_install_db_log' );
