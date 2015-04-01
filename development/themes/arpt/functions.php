<?php

add_trigger( 'theme_setup' , 'init_arpt_theme' );
function init_arpt_theme(){

	add_css_script( get_theme_dir() . '/css/bootstrap.min.css' );
	add_css_script( '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/default.min.css' );
	add_css_script( get_theme_dir() . '/css/arpt.css' );
	
	add_js_script( '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js' );
	add_js_script( get_theme_dir() . '/js/bootstrap.min.js' );
	add_js_script( '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js' );
	add_js_script( get_tool_sysdir() . '/js/jquery-ui-1.11.3/jquery-ui.min.js' );
	add_js_script( get_theme_dir() . '/js/functions.js' );
	add_js_script( get_theme_dir() . '/js/main.js' );

	add_layer( 'newsletters_title_layer' , 'newsletters_title_layer' );
	add_layer( 'breadcrumb_layer' , 'breadcrumb_layer' );
	add_layer( 'widget_title_layer' , 'arpt_widget_title' );
	add_layer( 'widget_last_articles_delimiter_layer' , 'widget_last_articles_delimiter_layer' );

	add_layer( 'the_routing' , 'arpt_the_routing_layer' );

}

function newsletters_title_layer( $title ){
	$title = undo_slug( do_slug( strip_tags( $title ) ) );
	return '<div class="text-uppercase text-center"><h3>' . $title . '<br><small>Par email</small></h3></div>';
}

function breadcrumb_layer( $breadcrumb ){
	if( is_errorpage() ) return '';
	$separator = ' <span class="lightgray">/</span> ';
	if( $breadcrumb['parent_title'] === null )
		$parent_title_and_separator = '';
	else
		$parent_title_and_separator = $separator . $breadcrumb['parent_title'];
	if( $breadcrumb['title'] == '' ) $breadcrumb['title'] = 'Tous';
	$breadcrumb['title'] = $separator . $breadcrumb['title'];
	
	$output = a( get_site_url() , $breadcrumb['home'] ) . $separator . a( get_url( '/' . $breadcrumb['type'] . '/' ) , undo_slug( ucwords( $breadcrumb['type'] ) ) ) . $parent_title_and_separator . $breadcrumb['title'];
	return $output;
}

function widget_last_articles_delimiter_layer( $arg ){
	$arg['start'] = '<ul class="list-unstyled last_articles">';
	$arg['end'] = '</ul>';
	$arg['start_inner'] = '<li>';
	$arg['end_inner'] = '</li>';
	$arg['class_inner'] = '';
	return $arg;
}

function arpt_widget_title( $title , $cleantitle ){
	return '<h3>' . $cleantitle . '</h3>';
}

function get_arpt_logo( $params = null ){
	return img(get_upload_dir() . '/arpt-logo.png' , $params );
}

function arpt_logo_link( $params = null ){
	return a( get_home_url() , get_arpt_logo( $params ) );
}

function arpt_the_routing_layer(){

	if( is_paginate() || get_queried()->total > 1 )
		$this->load( page_dir( 'archive.php' ) );

}