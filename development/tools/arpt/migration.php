<?php

function get_prototype_functions(){

	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';


	$args = array();
	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

	$code = strtok( file_get_contents( $base_path . $files[0] , "\r\n" ) );

	while( $line !== false ){
		if( preg_match($preg, $line ) )
			$args[] = $line;
		$line = strtok( "\r\n" );
	}

	logr($args);

	exit();
}