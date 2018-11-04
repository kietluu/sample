<?php

function wttCaptureCreatedByToPost($postId)
{
	// If this is a revision, don't capture
	if (wp_is_post_revision($postId)) return;
	$createdBy = get_post_meta($postId, 'wtt_post_created_by', true);
	if (!$createdBy) {
		add_post_meta(
			$postId,
			'wtt_post_created_by',
			get_current_user_id()
		);
	}
}

function wttCapturePublishedByToPost($ID, $post)
{
	update_post_meta(
		$ID,
		'wtt_post_published_by',
		get_current_user_id()
	);
}

add_action('save_post', 'wttCaptureCreatedByToPost');
add_action('publish_post', 'wttCapturePublishedByToPost', 10, 2);

/**
 * End Capture new data  : publish_by, created_by to show in Posts Grid
 */


function customDisplayColumns($column, $postId)
{
	
	switch ($column) {
		case 'published_by':
			$publishedById = get_post_meta($postId, 'wtt_post_published_by', true);
			$user = \get_user_by('ID', $publishedById);
			echo $user ? $user->user_nicename : '';
			break;
		
		case 'created_by':
			$createdById = get_post_meta($postId, 'wtt_post_created_by', true);
			$user = \get_user_by('ID', $createdById);
			echo $user ? $user->user_nicename : '';
			break;
		case 'post_status' :
			$post = get_post($postId);
			echo ucfirst($post->post_status);
			break;
		case 'question_level' :
			$level = get_post_meta($postId, 'meta-box-question-level', true);
			echo $level ? $level : '';
			break;
	}
}

$customColumns = [
	'published_by' => 'Publish By',
	'post_status'  => 'Status',
	'question_level'  => 'Level',
];

/* Add custom column to post list */
function addCustomColumn($columns)
{
	global $customColumns;
	
	foreach ($customColumns as $columnKey => $columnName) {
		$columns = array_merge($columns,
			array($columnKey => __($columnName)));
	}
	return $columns;
}

add_filter('manage_posts_columns', 'addCustomColumn');
add_action('manage_posts_custom_column', 'customDisplayColumns', 10, 2);