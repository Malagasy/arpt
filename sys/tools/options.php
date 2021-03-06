<?php

function comments_actived(){
	if( get_contentproperty( get_currentcontentid() , 'comments_actived' ) === false ) return true;
	if( intval( get_contentproperty( get_currentcontentid() , 'comments_actived' ) ) === 0 )
		return false;
	return true;
}

function visitor_can_comment(){
	if( get_option( 'allow_visitor_to_comment' ) == 1 || is_user_online() )
		return true;
	return false;
}

function is_pagetype_hide(){
	if( get_option('hide_content_type_on_url') == 1 )
		return true;
	return false;
}