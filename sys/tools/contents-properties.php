<?php

function insert_contentproperty( $contentid , $label , $value ){
	return insert_new_property( 'contents' , $contentid , $label , $value );
}


function insert_contentsproperties( $datas , $contentid ){
	$r = array();
	
	foreach( $datas as $k => $v )
		$r[] = insert_contentproperty( $contentid , $k , $v );
		
	return $r;
}


function get_contentproperty( $contentid , $label ){
	return get_property( 'contents' , $contentid , $label );
}

function update_contentproperty( $cid , $label , $value ){
	if( get_contentproperty( $cid , $label ) === false )
		return insert_contentproperty( $cid , $label , $value );
	return update_property( 'contents' , $cid , $label , $value );
}

function delete_contentproperty( $pid , $label , $value = null){
	return delete_property( 'contents' , $pid , $label , $value );
}
function update_contentsproperties( $datas , $contentid ){
	$r = array();

	if( !$datas || !$contentid ) return false;

	foreach( $datas as $k => $v )
		$r[] = update_contentproperty( $contentid , $k , $v );
		
	return $r;
}