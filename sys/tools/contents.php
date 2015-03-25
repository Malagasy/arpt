<?php

function get_contenttype( $id = null ){
	if( $id == null )
		if( is_contentpage() || is_categorypage() )
			$id = get_currentcontentid();
		else
			return false;

	$r = get_contentinfo( $id , 'content_type' );
	if( $r->next() )
		return $r->qtype();
	return false;
}
function get_contentid( $slug = null ){
	if( $slug == null && !is_contentpage() && !is_categorypage() ) return false;

	$r = ( $slug == null ) ? get_pageargs(0) : $slug;
	
	if( empty( $r ) ) return false;
	//($r);
	$return = get_contentinfo( $r , 'id' );
	if( $return->next() )
		return $return->qid();
	return false;
}
function get_contentslug( $id = null){
	if( $id == null )
		if( is_contentpage() )
			$id = get_currentcontentid();
		else
			return false;

	$r = get_contentinfo( $id , 'content_slug' );
	if( $r->next() )
		return $r->qslug();
	return false;
}
function get_currentcontentid(){
	if( $qid = qid() )
		return $qid;
	return get_contentid();
}

function get_contentname( $id = null ){

	if( $id == null )
		if( ( is_contentpage() || is_categorypage() ) && get_pageargs(0) )
			$id = get_pageargs(0);
		else
			return false;
		//logr($id);
	
	$return = get_contentinfo( $id , 'content_title' );
	//logr($return);
	//$return->next();
	//logr($return);
	if( $return->next() )
		return $return->qtitle();
	return false;
}
function get_currentcontentname(){
	return get_contentname();
}

function get_contentchilds( $specificities = array() , $pid = null ){
	if( $pid === null ) $pid = qid();
	$b = array_merge( $specificities , array( 'parent_id' => $pid , 'type' => get_contenttype( $pid) ) );
	$r = new_content( $b );
	//logr($r->getFirst());
	//if( !$r->is_null() )
		return $r;
//	return false;
}

function get_content_categoryname( $cid ){
	$catid = get_content_category( $cid );

	if( !$catid ) return false;

	if( $cat = get_category_by_id( $catid , 'name' ) )
		return $cat;
	return false;
}