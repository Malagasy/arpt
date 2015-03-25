<?php

function get_pageinfo(){
	global $arpt;
	return $arpt->get_pageinfo();
}

function get_pagetype(){
	return get_pageinfo()->get_pagetype();
}

function get_pageargs( $c = null ){
	return get_pageinfo()->get_pageargs( $c );
}
function get_lastarg(){/*
	$curl = get_current_url();
	$curl = array_filter( explode( '/' , $curl ) );
	return end( $curl );*/
	return get_pageinfo()->get_lastarg();
}

function get_params(){
	return get_pageinfo()->get_params();
}

function count_args(){
	return count( get_pageinfo()->get_pageargs() );
}


function is_arg( $name , $pos = 0 ){
	if( is_array( $name ) ) :
		if( in_array( get_pageargs( $pos ) , $name ) )
			return true;
		return false;
	endif;

	if( get_pageargs( $pos ) == $name ) return true;
	return false;
}

function currentpage(){
	return ( filter_has_var( INPUT_GET , 'p' ) ) ? ( ( filter_var( $_GET['p'] , FILTER_VALIDATE_INT ) > 0 ) ? $_GET['p'] : 1 ) : 1 ;
}

function the_offset(){
	return currentpage() - 1;
}
	
function is_adminpage(){
	if( get_pagetype() == 'admin' )
		return true;
	return false;
}

function is_contentpage(){
	if( is_active_content( get_pagetype() ) ) return true;
	return false;
}

function is_homepage(){
	if( get_pagetype() == '' || get_pagetype() == routing_home() ) return true;
	return false;
}

function is_searchpage(){
	if( get_pagetype() == routing_search() ) return true;
	return false;
}

function is_keywordspage(){
	if( get_pagetype() == routing_keywords() ) return true;
	return false;
}
function is_authorpage(){
	if( ( get_pagetype() == routing_author() ) ) return true;
	return false;
}

function is_archivepage(){
	if( filter_is_year( get_pagetype() ) || is_authorpage() ) return true;
	return false;
}

function is_queriablepage(){
	if( is_contentpage() || is_homepage() || is_searchpage() || is_keywordspage() || is_archivepage() || is_categorypage() ) return true;
	return false;
}

function is_signuppage(){
	if( get_pagetype() == 'signup' )
		return true;
	return false;
}

function is_errorpage(){
	if( get_pagetype() == 'error' )
		return true;
	return false;
}

function is_categorypage(){
	if( get_category_by_name( get_pagetype() ) === false ) return false;
	return true;
}

function get_systempage(){
	global $arpt;
	return $arpt->get_systempage();
}

function is_systempage( $value = null ){
	if( $value == null ) $value = get_pagetype();
	
	if( in_array( $value , get_systempage()  ) )
		return true;
	return false;
}

function is_success(){
	if( get_lastarg() == 'success' ) return true;
	return false;
}

function is_failure(){
	if( get_lastarg() == 'failure' ) return true;
	return false;
}

function sitetitle(){
	return get_pageinfo()->get_pagetitle();
}

function sitename(){
	return get_setting('sitename');
}
function description(){
	return get_setting('description');
}

function sitedescription(){
	return get_pageinfo()->get_pagedescr();
}
