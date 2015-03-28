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

	$f = array();

	foreach( $files as $the_file ){

		$line = strtok( file_get_contents( $base_path . $the_file ) , "\r\n"  );

		while( $line !== false ){
			if( preg_match($preg, $line ) )
				$args[] = $line;
			$line = strtok( "\r\n" );
		}

		foreach( $args as $arg ){

			$arg = str_replace('function','',$arg);
			$arg = trim( str_replace('{','',$arg) );

			$f_name = substr( $arg , 0 , strpos( $arg , '(' ) );

			$tmp['FunctionName'] = $f_name;
			$tmp['Prototype'] = $arg;

			preg_match('#\((.*?)\)#', $arg, $tmp_params);
			logr($f_name);
			logr($tmp_params);
			logr('-----');
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
			$tmp['File'] = 'sys/' . $the_file;

			$f[$f_name] = $tmp;

		}
	}

	foreach( $f as $function ){
		$the_args['parentid'] = 5;
		$the_args['userid'] = 1;
		$the_args['title'] = $function['FunctionName'];
		$the_args['message'] = '<p>Cette page a été générée par ARptDocs, elle n\'a pas encore été modifié.</p>';
		$the_args['message'] .= '<p>La fonction se trouve dans le fichier <a href="'. get_url( 'tracks/' . $function['File'] ) . '" alt="Liens vers ' . $function['File'] . '">' . $function['File'] . '</a></p>';
		logr($the_args);
	}
	logr($f);

	exit();
}