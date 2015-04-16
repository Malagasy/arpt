<?php

include_once 'sys/tools/php/timeago.inc.php';

function do_slug( $text ){
	$alphabet = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
    );

    $text = trim( strtr ( $text, $alphabet) );
	
    $text = preg_replace('/\W+/', '-', $text);

    return strtolower( trim($text,'-') );
}

function undo_slug( $text ){
	return str_replace( '-' , ' ' , $text );
}


function quote( $string ){
	//if( empty( $string ) ) return;
	return '\'' . $string . '\'';
}

function clause( $label , $value ){
	if( $label == '' || $value == '' ) return;
	return $label . $value;
}

function clause_where( $label , $comparator , $value , $before = null , $after = null ){
	if( $label == '' || $comparator == '' || $value == '' ) return;
	if( substr( $value , 0 , 4 ) == 'NOT-' ) return clause_where( $label , '<>' , substr( $value , 4 ) , $before , $after );
	if( substr( $value , 0 , 5 ) == 'LIKE-' ) return clause_like( $label , substr( $value , 5 ) , $before , $after );
	if( substr( $value , 0 , 4 ) == 'SUP-' ) return clause_where( $label , '>' , substr( $value , 4 ) , $before , $after );
	if( substr( $value , 0 , 4 ) == 'INF-' ) return clause_where( $label , '<' , substr( $value , 4 ) , $before , $after );
	if( substr( $value , 0 , 6 ) == 'SUPEQ-' ) return clause_where( $label , '>=' , substr( $value , 6 ) , $before , $after );
	if( substr( $value , 0 , 6 ) == 'INFEQ-' ) return clause_where( $label , '<=' , substr( $value , 6 ) , $before , $after );
	return $before . ' ' . $label . ' ' . $comparator . ' ' . quote( $value ) . ' ' . $after;
}

function clause_limit( $lim , $base = 0 ){
	if( $lim == '' || $lim == 'nolimit' ) return;
	return ' LIMIT ' . $lim . ' OFFSET ' . $base . ' ';
}

function clause_like( $label , $value , $before = null , $after = null){
	if( !empty( $value ) && substr( $value , 0 , 7 ) != 'STRICT-' ) $value = '%' . $value . '%';
	elseif( !empty( $value ) && substr( $value , 0 , 7 ) == 'STRICT-' ) $value = substr( $value , 7 );
	
	return clause_where( $label , ' LIKE ' , $value , $before , $after );
}

function clause_in( $label , $value , $before = null , $after = null){
	if( empty( $value ) ) return;
	if( is_array( $value ) ) :
		$r = $value;
		unset( $value );
		$value = implode( ',' , $r );
	endif;
	return $before . $label . ' IN ' . '(' . $value . ') ' . $after;
}

function clause_orderby( $label, $value = null , $func = null, $before = null , $after = null){
	if( $label == '' ) return;
	if( is_null( $func ) )
		$rest = ucwords( $func ) . '(' . $label . ', ' . $value . ')';
	else
		$rest = $label;
	return $before . ' ORDER BY ' . $rest . $after;
}

function last_value( $name ){
	return isset( $_COOKIE['postvar_' . $name] ) ?  $_COOKIE['postvar_' . $name] : '';
}

function a( $link , $msg = null , $specificities = null ){
	if( is_null( $msg ) )
		return '<a href="' . $link . '" ' . html_get_specificities( $specificities ) . '>';
	return '<a href="' . $link . '" ' . html_get_specificities( $specificities ) . '>' . $msg . '</a>';
}
function a_close(){
	return '</a>';
}

function img( $path , $specificities = null){
	if( $path == '' ) $path = get_upload_dir() . '/default-picture.jpg';
	return '<img src="' . $path . '" ' . html_get_specificities( $specificities ) . ' />';
}

function filter_is_year( $year ){
	if( is_year( $year ) ) return $year;
	return;
}

function filter_is_category( $string ){
	if( get_category_by_name( $string ) === false ) return false;
	return $string;
}

function filter_is_active_content( $string ){
	if( is_active_content( $string ) ) return $string;
	return false;
}

function filter_exists( $var ){
	if( isset( $var ) ) return $var;
	return false;
}

function trimslash( $string ){
	if( is_null( $string ) )
		return null;
	return trim( $string , '/' );
}

function breadcrumb( $separator = ' >> ' ){

	/*

	$element['home'] = 'Home';

	if( !is_errorpage() ){
		$element['type'] = get_pagetype();
		$element['title'] = get_currentcontentname();
		if( qpid() > 0 ){ $element['parent_title'] = a( content_link( qpid() ) , get_contentname( qpid() ) ); }
	}else{
		$element['type'] = 'Page introuvable';
	}
	*/
	$default = array( 'home' => sitename() , 'type' => null , 'parent_title' => null , 'title' => null );

	$element['type'] = qtype();

	if( get_queried()->total > 1 )
		$element['title'] = 'Tous';
	else
		$element['title'] = qtitle();

	if( $category = qcategory() ){
		$element['category'] = $category;
	}

	if( ( $qpid = qpid() ) > 0 ){
		$element['parent']= $qpid;
	}

	$element['separator'] = $separator;

	return call_layers( 'breadcrumb_layer' , array_merge( $default , $element ) );
}

function html_get_specificities( $specificities ){
	$return = '';

	if( is_null( $specificities ) ) return $return;
	
	foreach( $specificities as $key => $value )
		if( $value !== null )
			$return .= $key . '="' . $value . '" ';
		
	return $return;
}


function filter_extrafields( $args ){
	$filtered = array();
	if( is_array( $args ) ) :
		foreach( $args as $key => $arg )
			if( substr( $key , 0 , 11) == 'extrafields' )
				$filtered[ substr( $key , 12 ) ] = $arg;
		return $filtered;
	endif;

	return substr( $args , 12 );
}

function pluralize( $string ){
	if( substr( $string , -1 ) == 's' )
		return $string;
	return $string . 's';
}
function singularize( $string ){
	if( substr( $string , -1 ) == 's' )
		return substr( $string , 0 , -1 );
	return $string;
}

function div( $params , $content = null ){
	if( is_null( $content ) ) :
		echo ' <div ' . html_get_specificities( $params ) . '>';
		return;
	endif;


	echo ' <div ' . html_get_specificities( $params ) . '>';
	echo $content;
	echo '</div> ';

}

function div_close(){
	echo '</div> ';
}

function span( $params , $content = null ){
	if( is_null( $content ) ) :
		echo ' <span ' . html_get_specificities( $params ) . '>';
		return;
	endif;


	echo ' <span ' . html_get_specificities( $params ) . '>';
	echo $content;
	echo '</span>';

}

function span_close(){
	echo '</span> ';
}

function li( $params , $content = null ){
	if( is_null( $content ) ) :
		echo ' <li ' . html_get_specificities( $params ) . '>';
		return;
	endif;


	echo ' <li ' . html_get_specificities( $params ) . '>';
	echo $content;
	echo '</li> ';

}
function li_close(){
	echo '</li> ';
}
function ul( $params , $content = null ){
	if( is_null( $content ) ) :
		echo ' <ul ' . html_get_specificities( $params ) . '>';
		return;
	endif;


	echo ' <ul ' . html_get_specificities( $params ) . '>';
	echo $content;
	echo '</ul> ';

}
function ul_close(){
	echo '</ul> ';
}

function strong( $str ){
	return ' <strong>' . $str . '</strong> ';
}

function italic( $str ){
	return ' <i>' . $str . '</i> ';
}

function arpt_date( $date , $part1 = 'd/m/o' , $part2 = 'H\hi' , $link = ' à ' ){
	
	$time_timestamp = is_number( $date ) ? $date : strtotime( $date );

	return date($part1 , $time_timestamp) . $link . date($part2 , $time_timestamp);
}

function bold_search( $text , $search ){
	if( !is_array( $search ) ) $search = array( $search );

	$search[] = do_slug( $search[0] );

	foreach( $search as $key ) :
		$text = str_replace( trim( $key ) , '<b>'.$key.'</b>' , $text );
		$text = str_replace( ucfirst( $key ) , '<b>'.ucfirst( $key ).'</b>' , $text );
	endforeach;

	return $text;

}
function delete_entry( $searched , $array ){
	$key = array_search( $searched , $array );
	if( $key === false ) return $array;

	unset( $array[$key] );
	return $array;
} 

function longestWord( $string ){
	$words  = explode(' ', $string);

	$longestWordLength = 0;
	$longestWord = '';

	foreach ($words as $word) {
	   if (strlen($word) > $longestWordLength) {
	      $longestWordLength = strlen($word);
	      $longestWord = $word;
	   }
	}

	return $longestWord;
}
?>