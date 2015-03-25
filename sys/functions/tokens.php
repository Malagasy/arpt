<?php


function create_token( $name , $userid = null ){
	$v = md5( date("l jS \of F Y h:i A") ); 
	// en attendant de trouver mieux, on récupère l'IP de l'user courant pour l'identifier
	if( $userid === null )
		$userid = get_currentuserid();

	$tokens = unserialize( get_userproperty( $userid , 'tokens' ) );

	if( $userid ){
		unset( $tokens[$name] );
		$tokens[$name] = $v;
	}else{
		unset( $tokens[$_SERVER['REMOTE_ADDR']][$name] );
		$tokens[$_SERVER['REMOTE_ADDR']][$name] = $v;
	}

	update_userproperty( $userid , 'tokens' , serialize( $tokens ) );


	
	return $v;
}

function check_token( $name , $value , $userid = null , $delete = true ){

	$userid = ( is_null( $userid ) ) ? get_currentuserid() : $userid;

	$v = unserialize( get_userproperty( $userid , 'tokens' ) );

	if( $userid ){
		if( $v[$name] != $value ) return false;
	}else{
		if( $v[$_SERVER['REMOTE_ADDR']][$name] != $value ) return false;
	}
	
	if( $delete ) delete_token( $name , $value , $userid );
	
	return true;

}

function delete_token( $name , $value = null , $userid = null ){
	$userid = ( is_null( $userid ) ) ? get_currentuserid() : $userid;

	$r = unserialize( get_userproperty( $userid , 'tokens' ) );

	foreach( $r as $k => $v ){
		if( $value === null ){
			if( $k == $name ) unset( $r[$k] );
		}else{
			if( $k == $name && $v == $value ) unset( $r[$k] );
		}
	}

	if( $userid ){
		if( $value === null ){
			unset( $r[$name] );
		}else{
			if( $r[$name] == $value ) unset( $r[$name] );
		}
	}else{
		if( $value === null ){
			unset( $r[$_SERVER['REMOTE_ADDR']][$name] );
		}else{
			if( $r[$_SERVER['REMOTE_ADDR']][$name] == $value ) unset( $r[$_SERVER['REMOTE_ADDR']][$name] );
		}
	}

	/* some clean are done here when $r is too big */
	if( count( $r ) > 60 ){
		$r = array_slice( $r , 20 );
	}

	return update_userproperty( $userid , 'tokens' , serialize( $r ) );
}

function clean_tokens( $userid = null ){
	if( !is_user_online() ) return false;
	if( is_null( $userid ) ) $userid = get_currentuserid();

	$query['where'] = clause_where( 'parent_id' , '=' , $userid ) . clause_like( 'label' , 'tokens' , ' AND ' );
	new_query( 'delete' , 'arpt_users_properties' , $query );

	return true;
}