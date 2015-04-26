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

	add_trigger( 'the_routing' , 'arpt_the_routing_layer' );

}

function newsletters_title_layer( $title ){
	$title = undo_slug( do_slug( strip_tags( $title ) ) );
	return '<div class="text-uppercase text-center"><h3>' . $title . '<br><small>Par email</small></h3></div>';
}

function breadcrumb_layer( $breadcrumb ){
	if( is_errorpage() ) return '';
	$breadcrumb['separator'] = ' <span class="lightgray">/</span> ';

	$output = a( get_site_url() , $breadcrumb['home'] ) . $breadcrumb['separator'];
	$output .= a( get_url('/'.$breadcrumb['type'].'/') , undo_slug( ucwords( $breadcrumb['type'] ) ) ) . $breadcrumb['separator'];
	if( isset( $breadcrumb['category'] ) ){
		$output .= a( get_url('/'.$breadcrumb['category'].'/') , undo_slug( ucwords( $breadcrumb['category'] ) ) ) . $breadcrumb['separator'];
	}
	if( isset( $breadcrumb['parent'] ) ){
		$output .= a( content_link( $breadcrumb['parent'] ) , get_contentname( $breadcrumb['parent'] ) ) . $breadcrumb['separator'];
	}

	$output .= $breadcrumb['title'];

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
	global $arpt;
	if( ( is_paginate() || get_queried()->total > 1 ) && !is_homepage() ){
		$arpt->load( page_dir( 'archive.php' ) );
		exit();
	}

}

function arpt_before_loading_script(){
	echo '<link rel="icon" type="image/png" href="' . get_arpt_logo() . '"/>';
}
add_trigger('before_loading_script' , 'arpt_before_loading_script' );