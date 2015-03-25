<?php

function editor_get_files_inside( $folder ){
	$files = scandir( $folder );

	$exts = get_allowed_extension();

	$files_2 = array();

	logr( $files );

	foreach( $files as $file ){
		if( $file[0] == '.' ) continue;
		if( is_dir( $folder  . '/' . $file ) ){
			$files_2[] = $file;
		}else{
			if( in_array( file_extension( $file ) , $exts ) )
				$files_2[] = $file;
		}
	}

	if( !Arpt::is_ajaxcall() ) return $files_2;

	foreach( $files_2 as $file ){
		if( is_dir( $file ) )
			echo '<a href="#" data-type="folder" data-path="'.$file.'" class="list-group-item"><strong>' . $file . '</strong></a>';
		else
			echo '<a href="#" data-type="file" data-path="'.$file.'" class="list-group-item">' . $file . '</a>';

	}
}

function get_allowed_extension( $arg = array() ){
	$default = array( 'json' , 'js' , 'css' , 'php' , 'php4' , 'php5' , 'phtml' , 'html' , 'htm' , 'xml' , 'sql' , 'txt' , 'log' );
	$ext = array_merge( $default , $arg );
	return call_layers('editor_allowed_extension_layer' , $ext );
}