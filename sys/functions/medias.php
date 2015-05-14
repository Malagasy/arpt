<?php

function upload_miniature( $cid , $file , $maxsize = null ){
	
	if( ( $ext = get_image_extension( $file ) ) === false ) return false;

	if( !file_exists( get_upload_dir() . '/.miniature' ) )
		mkdir( get_upload_dir() . '/.miniature' );

 	return copy( $file ,  get_upload_dir() . '/.miniature/miniature-' . get_contentslug( $cid ) . '.' . $ext );

}

function get_image_extension( $file ){

	if( !is_image( $file ) ) return false;

	$img = getimagesize( $file );

	switch ( $img['mime'] ) {
		case 'image/gif':
			return 'gif';
			break;
		
		case 'image/jpeg':
			return 'jpeg';
			break;

		case 'image/png':
			return 'png';
			break;

		case 'image/tiff':
			return 'tiff';
			break;

		case 'image/x-icon':
			return 'ico';
			break;
	}

	return false;
}
function add_format( $name , $width , $height ){
	global $format_miniatures;

	$format_miniatures[$name] = array( 'width' => $width , 'height' => $height );
}

function get_format( $name ){
	global $format_miniatures;
	if( isset( $format_miniatures[$name] ) )
		return $format_miniatures[$name];
	return;
}
function has_miniature( $type = 'content' , $id = null ){
	if( get_miniature( $type , $id ) == null )
		return false;
	return true;
}

function rm_miniature( $cid ){
	$c = new_content( $cid );
	$c->qnext();
	return unlink( $c->qminiature() );
}

function get_miniature( $type = 'content' , $id = null ){

	if( $id == null )
		if( is_queriablepage() )
			$id = qid();
		else
			return;

	if( $type == 'content' ) :
		$path = get_upload_dir() . '/.miniature/miniature-' . get_contentslug( $id );
	
		if( $miniature = glob( $path . '.*') )
			return $miniature[0];

	endif;
	return;

}

function upload_medias( $files , $parentdir){
	foreach( $files as $file ) :
		if( $file['size'] > maxsize_upload_files() ) continue;
		move_uploaded_file( $file['tmp_name'] ,  get_upload_dir() . $parentdir . date("omd-Gi") . '-' . $file['name'] );
	endforeach;
	return true;
}
function js_rename_media( $folder , $name , $old_name ){
	if( $name == $old_name ) return;

	if( !file_exists( $folder . $old_name ) ) return ;

	echo rename( $folder . $old_name , $folder . $name );
}

function js_delete_media( $filename ){
	echo unlink( $filename );
}
