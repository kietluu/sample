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


function q_count_js(){
	
	if ('page' != get_post_type()) {
		
		echo '<script>
	jQuery(document).ready(function() {
	    jQuery("#title").after("<div style=\"position:absolute;top:12px;right:34px;color:red;\"><span id=\"title_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 100</span><small></small></div>");
	    jQuery("span#title_counter").text(jQuery("#title").val().length);
	    jQuery("#title").keyup(function() {
	        if (jQuery(this).val().length > 100) {
	            jQuery(this).val(jQuery(this).val().substr(0, 100));
	        }
	        jQuery("span#title_counter").text(jQuery("#title").val().length);
	    });
	    
	    
	    jQuery("#hdQue-post-class1").after("<div style=\"position:absolute;top:12px;right:13px;color:red;\"><span id=\"q1_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 40</span><small></small></div>");
	    jQuery("span#q1_counter").text(jQuery("#hdQue-post-class1").val().length);
	    jQuery("#hdQue-post-class1").keyup(function() {
	        if (jQuery(this).val().length > 40) {
	            jQuery(this).val(jQuery(this).val().substr(0, 40));
	        }
	        jQuery("span#q1_counter").text(jQuery("#hdQue-post-class1").val().length);
	    });
	    
	    
	    jQuery("#hdQue-post-class3").after("<div style=\"position:absolute;top:12px;right:13px;color:red;\"><span id=\"q3_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 40</span><small></small></div>");
	    jQuery("span#q3_counter").text(jQuery("#hdQue-post-class3").val().length);
	    jQuery("#hdQue-post-class3").keyup(function() {
	        if (jQuery(this).val().length > 40) {
	            jQuery(this).val(jQuery(this).val().substr(0, 40));
	        }
	        jQuery("span#q3_counter").text(jQuery("#hdQue-post-class3").val().length);
	    });
	
	    jQuery("#hdQue-post-class4").after("<div style=\"position:absolute;top:12px;right:13px;color:red;\"><span id=\"q4_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 40</span><small></small></div>");
	    jQuery("span#q4_counter").text(jQuery("#hdQue-post-class4").val().length);
	    jQuery("#hdQue-post-class4").keyup(function() {
	        if (jQuery(this).val().length > 40) {
	            jQuery(this).val(jQuery(this).val().substr(0, 40));
	        }
	        jQuery("span#q4_counter").text(jQuery("#hdQue-post-class4").val().length);
	    });
	});
</script>';
	}
}
add_action( 'admin_head-post.php', 'q_count_js');
add_action( 'admin_head-post-new.php', 'q_count_js');


add_filter('views_edit-post_type_question','wp37_update_movies_quicklinks');

function wp37_update_movies_quicklinks($views) {
	
	global $user_ID, $wpdb;
	$what = 'post_type_question';
	
//	$total = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'draft' OR post_status = 'pending') AND (post_author = '$user_ID'  AND post_type = '$what' ) ");
	$publish = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_author = '$user_ID' AND post_type = '$what' ");
	$draft = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft' AND post_author = '$user_ID' AND post_type = '$what' ");
	$trash = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'trash' AND post_author = '$user_ID' AND post_type = '$what' ");
	$used = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'used' AND post_author = '$user_ID' AND post_type = '$what' ");
	
	unset($views['all']);
	
	$views['publish'] = "<strong>Published <span class=\"count\">(".$publish.")</span></strong>";
	$views['draft'] = preg_replace( '/\(.+\)/U', '('.$draft.')', $views['draft'] );
	$views['trash'] = preg_replace( '/\(.+\)/U', '('.$trash.')', $views['trash'] );
	$views['used'] = "<strong>Used <span class=\"count\">(".$used.")</span></strong>";
	
	return $views;
	
}
