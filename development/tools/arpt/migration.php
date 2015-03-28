<?php

function get_prototype_functions(){

	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';

	$code = file_get_contents( $base_path . $files[0] );

	$args = array();

	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

	preg_match_all( $preg, $code, $args );

	logr($args);

	exit();
}