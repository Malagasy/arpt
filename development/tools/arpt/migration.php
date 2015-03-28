<?php

function get_prototype_functions(){
	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';
	logr( $base_path . $files[0] );
	$code = file_get_contents( $base_path . $files[0] );
	logr($code);
	$gets = token_get_all( $code );

	logr($gets);

	exit();

}