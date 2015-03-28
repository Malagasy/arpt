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

	$args = array();
	$preg = '/function[\s\n]+(\S+)[\s\n]*\(/';

		logr($files);

	foreach( $files as $the_file ){

		$code = strtok( file_get_contents( $base_path . $the_file ) , "\r\n"  );

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

			preg_match('#\((.*?)\)#', $arg, $tmp_params);

			$params = explode( ',' , $tmp_params[1] );
			$params_2 = array();

			foreach( $params as $param ){
				if( ( $the_pos = strpos( $param , '=' ) ) !== false ){
					$params_2[] = array( 'paramName' => trim( substr( $param , 0 , $the_pos ) ) , 'optional' => true );
				}else{
					$params_2[] = array( 'paramName' => trim( $param ) , 'optional' => false );
				}

			}

			$tmp['Parameters'] = $params_2;
			$tmp['File'] = $base_path . $the_file;

			$f[] = $tmp;

		}
	}
	logr($f);

	exit();
}