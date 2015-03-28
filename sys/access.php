<?php


function get_arpt_roles( $name = null ){
	global $arpt_roles;

	if( is_null( $name ) )
		return $arpt_roles;

	if( role_exists( $name ) )
		return $arpt_roles[$name];
	return false;
}
/**
  * A summary informing the user what the associated element does.
  *
  * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
  * and to provide some background information or textual references.
  *
  * @param string $myArgument With a *description* of this argument, these may also
  *    span multiple lines.
  *
  * Test...
  * @param string $myArgument With a *description* of this argument, these may also
  *    span multiple lines.
  *
  * @return array|false This function return mixed
  * But may return other things
  *
  * Like that
  */
function parseArray_get_roles(){
	global $arpt_roles;

	$return = array();

	foreach( $arpt_roles as $role  => $access ) :
		$return[$role] = $role;
	endforeach;

	return $return;
}
function create_new_role( $name ){
	global $arpt_roles;

	if( role_exists( $name ) ) return false;

	$arpt_roles[$name][] = 'no-access-yet';
	return true;
}


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

function role_exists( $name ){
	global $arpt_roles;
	if( isset( $arpt_roles[$name] ) )
		return true;
	return false;
}

function add_user_a_role( $id , $rolename ){
	if( role_exists( $rolename ) && user_exists( $id ) ) :
		update_userproperty( $id , 'user_role' , $rolename );
		return true;
	endif;
	return false;
}
function get_userrole( $id ){
	if( !user_exists( $id ) ) return false;
	return get_userproperty( $id , 'user_role' );
}

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
function currentusercan( $access ){
	if( is_user_online() )
		return usercan( get_currentuserid() , $access );
	return false;
}

function get_arpt_pages( $page = null ){
	global $arpt_pages;

	if( is_null( $page ) )
		return $arpt_pages;
	if( !isset( $arpt_pages[$page] ) )
		return false;

	return $arpt_pages[$page];
}

function restriction_exists( $page , $restr ){
	$p = get_arpt_pages( $page );
	
	if( empty( $p ) ) return false;

	if( $restr == $p[$page]  )
		return true;
	return false;
}



function add_restriction( $page , $access ){
	global $arpt_pages;

	if( is_array( $page ) )
		$page = implode( '/' , $page );

	if( restriction_exists( $page , $access ) ) return false;

	$arpt_pages[$page] = $access;

	return true;
}

function get_restriction( $page ){
	return get_arpt_pages( $page );
}
