<?php
add_action('restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts');
/**
 * First create the dropdown
 * make sure to change POST_TYPE to the name of your custom post type
 *
 * @author Ohad Raz
 *
 * @return void
 */
function wpse45436_admin_posts_filter_restrict_manage_posts()
{
	$type = 'post';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	
	//only add filter to post type you want
	if ('post_type_question' == $type) {
		//change this to the list of values you want to show
		//in 'label' => 'value' format
		?>
		<select name="level-question">
			<option value=""><?php _e('Filter By Level', 'wose45436'); ?></option>
			<?php
			$current_v = isset($_GET['level-question']) ? $_GET['level-question'] : '';
			for($i = 1; $i <= 10; $i++){
				printf
				('<option value="%s"%s>%s</option>',$i,$i == $current_v ? ' selected="selected"' : '',$i);
			}
			?>
		</select>
		<?php
	}
}


add_filter('parse_query', 'wpse45436_posts_filter');
/**
 * if submitted filter by post meta
 *
 * make sure to change META_KEY to the actual meta key
 * and POST_TYPE to the name of your custom post type
 * @author Ohad Raz
 * @param  (wp_query object) $query
 *
 * @return Void
 */
function wpse45436_posts_filter($query)
{
	global $pagenow;
	$type = 'post';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	if ('post_type_question' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['level-question']) && $_GET['level-question'] != '') {
		$query->query_vars['meta_key']   = 'meta-box-question-level';
		$query->query_vars['meta_value'] = $_GET['level-question'];
	}
}