<?php

function valid_source( $name , $userid = null ){
	if( !filter_has_var(INPUT_POST, $name) ) return false;
	
	if( !check_token( $name , $_POST[$name] , $userid ) ) return false;
	
	return true;

}

function sanitize_str( $string ){
	return filter_var( $string , FILTER_SANITIZE_STRING );
}

function remove_params( $url ){

	if( !is_array( $url ) ) :
		$url = explode( '?' , $url ); 
		$noparams = explode( '/' , $url[0] );
		$was_array = false;
	else :
		$url = implode( '/' , $url );
		$url = explode( '?' , $url );
		$url = explode( '#' , $url[0] );
		$noparams = explode( '/' , $url[0] );
		$was_array = true;
	endif;

	if( end( $noparams ) == '' )
		array_pop($noparams);

	if( in_array( end($noparams) , array( 'success' , 'failure' ) ) ) :
		$cleaned_url = array_slice( $noparams , 0 , -1 );
		return $was_array ? $cleaned_url : implode( '/' , $cleaned_url );
	endif;
	return $was_array ? $noparams : implode( '/' , $noparams );
}


function is_at_errors( $object ){
	if( !is_object( $object ) ) return false;
	if( get_class( $object ) == 'AT_Errors' )
		return true;
	return false;
}

function critical_error( $error ){
	global $cerror;

	$params_url = get_params();

	$pagetype = array_shift( $params_url );

	call_triggers( 'critical_error_' . $pagetype , $params_url );

	$cerror = new AT_Errors( array( 'from_' . $pagetype => $error ) );
}

function check_critical_error(){
	global $cerror;
	if( !is_at_errors( $cerror ) ) return;


	echo $cerror->get_first();
	echo '</body></html>';
	exit();
}

function log_for_user( $log ){
	global $log_for_user;

	$log_for_user = new AT_Errors( array( 'UserLog' => $log ) );
}

function check_log_for_user(){
	global $log_for_user;
	if( !is_at_errors( $log_for_user ) ) return;

	$errors = $log_for_user->get();

	echo '<div class="logforuser text-center alert alert-danger">';
	foreach( $errors as $key => $error )
		echo '<p><strong>Atttention !</strong> ' . $error . '</p>';
	echo '</div>';

}

function the_original_postvar( $key = null ){
	global $the_original_postvar;

	if( is_null( $key ) )
		return $the_original_postvar;

	if( isset( $the_original_postvar[$key] ) )
		return $the_original_postvar[$key];
	return false;
}

function topv( $key = null ){
	return the_original_postvar( $key );
}

function pwd_crypt( $string ){
	if( $string == '' ) return null;
	return md5( $string );
}

function is_image( $path ){

	$finfo = finfo_open( FILEINFO_MIME_TYPE );

	$mime_path = finfo_file( $finfo , $path );

	return in_array( $mime_path , array( 'image/png' , 'image/jpeg' , 'image/gif' , 'image/tiff' , 'image/bmp' ) );

}

function is_email( $string ){
	if( filter_var( $string , FILTER_VALIDATE_EMAIL ) !== false ) return true;
	return false;
}

function is_year( $year ){
	if( !is_number( $year ) ) return false;

	if( strlen( $year ) != 4 ) return false;
	elseif( $year > date("Y") ) return false;

	return true;
}

function is_number( $string ){
	if( ( $str = filter_var( $string , FILTER_VALIDATE_INT ) ) === false ) return false;
	return true;
}

function diffstr( $str1 , $str2 ){
	if( strtolower( $str1 ) != strtolower( $str2 ) )
		return true;
	return false;
}
function samestr( $str1 , $str2 ){
	if( diffstr( $str1 , $str2 ) )
		return false;
	return true;
}

function maxsize_upload_files(){
	return call_layers('maxsize_upload_files' , 3000000);
}

function post_submit(){
	if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && !empty( $_POST ) ) return true;
	return false;
}

function clean_path( $url ){
	return preg_replace('#/+#','/',$url);
}

function safe_submit(){
	if( valid_source( 'unsafe_submit' ) ) return false;
	return true;
}