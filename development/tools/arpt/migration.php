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

	unset( $files );

	$files[] = 'access.php';

	$id_files = 0;

	foreach( $files as $the_file ){

		$line = strtok( file_get_contents( $base_path . $the_file ) , "\r\n"  );

		$nb_line = 0;

		while( $line !== false ){
			$nb_line++;
			if( preg_match($preg, $line ) )
				$args[] = array( $line , $nb_line );
			$current_file_lines[$nb_line] = $line;
			$line = strtok( "\r\n" );
		}

		foreach( $args as $arg ){

			$id_files++;

			$arg[0] = str_replace('function','',$arg[0]);
			$arg[0] = trim( str_replace('{','',$arg[0]) );

			$f_name = trim( substr( $arg[0] , 0 , strpos( $arg[0] , '(' ) ) );

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

			$f[$id_files] = $tmp;



			$start_line = $tmp['Line']-3;

			$found_pdoc = false;

			while( ( $foundpdoc = strpos( $current_file_lines[$start_line] , '/**' ) ) === false ){
				$start_line--;
				if( $start_line < 0 ) break;
				if( isset( $f[$id_files-1] ) )
					if( $start_line == $f[$id_files-1]['Line'] )
						break;
			}

			if( $foundpdoc !== false ){
				$pdoc_format = array();
				$the_pdoc = array_slice( $current_file_lines , $start_line , $tmp['Line'] - 1 - $start_line - 1 );
				logr($the_pdoc);

				$summary = true;
				$pdoc_format['Summary'] = '';
				$pdoc_format['Description'] = array();
				$description = false;
				$metas = false;

				$p_number_description = 0;

				foreach( $the_pdoc as $pdoc_line ){

					$pdoc_line = trim( $pdoc_line );

					if( $summary ){
						if( $pdoc_line[0] == '*' && strlen( $pdoc_line ) > 2 ){
							$pdoc_line = substr( $pdoc_line , 1 );
							if( ($dot_position = strpos( $pdoc_line , '.' ) ) !== false ) :
								$pdoc_format['Summary'] .= substr( $pdoc_line , 0 , $dot_position );
								$summary = false;
								$description = true;
							else :
								$pdoc_format['Summary'] .= $pdoc_line;
							endif;

						}
					}
					if( $description ){

						if( !isset( $pdoc_format['Description'][$p_number_description] ) )
							$pdoc_format['Description'][$p_number_description] = '';

						if( $pdoc_line[0] == '*' && strlen( $pdoc_line ) > 2 ){
								$pdoc_line = substr( $pdoc_line , 1 );

								$first_word = strstr( substr( $pdoc_line , 1 ) , ' ' , true );
								if( $first_word[0] == '@' ) :
									$description = false;
									$metas = true;
								else :
									$pdoc_format['Description'][$p_number_description] .= $pdoc_line;
								endif;

						}else{
							$p_number_description++;
						}
					}

					if( $metas ){

					}

				}

				logr($pdoc_format);

			}




		}


		$args = array();

	}
	logr($f);
	logr($current_file_lines);

	/*
	foreach( $f as $function ){

		$the_args['parentid'] = 5;
		$the_args['userid'] = 1;
		$the_args['title'] = $function['FunctionName'];
		$the_args['message'] = '<p>Cette page a été générée automatiquement et n\'a pas encore été modifié.</p>';
		$the_args['message'] .= '<p>Cette fonction se trouve dans le fichier <a href="'. get_url( 'tracks/' . $function['File'] ) . '" alt="Liens vers ' . $function['File'] . '">' . $function['File'] . ' (l.'.$function['Line'].')</a>.</p>';
		$the_args['keywords'] = 'fonctions, ' . $function['File'];

		$content = get_contents( array( 'title' => $function['FunctionName'] ) );
		if( !$content->qhas() ){
			echo 'Création de ' . $function['FunctionName'] . '<br>';
		
			$cid = insert_new_content( 'fonction' , $the_args );

			if( $cid ){
			echo $function['FunctionName'] . ' crée.' . '<br>';
				$value = '<pre><code class="php">' . $function['Prototype'] . '</code></pre>';
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


				echo 'CustomChamps crées.' . '<br>';
			}

		}
	}
	*/

	die();
		
	
}