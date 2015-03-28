<?php

function get_prototype_functions(){
	if( !isset( $_GET['update_function_documentation'] ) && !is_systempage() ) return;

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
						$params_2[] = array( 'paramName' => trim( substr( $param , 0 , $the_pos ) ) , 'optional' => true , 'default' => trim( substr( $param , $the_pos ) ) );
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

				$summary = true;
				$pdoc_format['Summary'] = '';
				$pdoc_format['Description'] = array();
				$pdoc_format['Metas'] = array();
				$description = false;
				$metas = false;

				$p_number_description = 0;
				$param_number = array();


				foreach( $the_pdoc as $pdoc_line ){

					$pdoc_line = trim( $pdoc_line );

					if( $summary ){
						if( $pdoc_line[0] == '*' && strlen( $pdoc_line ) > 2 ){
							$pdoc_line = substr( $pdoc_line , 1 );
							if( ($dot_position = strpos( $pdoc_line , '.' ) ) !== false ) :
								$pdoc_format['Summary'] .= substr( $pdoc_line , 0 , $dot_position ) . '.';
								$summary = false;
								$description = true;
							else :
								$pdoc_format['Summary'] .= $pdoc_line;
							endif;

						}
					}
					if( $description ){

						if( $pdoc_line[0] == '*' && strlen( $pdoc_line ) > 2 ){

								$pdoc_line_2 = trim( substr( $pdoc_line , 1 ) );

								$first_word = strstr( $pdoc_line_2 , ' ' , true );

								if( $first_word[0] == '@' ) :
									$description = false;
									$metas = true;
								else :
									if( !isset( $pdoc_format['Description'][$p_number_description] ) )
										$pdoc_format['Description'][$p_number_description] = '';

									$pdoc_format['Description'][$p_number_description] .= $pdoc_line_2;
								endif;

						}else{
							$p_number_description++;
						}
					}

					if( $metas ){

						if( $pdoc_line[0] == '*' ){
							$pdoc_line_no_star = trim( substr( $pdoc_line , 1 ) );

							$pdoc_params = array_filter( explode( ' ' , $pdoc_line_no_star ) );

							if( $pdoc_params[0][0] == '@' ){
								$pdoc_params[0] = substr( $pdoc_params[0] , 1 ); // remove the @

								$pdoc_last_type = $pdoc_params[0];

								if( !isset( $param_number[$pdoc_params[0]] ) )
									$param_number[$pdoc_params[0]] = -1;

								$param_number[$pdoc_params[0]]++;

								if( samestr( $pdoc_params[0] , 'param' ) ){
									$pdoc_format['Metas'][ $pdoc_params[0] ][$param_number[$pdoc_params[0]]]['Type'] = $pdoc_params[1];
									$pdoc_format['Metas'][ $pdoc_params[0] ][$param_number[$pdoc_params[0]]]['Argument'] = $pdoc_params[2];
									
									if( !isset( $pdoc_format['Metas'][ $pdoc_params[0] ][$param_number[$pdoc_params[0]]]['Description'] ) )
										$pdoc_format['Metas'][ $pdoc_params[0] ][$param_number[$pdoc_params[0]]]['Description'] = '';

									$pdoc_format['Metas'][ $pdoc_params[0] ][$param_number[$pdoc_params[0]]]['Description'] .= implode( ' ' , array_slice( $pdoc_params , 3 ) );

								}
								if( samestr( $pdoc_params[0] , 'return' ) ){
									$pdoc_format['Metas'][ $pdoc_params[0] ]['Type'] = $pdoc_params[1];
									
									if( !isset( $pdoc_format['Metas'][ $pdoc_params[0] ]['Description'] ) )
										$pdoc_format['Metas'][ $pdoc_params[0] ]['Description'] = '';

									$pdoc_format['Metas'][ $pdoc_params[0] ]['Description'] .= implode( ' ' , array_slice( $pdoc_params , 2 ) );

								}
							}else{
								if( samestr( $pdoc_last_type , 'param' ) ){
									if( $pdoc_line_no_star )
										$pdoc_format['Metas'][ $pdoc_last_type ][$param_number['param']]['Description'] .= ' ' . $pdoc_line_no_star;
								}
								if( samestr( $pdoc_last_type , 'return' ) ){
									if( $pdoc_line_no_star )
										$pdoc_format['Metas'][ $pdoc_last_type ]['Description'] .= ' ' . $pdoc_line_no_star;
								}
							}
						}

					}

				}

				$f[$id_files]['PHPDoc'] = $pdoc_format;

			}




		}


		$args = array();

	}

	foreach( $f as $function ){
		$the_args['title'] = $function['FunctionName'];
		if( $function['PHPDoc']['Summary'] )
			$the_args['message'] = '<p>'. $function['PHPDoc']['Summary'] .'</p>';
		else
			$the_args['message'] = '<p>Cette page a été générée automatiquement et n\'a pas encore été modifié.</p>';

		$the_args['message'] .= '<p>Cette fonction se trouve dans le fichier <a href="'. get_url( 'tracks/' . $function['File'] ) . '" alt="Liens vers ' . $function['File'] . '">' . $function['File'] . ' (l.'.$function['Line'].')</a>.</p>';

			echo 'Mise à jour de ' . $function['FunctionName'] . '<br>';

			$content = get_contents( array( 'slug' => do_slug( $function['FunctionName'] ) ) );
			$content->qnext();
			if( diffstr( $content->qtype() , 'fonction' ) ) continue;
			if( strtotime( $content->qproperty('last_edit') ) >=  filemtime( './' . $function['File'] ) ) continue;
			echo "lastedit:" . strtotime( $content->qproperty('last_edit') . "-----" . filemtime( './' . $function['File'] ) . '<br>';
			update_content( $content->qid() , $the_args );

				$value = '<pre><code class="php">' . $function['Prototype'] . '</code></pre>';

				if( isset( $function['PHPDoc']['Description'] ) )
					foreach( $function['PHPDoc']['Description'] as $paragraphe )
						$value .= "<p>" . $paragraphe . "</p>";


				if( isset( $function['PHPDoc']['Metas']['param'] ) ){
					if( $function['PHPDoc']['Metas']['param'] != false ){
						$value .= '<ul class="fonction-parametres">';
						foreach( $function['PHPDoc']['Metas']['param'] as $parameter ){
							$value .= "<li>" . $parameter['Type'] . ' <strong>' . $parameter['Argument'] . '</strong>';
							if( $parameter['optional'] == true )
								$value .= " (Optionnel) : ";
							else
								$value .= " : ";

							if( $parameter['Description'] )
								$value .= $parameter['Description'];

							if( $parameter['optional'] == true )
								$value .=  "<br>Défaut: " . strtoupper( $parameter['default'] );

							$value .= "  </li>";
						}
						$value .= "</ul>";
					}
				}else{
					if( $function['Parameters'] != false ){
						$value .= '<ul class="fonction-parametres">';
						foreach( $function['Parameters'] as $parameter ){
							$value .= "<li><strong>" . $parameter['paramName'] . "</strong>";
							if( $parameter['optional'] == true ){
								$value .= " (Optionnel) <br> Défaut: " . strtoupper( $parameter['default'] );
							}
							$value .= "  </li>";
						}
						$value .= "</ul>";
					}
				}

				update_contentproperty( $content->qid() , 'prototype' , $value );


				$value = '';
				if( isset( $function['PHPDoc']['Metas']['return'] ) ){
					$value .= "<p>Retourne <strong>" . $function['PHPDoc']['Metas']['return']['Type'] . '</strong>.</p>';
					if(  isset( $function['PHPDoc']['Metas']['return']['Description'] ) )
						$value .= "<p>" . $function['PHPDoc']['Metas']['return']['Description'] . "</p>";
				}else{
					$value .= "Non renseigné.";
				}
				update_contentproperty( $content->qid() , 'return' , $value );


				echo 'CustomChamps édités.' . '<br>';
			}
die();
		redirect( get_clean_url() );
		
	
}
add_trigger( 'after_checkurl' , 'get_prototype_functions' );