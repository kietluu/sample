<?php
/*
 * Plugin Name: Questions Bank
 * Description: Allows you to add an unlimited amount of Quizzes to your site.
 * Plugin URI: https://harmonicdesign.ca/hd-quiz/
 * Author: Harmonic Design
 * Author URI: https://harmonicdesign.ca
 * Version: 1.4.2
*/


include(plugin_dir_path(__FILE__) . 'custom-meta.php');
add_image_size('hd_qu_size', 600, 400, true);
add_image_size('hd_qu_size2', 400, 400, true);

// Image upscale if someone uploads an image under 400x400
function hd_qu_image_upscale($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
{
	if (!$crop) return null; // let the wordpress default function handle this

	$aspect_ratio = $orig_w / $orig_h;
	$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

	$crop_w = round($new_w / $size_ratio);
	$crop_h = round($new_h / $size_ratio);

	$s_x = floor(($orig_w - $crop_w) / 2);
	$s_y = floor(($orig_h - $crop_h) / 2);

	return array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);
}

add_filter('image_resize_dimensions', 'hd_qu_image_upscale', 10, 6);


/* Check to see if the page is using Questions Bank
________________________________________________________
-------------------------------------------------------- */

//Disable Canonical redirection for paginated quizzes
function hd_qu_disable_redirect_canonical($redirect_url)
{
	global $post;

	if (has_shortcode($post->post_content, 'HDquiz')) {
		$redirect_url = false;
	}
	return $redirect_url;

}

add_filter('redirect_canonical', 'hd_qu_disable_redirect_canonical');

// load css and js files
//function hd_qu_scripts()
//{
//	global $post;
//	if (has_shortcode($post->post_content, 'HDquiz')) {
//		wp_enqueue_style('style-name', plugin_dir_url(__FILE__) . 'admin.css');
//		wp_enqueue_script(
//			'custom-script',
//			plugins_url('custom.js', __FILE__),
//			array('jquery'),
//			'1.0',
//			true
//		);
//		wp_localize_script('custom-script', 'pluginURL', plugin_dir_url(__FILE__));
//	}
//}
//
//add_action('wp_enqueue_scripts', 'hd_qu_scripts');

//
///* add quiz template
//________________________________________________________
//-------------------------------------------------------- */
//function hd_qu_get_cp_template($single_template)
//{
//	global $post;
//
//	if ($post->post_type == 'post_type_question') {
//		$single_template = dirname(__FILE__) . '/template.php';
//	}
//	return $single_template;
//}
//
//add_filter('single_template', 'hd_qu_get_cp_template');
//


