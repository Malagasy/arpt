<?php

function insert_userproperty( $pid , $label , $value ){
	return insert_new_property( 'users' , $pid , $label , $value );
}


function insert_usersproperties( $datas , $userid ){
	$r = array();
	
	foreach( $datas as $k => $v )
		$r[] = insert_userproperty( $userid , $k , $v );
		
	return $r;
}

function get_userproperty( $pid , $label ){
	return get_property( 'users' , $pid , $label );
}


function update_userproperty( $pid , $label , $value ){
	return update_property( 'users' , $pid , $label , $value );
}

function delete_userproperty( $pid , $label , $value = null){
	return delete_property( 'users' , $pid , $label , $value );
}

function update_usersproperties( $datas , $userid ){
	$r = array();

	foreach( $datas as $k => $v )
		$r[] = update_userproperty( $userid , $k , $v );
		
	return $r;
}