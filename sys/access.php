<?php

/*
	** Get datas of the different roles and their accesses.

	** Params : ( optional ) String, name of a role

	** Return Mixed 	- (Array) datas of all roles or datas of specified role ( if first param given ). 
						- (Boolean) 'false' if the specified role doesn't exist.
onfig.php - 33
Array
(
    [Administrateur] => Array
        (
            [0] => view-backend
            [1] => manage-settings
            [2] => manage-contents
            [3] => manage-options
            [4] => manage-users
        )

    [Modérateur] => Array
        (
            [0] => view-backend
            [1] => manage-contents
        )

*/

function get_arpt_roles( $name = null ){
	global $arpt_roles;

	if( is_null( $name ) )
		return $arpt_roles;

	if( role_exists( $name ) )
		return $arpt_roles[$name];
	return false;
}

function parseArray_get_roles(){
	global $arpt_roles;

	$return = array();

	foreach( $arpt_roles as $role  => $access ) :
		$return[$role] = $role;
	endforeach;

	return $return;
}

/*
	** Create a new role, first the role hasn't specifics access. You need to give it access to make it specifics ( using add_access_to_role() function )

	** Params : String, the name of the role

	** Return Boolean : 'true' if function worked well, 'else' if the role already exists.

*/
function create_new_role( $name ){
	global $arpt_roles;

	if( role_exists( $name ) ) return false;

	$arpt_roles[$name][] = 'no-access-yet';
	return true;
}


/*
	** Create access to an existing role.

	** Params : - String, the name of the existing role
				- String, the name of the access

	** Return Boolean : 'true' if function worked well, 'else' if the role doesn't exist.

*/

function add_access_to_role( $rolename , $accessname ){
	global $arpt_roles;

	if( !role_exists( $rolename ) ) return false;

	if( $arpt_roles[$rolename][0] == 'no-access-yet' ) :
		$arpt_roles[$rolename][0] = $accessname;
	else :
		if( !in_array( $accessname , $arpt_roles[$rolename] ) )
			$arpt_roles[$rolename][] = $accessname;
	endif;

	return true;
}

/*
	** Check if a role exists

	** Params : String, the name of the role

	** Return Boolean : 'true' if role exists, 'false' if not.

*/

function role_exists( $name ){
	global $arpt_roles;
	if( isset( $arpt_roles[$name] ) )
		return true;
	return false;
}

/*
	** Give a role to an existing user.

	** Params : - Int, ID of an existing user.
				- String, name of an existing user.

	** Return Boolean, 'true' if function worked well, 'false' if user doesn't exist or roles doesn't exist.

*/

function add_user_a_role( $id , $rolename ){
	if( role_exists( $rolename ) && user_exists( $id ) ) :
		update_userproperty( $id , 'user_role' , $rolename );
		return true;
	endif;
	return false;
}

/*
	** Get the role of an existing user.

	** Params : Int, ID of an existing user.

	** Return Mixed : 	- (String) the role of a user.
						- (Boolean) 'false' if user hasn't role or if user doesn't exist.

*/

function get_userrole( $id ){
	if( !user_exists( $id ) ) return false;
	return get_userproperty( $id , 'user_role' );
}

/*
	** Check if user has an access

	** Params :	- Int, ID of an existing user.
				- String, the name of the access.

	** Return Boolean : 'true' if user has the access, 'false' if the user doesn't exist or he hasn't access.
*/

function usercan( $id , $access ){
	$role = get_userrole( $id );

	if( !role_exists( $role ) ) return false;

	global $arpt_roles;
	if( is_array( $access ) )
		foreach( $access as $acc ) : 
			if( in_array( $access , $arpt_roles[$role] ) )
				return true;
		endforeach;
	else
		if( in_array( $access , $arpt_roles[$role] ) )
				return true;
	return false;

}

/*
	** Check if the current user ( means logged ) has an access

	** Params : String, the name of the access.

	** Return Boolean : 'true' if the current user has the access, 'false' if he hasn't or if nobody is online yet.

*/

function currentusercan( $access ){
	if( is_user_online() )
		return usercan( get_currentuserid() , $access );
	return false;
}

/*
	** Get datas' restriction of pages ( or a specified page ). Please, instead of this, use get_restrictions() function which is its alias.

	** Params : ( optional ) String, path of the page ( excluding HOST url and BASE url) 

	** Return Mixed :	- (Array) datas' restrictions of pages (or a specified page, check Params:1 )
						- (Boolean) 'false' if page hasn't restrictions if Params:1 is specified.

*/

function get_arpt_pages( $page = null ){
	global $arpt_pages;

	if( is_null( $page ) )
		return $arpt_pages;
	if( !isset( $arpt_pages[$page] ) )
		return false;

	return $arpt_pages[$page];
}

/*
	** Check if a page has a specific restriction

	** Params : - String, the path of the page
				- String, the name of the restriction

	** Return Boolean : 'true' if page has the specified restriction, 'false' if not.

*/
function restriction_exists( $page , $restr ){
	$p = get_arpt_pages( $page );
	
	if( empty( $p ) ) return false;

	if( $restr == $p[$page]  )
		return true;
	return false;
}


/*
	** Add a restriction to a page

	** Params : - String, the path of the page
				- String, the name of the restriction( = access) 

	** Return Boolean : 'true' if function worked well, 'false' if restriction already exists on the page.

*/

function add_restriction( $page , $access ){
	global $arpt_pages;

	if( is_array( $page ) )
		$page = implode( '/' , $page );

	if( restriction_exists( $page , $access ) ) return false;

	$arpt_pages[$page] = $access;

	return true;
}
/*
	** Get datas' restriction of a specified page.

	** Params : String, path of the page ( excluding HOST url and BASE url) 

	** Return Mixed :	- (Array) datas' restrictions of specified page.
						- (Boolean) 'false' if page hasn't restrictions.
*/

function get_restriction( $page ){
	return get_arpt_pages( $page );
/*
	if( empty( $p ) ) return null;

	return $p;*/
}
