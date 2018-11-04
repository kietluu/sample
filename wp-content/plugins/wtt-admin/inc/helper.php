<?php

if (!function_exists('getCurrentUserRole')) {
	function getCurrentUserRole()
	{
		if (is_user_logged_in()) {
			$user = wp_get_current_user();
			$role = ( array )$user->roles;
			
			return $role[0];
		}
		
		return null;
	}
}

if (!function_exists('isWriter')) {
	function isWriter()
	{
		$userRole = getCurrentUserRole();
		return $userRole && $userRole == 'writer';
	}
}

if (!function_exists('isEditor')) {
	function isEditor()
	{
		$userRole = getCurrentUserRole();
		return $userRole && $userRole == 'editor';
	}
}

if (!function_exists('isAdmin')) {
	function isAdmin()
	{
		$userRole = getCurrentUserRole();
		return $userRole && $userRole == 'admin';
	}
}

if (!function_exists('statusBeAbleToReview')) {
	function statusBeAbleToReview($postStatus)
	{
		
		return !in_array($postStatus, ['unpublish', 'publish', 'future', 'trash', 'auto-draft', 'pending']);
	}
}

if (!function_exists('userCanReviewPost')) {
	
	
	function userCanReviewPost($post) {
		
		if (isWriter() && !in_array($post->post_status, array('draft','auto-draft'))) return false;
		
		
		
		if (isAdmin()) return false;
		
		if (isEditor() && !in_array($post->post_status, ['editing', 'draft']) ) return false;
		
		
		// After editor reject article, they can not update it
//		if (isEditor() && $post->post_status == 'draft' && !currentUserIsOwnedPost($post)) return false;
//
		// After content manager reject this article , they can not update it
//		if (isManagerContent() && $post->post_status == 'editing' && !currentUserIsOwnedPost($post) ) return false;
		
		return true;
	}
}



