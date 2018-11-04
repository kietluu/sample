<?php
/**
 * Limit role
 *
 * @param $all_roles
 *
 * @return mixed
 */
function my_editable_roles($all_roles) {

	$filteredRoles = ['writer', 'editor', 'admin'];

	if (current_user_can('administrator'))
		$filteredRoles[] = 'administrator';

	foreach ($all_roles as $role => $value)
	{
		if (!in_array($role, $filteredRoles))
			unset($all_roles[$role]);
	}

	return $all_roles;
}
add_filter('editable_roles', 'my_editable_roles');