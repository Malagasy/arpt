<?php

$widgets = array();

function add_dynamic_widget( $title , $func ){
	global $widgets;

	$widgets[$func] = $title;
}

function get_widgets( $func = null ){
	global $widgets;
	if( is_null( $func ) )	return $widgets;
	
	if( isset( $widgets[$func] ) )
		return $widgets[$func];
	else
		return false;
}

function widget_exists( $func ){
	if( get_widgets( $func ) ) return true;
	return false;
}

function get_available_widgets(){
	$r = get_widgets(); 
	$v = get_widgetmenu();
	
	$e = array_diff( array_keys( $r ), $v );
	
	if( empty( $e ) ) return array();
	
	foreach( $e as $val )
		$t[$val] = get_widgets( $val );
	
	return $t;
}

function output_widgets($before = null, $after=null , $position = null){
	$the_widgets = new Menu();
	$the_widgets->set( get_widgetmenu() );

	$the_widgets->display( $before , $after , $position);
}

function get_widgetmenu(){
	$r = get_option( 'widgetmenu' );
	
	$t = (array)unserialize( $r );
	if( reset( $t ) == '' ) return array();
	return $t;
}


function getCallingWidget(){
	$callers = debug_backtrace();
	$i = 0;
	for( $i = 0 ; $i < 5 ; $i++ ){
		if( !widget_exists( $callers[$i]['function'] ) ) continue;
		return $callers[$i]['function'];
	}
	return null;
}


function action_edit_widget(){
	
/*	if( !empty( $_POST ) )	
		if( isset( $_POST['action'] ) )
			if( $_POST['action'] == 'edit-widget' ) 
				return true;
		
	return false;*/

	if( is_adminpage() )
		if( is_arg( 'widgetmenu' ) )
			return true;

	return false;
}
 

 function widget_title(){
 	$widgetname =   getCallingWidget();
 	if( false === ( $title = get_option( 'widget_title_' . $widgetname ) ) )
 		return call_layers( 'widget_title_layer' , '<h4>' . get_widgets( $widgetname ) . '</h4>' , get_widgets( $widgetname ) );
 	return call_layers( 'widget_title_layer' , '<h4>' . $title . '</h4>' , $title );
 }

 function widget_title_form( $classInput = 'form-control' ){
	$delimiter = call_layers( 'widget_title_form_layer' , array( 'start' => div( array( 'class' => 'form-group' ) ) , 'end' => div_close() ) );
	echo $delimiter['start'];
	form_input( array( 'class' => $classInput , 'name' => option('widget_title') , 'value' =>  strip_tags( widget_title() ) ) , 'Titre de la section' );
	echo $delimiter['end'];
 }