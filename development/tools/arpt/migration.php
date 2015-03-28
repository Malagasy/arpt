<?php

function get_prototype_functions(){

	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';


	$args = array();
	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

	$code = strtok( file_get_contents( $base_path . $files[0] ) , "\r\n"  );

	while( $line !== false ){
		logr($line);
		if( preg_match($preg, $line ) )
			$args[] = $line;
		$line = strtok( "\r\n" );
	}

	$f = array();

	foreach( $args as $arg ){

		$arg = str_replace('function','',$arg);
		$arg = trim( str_replace('{','',$arg) );

		$f_name = substr( $arg , 0 , strpos( $arg , '(' ) );

		$tmp['FunctionName'] = $f_name;
		$tmp['Prototype'] = $arg;

		$f[] = $tmp;

	}

	logr($args);
	logr($f);

	exit();
}