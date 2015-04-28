<?php

function routing_home(){
	$slug = ( defined('DEFAULT_INDEXSLUG') ) ? DEFAULT_INDEXSLUG : 'index';
	return $slug;
}

function routing_article(){
	$slug = ( defined('DEFAULT_ARTICLESLUG') ) ? DEFAULT_ARTICLESLUG : 'article';
	if( is_active_content( $slug ) ) return $slug;
	return 'article';
}

function routing_page(){
	$slug = ( defined('DEFAULT_PAGESLUG') ) ? DEFAULT_PAGESLUG : 'page';
	if( is_active_content( $slug ) ) return $slug;
	return 'page';
}

function routing_search(){
	$slug = ( defined('DEFAULT_SEARCHSLUG') ) ? DEFAULT_SEARCHSLUG : 'search';
	return $slug;
}

function routing_keywords(){
	$slug = ( defined('DEFAULT_KEYWORDSSLUG') ) ? DEFAULT_KEYWORDSSLUG : 'kw';
	return $slug;
}

function routing_author(){
	$slug = ( defined('DEFAULT_AUTHORSLUG') ) ? DEFAULT_KEYWORDSSLUG : 'author';
	return $slug;
}