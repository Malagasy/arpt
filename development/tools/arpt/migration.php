<?php

function get_prototype_functions(){
	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';

	$code = file_get_contents( $base_path . $file[0] );

	$gets = token_get_all( $code );

	logr($gets);

	exit();

}