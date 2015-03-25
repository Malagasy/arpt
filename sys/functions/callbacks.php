<?php

function add_layer( $target , $callback , $priority = 10 ){
	global $layers;
	if( function_exists( $callback ) )
		$layers[$target][$priority] = $callback;
}

function call_layers( $target , $a = null , $b = null, $c = null , $d = null ){
	global $layers;
	if( !empty( $layers[$target] ) ) 
		return call_user_func( reset( $layers[$target] ) , $a , $b , $c , $d );		
	return $a;
}

function add_trigger( $target , $callback ){
	global $triggers;
		$triggers[$target][] = $callback;
}

function call_triggers( $target , $a = null , $b = null, $c = null , $d = null ){
	global $triggers;
	if( !empty( $triggers[$target] ) )
		foreach( $triggers[$target] as $trigger )
			if( function_exists( $trigger ) )
				call_user_func( $trigger , $a , $b , $c , $d );
}