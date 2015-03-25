<?php

function logr( $string ){
	$file = debug_backtrace();
	echo '<pre>';
	if( isset( $file[1]['file'] ) ) echo $file[1]['file'] . ' - ' . $file[1]['line'];
	echo '<br/>';
	print_r( $string );
	echo '</pre>';
}

function queried_query(){
	logr( get_queried()->query );
}

function exectime(){
	global $exectime;

	$exectime[] = microtime(true);
}

function logr_exectime(){
	global $exectime;
	$i = -1;

	foreach( $exectime as $time ){
		$i++;
		if( $i == 0 )
			$r[$i] = $time;
		else
			$r[$i] = $time - $exectime[$i-1]; 
	}
	logr($r);
}