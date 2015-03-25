<?php


function get_categories( $specificities = null ){
	return new Categories( $specificities );
}

function get_category_by( $label , $value , $what = '*' ){
	if( $value == '' ) return false;

	$r = get_categories( array( $label => $value , 'selection' => $what ) );

	if( $r->has() ) :
		if( $r->total == 1 )
			if( $what == '*' ) :
				return $r;
			else :
				$r->next();
				return $r->datas->$what;
			endif;
		else
			return $r;
	endif;

	return false;
}


function get_content_category( $cid ){
	$cat = new_query( 'select' , 'arpt_contents_categories' , array( 'selection' => 'cat_id' , 'where' => 'content_id=\''.$cid.'\'' ) );

	if( $cat->next() )
		return $cat->datas->cat_id;
	return false;
}

function get_contentsids_by_categoryid( $catid ){
	if( $catid == '' ) return array();

	$cat = new_query( 'select' , 'arpt_contents_categories' , array( 'selection' => 'content_id' , 'where' => 'cat_id=\''.$catid.'\'' ) );

	if( $cat->has() ) :
		while( $cat->next() )
			$r[] = $cat->qdatas()->content_id;
		return $r;
	endif;
	return array();
}

function insert_content_category( $cid , $catid ){
	if( get_content_category( $cid ) )
		return false;
	return new_query( 'insert' , 'arpt_contents_categories' , array( 'content_id' => $cid , 'cat_id' => $catid ) );
}

function update_content_category( $cid , $catid ){

	if( get_content_category( $cid ) === false )
		return insert_content_category( $cid , $catid );
	return new_query( 'update' , 'arpt_contents_categories' , array( 'set' => 'cat_id=\''.$catid.'\'' , 'where' => 'content_id=\''.$cid.'\'' ) );

}

function delete_category( $id ){
	call_triggers( 'before_delete_category' , $id );

	new Categories( $id , 'delete' );
	new Queries( 'update' , 'arpt_contents_categories' , array( 'set' => clause_where( 'cat_id' , '=' , 0 ) , 'where' => clause_where( 'cat_id' , '=' , $id ) ) );

	return true;

}