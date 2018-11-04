<?php

function custom_post_status()
{
	register_post_status('editing', array(
		'label'                     => _x('Unread', 'post_type_question'),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop('Editing <span class="count">(%s)</span>', 'Editing <span class="count">(%s)</span>'),
	));
	register_post_status('used', array(
		'label'                     => _x('Used', 'post_type_question'),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop('Used <span class="count">(%s)</span>', 'Used <span class="count">(%s)</span>'),
	));
}

add_action('init', 'custom_post_status');

function filter_list_post($query)
{
	global $user_ID;
	
	if (is_admin()) {
		if (isWriter()) {
			$query->set('post_status', array('auto-draft', 'draft', 'trash'));
			$query->set('author', $user_ID);
			return;
		}
		if (isEditor()) {
			$query->set('post_status', array('editing', 'pending', 'draft'));
			return;
		}
	}
}

function updatePostStatus($postId, $postStatus)
{
	wp_update_post(array(
		'ID'          => $postId,
		'post_status' => $postStatus
	));
}

function wttSendToReviewPost($postId, $post, $update)
{
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'trash') return;
	if($update == false) return;
	if (wp_is_post_revision($postId)) return;
	if (wp_is_post_autosave($postId)) return;
	if (!empty($_POST['save']) && $_POST['save'] == 'Save Draft') return;
	
//	if (!statusBeAbleToReview($post)) return;
	if (userCanReviewPost($post)) return;
	
	
	remove_action('save_post', 'wttSendToReviewPost',1);
	
	$userRole = getCurrentUserRole();
	
	switch ($userRole) {
		case 'editor' :
			updatePostStatus($_POST['post_ID'], 'pending');
			break;
		case 'writer':
			if (in_array($_POST['original_post_status'], array('draft', 'auto-draft')))
				updatePostStatus($_POST['post_ID'], 'editing');
			break;
	}
	add_action('save_post', 'wttSendToReviewPost', 1, 3 );
}

function statusBeAbleToReject($postStatus)
{
	return !in_array($postStatus, ['unpublish', 'publish', 'future', 'trash', 'auto-draft', 'draft']);
}

function userCanRejectPost($post)
{
	if (isWriter()) return false;
//
//		if (isEditor() && in_array($post->post_status, ['pending'])) return false;
//
//		if ( currentUserIsOwnedPost($post) && ! (isAdmin() || isContentAdmin())) return false;
//
//		if ( (isAdmin() || isContentAdmin()) && $post->post_status == 'editing') return false;//already rejected before.
//
	return true;
	
}

function addRejectButton($post)
{
	
	if (!statusBeAbleToReject($post->post_status)) return;
	if (!userCanRejectPost($post)) return;
	
	$html = '<div id="major-publishing-actions" style="overflow:hidden">';
	$html .= '<div id="publishing-action">';
	$html .= '<input type="button" accesskey="p" tabindex="5" value="Reject" class="button-primary" id="reject-post" name="reject" style="margin-right: 12px;">';

//	if (!isEditor() || !isEditor()) {
//		$html .= '<input type="button" accesskey="p" tabindex="5" value="Reject to Writer" class="button-primary" id="reject-post-to-writer" name="reject-post-to-writer">';
//	}
	$html .= '</div>';
	$html .= '</div>';
	
	echo $html;
}

function updatePostRejectReason($postId, $reason)
{
	update_post_meta(
		$postId,
		'wtt_post_reject_reason',
		$reason
	);
}

function wttRejectPost($postId)
{
	if (wp_is_post_revision($postId)) return;
	$post = get_post($postId);
	if(empty($_POST['original_post_status'])) return;
	if (!statusBeAbleToReject($_POST['original_post_status'])) return;
	
	if (!userCanRejectPost($post)) return;
	
	$reason = isset($_POST['reason']) ? $_POST['reason'] : false;
	
	unset($_POST['reason']);
	
	if ($reason) {
		
		$userRole = getCurrentUserRole();
		
		updatePostRejectReason($_POST['post_ID'], $reason);
		
		remove_action('save_post','wttRejectPost');
		if (!empty($_POST['reject-post-to-writer']) && $_POST['reject-post-to-writer'] == '1') {
			updatePostStatus($_POST['post_ID'], 'draft');
		} else {
			switch ($userRole) {
				case 'editor' :
					updatePostStatus($_POST['post_ID'], 'draft');
					break;
				default:
					updatePostStatus($_POST['post_ID'], 'editing');
					break;
			}
		}
		add_action('save_post','wttRejectPost');
	}
}

function customStatusWorkflow()
{
//	add_action('admin_print_styles-post.php',     'hideActionUsingCss');
//	add_action('admin_print_styles-post-new.php', 'hideActionUsingCss');

//	global $pagenow;
//
//	if (getPostType() != 'post') return;
//	if ($pagenow != 'post.php') return;


//	add_action('save_post', 'wttUnPublishPost');
//	add_action('save_post', 'wttSendBackToUpdatePost');

//	add_action( 'post_submitbox_misc_actions', 'addSendToReviewButton' );
//	add_action( 'post_submitbox_misc_actions', 'addUnPublishButton' );
//	add_action( 'post_submitbox_misc_actions', 'addSendBackButton' );
	
	add_action('save_post', 'wttRejectPost');
	add_action('save_post', 'wttSendToReviewPost', 1, 3 );
	add_action('post_submitbox_misc_actions', 'addRejectButton');
	
	
}

function my_post_submitbox_misc_actions()
{
	global $post;
	//only when editing a post
	if ($post->post_type == 'post_type_question') {
		
		if ($post->post_status == 'editing') {
			echo "<script>";
			echo "jQuery('#post-status-display').text(' Editing');";
			echo "jQuery('#save-post').hide();";
			echo "</script>";
		}elseif($post->post_status == 'used'){
			echo "<script>";
			echo "jQuery('#post-status-display').text(' Used');";
			echo "</script>";
		}
	}
}

function restricts()
{
	if (isset($_GET['action']) && in_array($_GET['action'],['delete','untrash'])){
		return;
	}
	
	if (isset($_GET['post']))
		$post_id = $post_ID = (int)$_GET['post'];
	elseif (isset($_POST['post_ID']))
		$post_id = $post_ID = (int)$_POST['post_ID'];
	else
		$post_id = $post_ID = 0;
	
	if ($post_id)
		$post = get_post($post_id);
	
	if ($post) {
		//restrict Writer
		if (isWriter() && !in_array($post->post_status, array('draft', 'auto-draft'))) {
			wp_redirect('edit.php?post_type=post_type_question');
			exit();
		}
		//restrict Editor
		if (isEditor() && $post->post_status == 'pending') {
			setcookie('wtt-notice-warning', 'You cannot edit this post!', 0, COOKIEPATH, COOKIE_DOMAIN);
			wp_redirect('edit.php?post_type=post_type_question');
			exit();
		}
		
		//
	}
}

function wtt_admin_notice_warning()
{
	if (isset($_COOKIE['wtt-notice-warning'])) {
		$msg = $_COOKIE['wtt-notice-warning'];
		unset($_COOKIE['wtt-notice-warning']);
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo $msg; ?></p>
		</div>
		<?php
	}
}

add_filter( 'post_row_actions', 'my_disable_quick_edit', 10, 2 );
add_filter( 'page_row_actions', 'my_disable_quick_edit', 10, 2 );

function my_disable_quick_edit( $actions = array(), $post = null ) {
	
	// Remove the Quick Edit link
	if ( isset( $actions['inline hide-if-no-js'] ) ) {
		unset( $actions['inline hide-if-no-js'] );
	}
	
	// Return the set of links without Quick Edit
	return $actions;
	
}


add_action('init', 'customStatusWorkflow');
add_action('pre_get_posts', 'filter_list_post', 1);
add_action('post_submitbox_misc_actions', 'my_post_submitbox_misc_actions');
add_action('load-post.php', 'restricts');
//add_action('admin_notices', 'wtt_admin_notice_warning');
