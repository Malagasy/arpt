<?php

/**
	* Retourne l'URL de la page actuelle.
	*
	* Retourne l'URL de la page actuelle plus les éventuelles paramètres passés en argument.
	*
	* @param String $arg Argument ajouté à la fin de l'URL.
	* @return String
*/

function get_current_url($arg=null){
	global $arpt;
	$url = trimslash( $_SERVER['REQUEST_URI'] );

	if( $arg[0] != '#' ) :
		if( $arg[0] != '/' )
			$arg = '/' . $arg;
	endif;


	$filtered_url = trimslash( substr( $url , strlen( $arpt->base_url ) ) );

	return get_site_url() . '/' . $filtered_url . $arg;
}

/**
	* Retourne l'URL propre de la page actuelle.
	*
	* Retourne l'URL propre de la page actuelle plus les éventuelles paramètres passés en argument.
	*
	* @param String $arg Argument ajouté à la fin de l'URL.
	* @return String
*/

function get_clean_url( $arg = null ){
	if( $arg[0] != '/' )
		$arg = '/' . $arg;
	return remove_params( get_current_url() ) . $arg ;
}

function get_base_url(){
	return  get_the_scheme() . get_the_host();
}

/**
	* Retourne l'URL de la page d'administration.
	*
	* Retourne l'URL de la page actuelle plus les éventuelles paramètres passés en argument.
	*
	* @param String $name Argument ajouté à la fin de l'URL.
	* @return String
*/

function get_admin_url( $name = null ){
	$name = trimslash( $name );
	if( !is_null( $name ) ) 
		return get_url( 'admin/' . $name );
	return get_url( 'admin' );
}

function get_signup_url( $arg = null ){
	return get_url( 'signup/' ) . $arg;
}

function get_site_url( $string = null ){


	if( $string )
		$string = '/' . trimslash( $string );

	if( get_base_var() != '' )
		return get_base_url() . get_base_var('/') . $string;
	return get_base_url() . $string;
}

/**
	* Retourne l'URL de la page recherchée.
	*
	* Retourne l'URL de la page recherchée $name.
	*
	* @param String $name Argument ajouté à la fin de l'URL.
	* @return String
*/

function get_url( $name ){
	return get_site_url() . clean_path( '/' . $name . '/' );;
}

function get_search_url(){
	return get_url( routing_search() );
}
function get_kw_url(){
	return get_url( routing_keywords() );
}

function get_home_url(){
	return get_url( routing_home() );
}


function get_logout_url(){
	return get_url( 'quit' );
}

/**
	* Retourne le chemin du thème actif.
	*
	* @return String
*/

function get_theme_dir(){
	return './development/themes/'.get_setting('current_theme');
}
/**
	* Retourne le chemin du dossier des uploads.
	*
	* @return String
*/
function get_upload_dir(){
	return './development/themes/uploads';
}

function get_tool_dir(){
	return './development/tools';
}

function get_tool_sysdir(){
	return './sys/tools';
}

function get_admin_css( $name ){
	return './sys/tools/css/' . $name;
}
function get_admin_js( $name ){
	return './sys/tools/js/' . $name;
}

function get_module_sysdir(){
	return './sys/module';
}

function get_mvc_dir( $name = null){
	return './development/mvc/' . $name;
}

function controllers_dir(){
	return get_mvc_dir( 'controllers' );
}

function vues_dir(){
	return get_mvc_dir( 'vues' );
}

function page_dir( $name ){
	return call_layers( 'page_dir_layer' , get_theme_dir().'/'. $name , $name );
}
