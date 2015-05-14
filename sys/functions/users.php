<?php

function new_user( $specificities = null , $action = 'select' ){
	return new Users( $specificities , $action );
}

function get_userinfo( $id , $selection = '*' ){
	if( is_number( $id ) ) $r['id'] = $id;
	else $r['slug'] = $id;

	$r['selection'] = $selection;

	$user_info = new_user( $r );

	if( $user_info->next() ) :
		if( $selection == '*')
			return $user_info->qdatas();
		else
			return $user_info->datas->{$selection};
	else :
		return false;
	endif;

}

function insert_new_user( $datas ){

	$user_exists = new_user( array( 'name' => $datas['name'] ) );
	if( $user_exists->has() ) return false;

	$default = array(
		'name' 	=> null,
		'slug'	=> null,
		'pass'		=> null,
		'email'		=> null,
		'date_registered' => null,
		'status'	=> 0);
	$datas = array_merge( $default , $datas );
	
	$user['username'] = $datas['name'];
	$user['slug'] = db_uniq_slug( 'arpt_users' , 'slug' , $datas['name'] );
	$user['pass'] = pwd_crypt( $datas['pass'] );
	$user['email'] = $datas['email'];
	$user['date_registered'] = date("Y-m-d H:i:s");
	$user['status'] = $datas['status'];

	$user = call_layers( 'before_inserting_user' , $user );

	$new_user = new_query( 'insert' , 'arpt_users' , $user );

	$user['id'] = $new_user->get();
	
	return $user;
}

function update_user( $datas , $userid ){
	if( !user_exists( $userid ) ) return insert_new_user( $datas );

	foreach( $datas as $k => $v ) :
		update_userinfo( $userid , $k , $v );
	endforeach;

	return true;
}

function delete_user( $id ){
	if( !user_exists( $id ) ) return false;

	call_triggers( 'before_delete_user' , $id );

	new Users( $id , 'delete' );
	new Queries( 'delete' , 'arpt_users_properties' , array( 'where' => clause_where( 'parent_id' , '=' , $id ) ) );

	return true;
}

function authenticate_user( $name , $password ){

	$crypted_pwd = pwd_crypt( $password );

	if( is_email( $name ) ) :
		$query = new_user( array( 'selection' => 'id' , 'email' => 'STRICT-'.$name ) );
		if( !$query->next() ) return false;

		$related_id_name = $query->qid();
	else :
		$query = new_user( array( 'selection' => 'id' , 'name' => 'STRICT-'.$name ) );
		if( !$query->next() ) return false;

		$related_id_name = $query->qid();
	endif;

	$user_datas = $query->qdatas();

	$query = new_user( array( 'selection' => 'pass' , 'id' => $related_id_name ) );
	$query->next();
	$found_pwd = $query->qpwd();


	if( $found_pwd ==  $crypted_pwd )
		return $user_datas;
	return false;

/*	$user = new_query( 'select' , 'arpt_users', array( 'where' => '( username=\'' . sanitize_str( $name ). '\' AND pass=\'' . md5( sanitize_str( $password ) , true ) . '\' ) OR ( email=\'' . sanitize_str( $name ) . '\' AND pass=\'' . md5( sanitize_str( $password ) , true ) . '\' )' ) );
	
	if( $user->next() )
		return $user->qdatas();
	return false;*/
}

function sessionning_user( $user ){
	if( is_array( $user ) )
		$user = (object)$user;

	unset( $user->pass );
	$_SESSION['user'] = $user;
	return true;
}

function is_user_online(){
	if( get_currentuserid() )
		return true;
	return false;
}
function get_currentuserid(){
	if( isset( $_SESSION['user'] ) )
		return call_layers( 'get_currentuserid_layer' , $_SESSION['user']->id );
	return 0;
}

function get_currentusername(){
	if( isset( $_SESSION['user'] ) )
		return call_layers( 'get_currentusername_layer' , $_SESSION['user']->username );
	return false;
}

function get_currentuser(){
	global $arpt;
	return $arpt->get_currentuser();
}

function user_exists( $id ){
	$user = new_user( $id );

	if( $user->next() )
		return $user;
	return false;
}

function update_userinfo( $userid , $info , $value ){
	return new_user( array( 'id' => $userid , $info => $value ) , 'update' );
}


/*
	** Check if given user is an administrator

	** Params : ( optional ) Int, ID of an existing user.
			If ID isn't given, function will try to get the ID of the current user.

	** Return Boolean : 'true' if user is admin, 'false' if not.
*/

function is_user_admin( $id = null ){
	if( is_null( $id ) )
		if( is_user_online() )
			$id = get_currentuserid();
		else
			return false;


	if( get_userrole( $id ) == 'Administrateur' )
		return true;
	return false;
}