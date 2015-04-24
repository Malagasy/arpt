<?php

function redirect( $target ){
	global $arpt;
	$arpt->redirect( $target );
}

function redirect_success( $get = null ){
	$curl = get_clean_url();
	$lastarg = get_lastarg();

	if( $get )
		if( $get[0] == '/' )
			$get = substr( $get , 1 );

	call_triggers( 'on_redirect_success' );

	redirect( $curl . 'success/' . $get );
}
function redirect_failure( $get = null ){
	$curl = get_clean_url();
	call_triggers( 'on_redirect_failure' );

	if( $get )
		if( $get[0] == '/' )
			$get = substr( $get , 1 );

	redirect( $curl . 'failure/' . $get );
}

function load( $file ){
	global $arpt;
	if( file_exists( page_dir( $file ) ) )
		$arpt->load( page_dir( $file ) );
}

function load_part( $name ){
	load( 'part-' . $name . '.php' );
}

function get_header( $name = null ){
	call_triggers( 'before_get_header' );
	( $name == null ) ? load('header.php') : load( $name );
}

function head(){
	if( get_base_var() )
		echo '<base href="/' . get_base_var() .'/">';
	else
		echo '<base href="/">';

	call_triggers( 'before_loading_script' );

	call_triggers('css_script');
	
	call_triggers('js_script');

	call_triggers('meta_data');
}

function meta_configuration(){
	echo '<meta charset="utf-8" />';
	echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
	echo '<meta name="description" content="' . sitedescription() .'" />';
	echo '<meta http-equiv="content-language" content="fr">'; // todo multilangagage..
}

function get_footer( $name = null ){
	call_triggers( 'before_get_footer' );
	( $name == null ) ? load('footer.php') : load( $name );
	call_triggers( 'end_of_page' );
}