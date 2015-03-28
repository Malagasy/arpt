<?php

function get_prototype_functions(){

	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';

	$code = file_get_contents( $base_path . $files[0] );

	$args = array();
	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

	$handle = fopen( $base_path . $files[0] , "r" );
	if( $handle ){
		while( ($line = fgets( $handle ) !== false ) ){
			echo $line;
			if( preg_match( $preg, $line ) )
				$args[] = $line;
		}
		fclose($handle);
	}else{
		echo 'fails';
	}

	logr($args);

	exit();
}