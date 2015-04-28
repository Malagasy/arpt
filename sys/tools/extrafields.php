<?php

function add_field_content( $active_content , $extra ){
	
	global $arpt_contents;
	
	if( is_array( $active_content ) )
		foreach( $active_content as $c )
			$arpt_contents['extra_fields'][$c][] = $extra ;
	else
		$arpt_contents['extra_fields'][$active_content][] = $extra;
}

function add_field_user( $extra ){
	
	global $arpt_users;
	
	$arpt_users[] = $extra ;
}

function get_extrafields_content( $content ){
	global $arpt_contents;
	
	if( !isset( $arpt_contents['extra_fields'][$content] ) ) return false;
	return $arpt_contents['extra_fields'][$content];
	
}

function get_extrafields_user(){
	global $arpt_users;

	return $arpt_users;
}

function decode_extrafields_type( $type , $args , $id = null ){
	$default = array( 
				'type' => 'text',
				'name' => null,
				'value' => null,
				'label' => 'Sans nom',
				'options' => null,
				'extras' => null,
				'id' => null
	);
	$args = array_merge( $default , $args );
	$name = 'extrafields_'.$args['name'];
	
	if( !is_null( $id ) ) :
		if( $type == 'content' )
			$value = get_contentproperty( $id , $args['name'] ); // attentio ndesynchro
		elseif( $type == 'user' )
			$value = get_userproperty( $id , $args['name'] );
	endif;

	$property = ( isset( $value) ) ? $value : $args['value'];
	

	if( in_array( $args['type'] , array( 'text', 'password' , 'hidden' , 'file' ) ) ) :
		if( $args['type'] != 'file' ) div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'type' => $args['type'] , 'name' => $name , 'value' => $property , 'id' => $args['id']) , $args['label'] );
		if( $args['type'] != 'file' ) : div_close(); endif;
	elseif( $args['type'] == 'textarea' ) :
		div( array( 'class' => 'form-group' ) );
		form_textarea( array( 'class' => 'form-control' , 'name' => $name , 'id' => $args['id'] ) , $args['label'] , $property );
		div_close();
	elseif( $args['type'] == 'select' ) :
		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => $name , 'id' => $args['id']) , $args['label'] , $args['options'] , $property );
		div_close();
	elseif( $args['type'] == 'radio' ) :
		div( array( 'class' => 'radio-inline') );
		form_radio( array( 'name' => $name , 'value' => $args['value'] , 'id' => $args['id']) , $args['label'] , ( isset( $value ) && $property == $args['value'] ) ? 'checked' : $args['extras'] );
		div_close();
	endif;
}

function decode_extrafields_user( $args , $id = null ){
	return  decode_extrafields_type( 'user' , $args , $id );
}

function decode_extrafields_content( $args , $id = null ){
	return decode_extrafields_type( 'content' , $args , $id );
}

add_trigger( 'end_add-content' , 'display_extra_fields' );
function display_extra_fields( $cid , $content_type){
	$r = get_extrafields_content( $content_type );
	if( !$r ) return;
	
	foreach( $r as $field )
		decode_extrafields_content( $field , $cid );

}
/*
add_trigger( 'end_add-content_article' , 'display_extra_fields_article' );
function display_extra_fields_article( $cid ){

	global $arpt_contents;
	$r = get_extrafields_content('article' );

	if( !$r ) return;

	foreach( $r as $field )
		decode_extrafields_content( $field , $cid );

}*/