<?php

function get_prototype_functions(){

	$base_path = './sys/';
	$files[] = 'access.php';
	$files[] = 'properties.php';

	$functions_path = 'functions/';
	$tools_path = 'tools/';

	$functions_files = scandir( $base_path . $functions_path );

	foreach( $functions_files as $file ){
		if( file_extension( $file ) == 'php' )
			$files[] = $functions_path . $file;
	}


	$tools_files = scandir( $base_path . $tools_path );

	foreach( $tools_files as $file ){
		if( file_extension( $file ) == 'php' )
			$files[] = $tools_path . $file;
	}

	logr($files);



	$args = array();
	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

	$code = strtok( file_get_contents( $base_path . $files[0] ) , "\r\n"  );

	while( $line !== false ){
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

		preg_match_all('/\(([A-Za-z0-9 ]+?)\)/', $arg, $tmp_params);
		xmpr($tmp_params);
		$params = explode( ',' , $tmp_params );
		$tmp['Parameters'] = $params;

		$f[] = $tmp;

	}
	logr($f);

	exit();
}