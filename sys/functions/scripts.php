<?php

// they should be inside an object...
$css_scripts = new Chain();
$js_scripts = new Chain();

function add_script( $type , $path , $position = null ){
	global $css_scripts, $js_scripts;

	if( $type == 'css' )
		$css_scripts->add( $path , $position );
	elseif( $type == 'js' )
		$js_scripts->add( $path , $position );
	else 
		return false;

	return true;
}

function add_css_script( $path , $position = null ){
	return add_script( 'css' , $path , $position );
}

function add_js_script( $path , $position = null ){
	return add_script( 'js' , $path , $position );
}

function load_scripts( $type ){
	global $css_scripts, $js_scripts;
	
	if( $type == 'css' )
		foreach( $css_scripts->get() as $v )
			echo '<link href="' .  $v  . '" rel="stylesheet" type="text/css">';
	elseif( $type == 'js' )
		foreach( $js_scripts->get() as $v )
			echo '<script src="' .  $v . '"></script>';

	
}

add_trigger( 'css_script' , 'load_css_scripts' );
function load_css_scripts(){
	load_scripts( 'css' );
}

add_trigger( 'js_script' , 'load_js_scripts');
function load_js_scripts(){
	load_scripts( 'js' );
}