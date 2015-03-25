<?php
function get_category_by_type( $value , $what = '*' ){
	return get_category_by( 'content_type' , $value , $what );
}

function get_category_by_id( $value , $what = '*' ){
	return get_category_by( 'id' , $value , $what );
}
function get_category_by_name( $value , $what = '*' ){
	return get_category_by( 'name' , $value , $what );
}

function get_catid_by_name( $value , $what = '*' ){
	$r = get_category_by_name( $value , $what );
	
	if( $r === false ) return false;

	if( $r->next() )
		return $r->qdatas()->id;
}

function get_currentcategorypage(){
	if( is_categorypage() )
		return get_pagetype();
	return false;
}

function category_exists( $id ){
	if( is_number( $id ) ) :
		if( get_category_by_id( $id ) === false )
			return false;
		return true;
	else :
		if( get_category_by_name( $id ) === false )
			return false;
		return true;
	endif;
}
