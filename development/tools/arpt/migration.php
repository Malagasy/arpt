<?php

function get_prototype_functions(){
	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';
	logr( $base_path . $files[0] );
	$code = file_get_contents( $base_path . $files[0] );
	$gets = token_get_all( $code );

	$r = array();
	foreach( $gets as $k => $v ){
		$r[][$k] = token_name($v);
	}

	logr($r);

	exit();

}