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

		$nb_line = 0;

		while( $line !== false ){
			$nb_line++;
			if( preg_match($preg, $line ) )
				$args[] = array( $line , $nb_line );
			$line = strtok( "\r\n" );
		}

		foreach( $args as $arg ){

			$arg[0] = str_replace('function','',$arg[0]);
			$arg[0] = trim( str_replace('{','',$arg[0]) );

			$f_name = substr( $arg[0] , 0 , strpos( $arg[0] , '(' ) );

			$tmp = array();

			$tmp['FunctionName'] = $f_name;
			$tmp['Prototype'] = $arg[0];
			$tmp['Line'] = $arg[1];

			preg_match('#\((.*?)\)#', $arg[0], $tmp_params);

			$params = explode( ',' , $tmp_params[1] );

			$params_2 = array();

			if( $tmp_params[1] ){
				foreach( $params as $param ){
					if( ( $the_pos = strpos( $param , '=' ) ) !== false ){
						$params_2[] = array( 'paramName' => trim( substr( $param , 0 , $the_pos ) ) , 'optional' => true );
					}else{
						$params_2[] = array( 'paramName' => trim( $param ) , 'optional' => false );
					}

				}
			}else{
				$params_2 = false;
			}

			$tmp['Parameters'] = $params_2;
			$tmp['File'] = 'sys/' . $the_file;

			$f[$f_name] = $tmp;


		}

	}

	logr($f);
	die();

	/*foreach( $f as $function ){

		$function = $f['get_contentinfo'];

		$the_args['parentid'] = 5;
		$the_args['userid'] = 1;
		$the_args['title'] = $function['FunctionName'];
		$the_args['message'] = '<p>Cette page a été générée automatiquement et n\'a pas encore été modifié.</p>';
		$the_args['message'] .= '<p>Cette fonction se trouve dans le fichier <a href="'. get_url( 'tracks/' . $function['File'] ) . '" alt="Liens vers ' . $function['File'] . '">' . $function['File'] . '</a>.</p>';
		
		$cid = insert_new_content( 'fonction' , $the_args );

		if( $cid ){
			$value = "<pre><code>" . highlight_string( $function['Prototype'] ) . "</code></pre>";
			if( $function['Parameters'] != false ){
				$value .= "<ul>";
				foreach( $function['Parameters'] as $parameter ){
					$value .= "<li><strong>" . $parameter['paramName'] . "</strong>";
					if( $parameter['optional'] == true )
						$value .= " (Optionnel)";
					$value .= "  </li>";
				}
				$value .= "</ul>";
			}

			update_contentproperty( $cid , 'prototype' , $value );
			update_contentproperty( $cid , 'return' , 'Non renseigné.' );
			update_contentproperty( $cid , 'example' , 'Non renseigné.' );
		}
	//}*/
		
	
}