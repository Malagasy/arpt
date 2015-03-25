<?php

function add_new_content( $name ){
	global $arpt_contents;
	if( !is_active_content( $name ) )	
		$arpt_contents['active_contents'][] = $name;
	else
		return;

	call_triggers( 'new_content_added' , $name );

	//mkdir_uploads_content( $name );
}

function remove_content( $name ){
	global $arpt_contents;
	if( !is_active_content( $name ) ) return false;

	foreach( $arpt_contents['active_contents'] as $k => $v ) :
		if( diffstr( $v , $name ) ) continue;
		unset( $arpt_contents['active_contents'][$k] );
		return true;
	endforeach;
}

function get_active_contents(){
	global $arpt_contents;
	if( !empty( $arpt_contents ) )
		return $arpt_contents['active_contents'];
	return array();
}

function is_active_content( $name ){
	if( in_array( strtolower( $name ) , get_active_contents() ) )
		return true;
	return false;
}

function get_contents( $specificities  ){
	return new_content( $specificities , 'select' ); 
}
/* use ID or slug(as array => slug) */
function get_contentinfo( $id , $info = '*' ){

	if( is_number( $id ) ) $spec['id'] = $id;
	else $spec['slug'] = $id;

	if( is_array( $info ) )
		$info = implode( ',' , $info );

	$spec['selection'] = $info;
//	logr($spec);
//
	$type = get_contents( $spec ); 
	//($type);
	//
	//$type = new_query( 'select' , 'arpt_contents' , array( 'where' => $label . '=\'' . $id . '\'' , 'selection' =>  $info ) );
	return $type;
}

function delete_content( $id ){
	if( !content_exists( $id ) ) return false;

	call_triggers( 'before_delete_content' , $id );

	clean_navmenu_from_content( $id );

	new Contents( $id , 'delete' );
	new Queries( 'delete' , 'arpt_contents_properties' , array( 'where' => clause_where( 'parent_id' , '=' , $id ) ) );

	call_triggers( 'after_delete_content' , $id );

	return true;
}

function clean_navmenu_from_content( $cid ){
	$content = get_contents( $cid );
	$content->next();

	$type = $content->qtype();

	foreach( get_navmenus() as $navmenu ){
		if( diffstr( $navmenu['contenttype'] , $type ) ) continue;
		$list = get_navmenu_links( $navmenu['id'] );
		if( ( $id_for_delete = array_search( $cid , $list ) ) === false ) continue;
		unset( $list[$id_for_delete] );
		
		set_option( 'navmenu_'.$navmenu['id'] , serialize( $list ) );
	}
}

function content_link( $id ){
	$infos = get_contentinfo( $id );
	$infos->next(); //
//	
	if( $catid = get_content_category( $id ) ) :
		$slugtype = get_category_by_id( $catid , 'name');
	else :
		if( get_option('hide_content_type_on_url') )
			$slugtype = '';
		else
			$slugtype = $infos->qtype();
	endif;
	$link = get_url( strtolower( $slugtype ) . '/' . $infos->qslug() );


	return call_layers( 'content_link_layer' , $link , $id );
}

function content_exists( $id ){
	$content = new_content( array( 'id' => $id , 'status' => 'all' ) );
	if( $content->has() ) return true;
	return false;
}

function new_content( $specificity , $action = 'select' ){
	return new Contents( $specificity , $action );
}

function update_commentscount( $id , $pid ){
	$r = get_contentproperty( $pid , 'commentscount' );
	$r += 1;
	update_contentproperty( $pid , 'commentscount' , $r );
}