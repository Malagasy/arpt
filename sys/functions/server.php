<?php

function get_the_scheme(){
	$scheme = is_ssl() ? 'https' : 'http';

	$scheme .= '://';

	return $scheme;
}

/* http://stackoverflow.com/questions/1175096/how-to-find-out-if-you-are-using-https-without-serverhttps */
function is_ssl(){
	return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}

function get_the_host(){
	return $_SERVER['HTTP_HOST'];
}
