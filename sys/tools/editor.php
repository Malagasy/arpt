<?php

function editor_get_files_inside( $folder ){
	$files = scandir( $folder );

	$exts = get_allowed_extension();

	$files_2 = array();

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
		if( is_dir( $folder . '/' . $file ) )
			echo '<a href="#" data-type="folder" data-path="'.$file.'" class="list-group-item"><strong>' . $file . '</strong></a>';
		else
			echo '<a href="#" data-type="file" data-path="'.$file.'" class="list-group-item">' . $file . '</a>';

	}
	die();
}

function get_allowed_extension( $arg = array() ){
	$default = array( 'json' , 'js' , 'css' , 'php' , 'php4' , 'php5' , 'phtml' , 'html' , 'htm' , 'xml' , 'sql' , 'txt' , 'log' );
	$ext = array_merge( $default , $arg );
	return call_layers('editor_allowed_extension_layer' , $ext );
}

function editor_display_file_code( $path ){
	$code = file_get_contents( $path );

	if( !Arpt::is_ajaxcall() ) return $code;

	$code = form_textarea( array( 'name' => 'file_code' , 'spellcheck' => 'false' , 'value' => $code , 'class' => 'editor-textarea form-control' ) );;
	die( $code );
}

function editor_register_file( $path , $content ){
	if( !Arpt::is_ajaxcall() ){
		if( !file_exists( $path ) ) return false;

		return file_put_contents($path, $content);
	}
	logr( ( $content ) );
	if( !file_exists( $path ) ) die("filenotexists");
	if( file_put_contents($path, ( preg_quote( $content ) ) ) === false ) die( "fails");
	die("filechanged");
}