<?php


function get_tag_list( $content_id ){
	$r = new_query( 'select' , 'arpt_keywords' , array( 'where' => clause_where( 'content_id' , '=' , $content_id ) , 'selection' => '*' ) );
	return $r->getAll();
}
function display_tag_list( $content_id = null ){
	if( $content_id == null ) 
		if( is_queriablepage() )
			$content_id = qid();
		else
			return false;
	$list = get_tag_list( $content_id );

	if( empty( $list ) ) return false;
	
	foreach( $list as $r )
		$v[$r['id']] = a( get_kw_url() . '/' . do_slug($r['name']) , ucwords( undo_slug( $r['name'] ) ) , array( 'class' => 'btn btn-success btn-xs' ) );
		
	return call_layers( 'display_tag_list_layer' , implode( ' ' , $v ) , $v );
}

function get_ids_by_tag( $tag , $return = 'array' ){
	$r = new_query( 'select' , 'arpt_keywords' , array( 'where'	=> clause_like( 'name' , $tag ) , 'selection' => 'content_id') );
	
	//logr( $r->getAll());
	if( $r->is_null() ) return array();
	foreach( $r->getAll() as $tag )
		$v[] = $tag['content_id'];
		
	if( $return == 'array' ) return array_unique( $v );
	elseif( $return == 'string' ) return quote( implode( '\', \'' , $v ) );
}