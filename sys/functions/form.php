<?php

function fieldset_open( $title , $f_class = null , $l_class = null ){
	echo '<fieldset ' . html_get_specificities( $f_class ) . '><legend ' . html_get_specificities( $l_class ) . '>' . $title . '</legend>';
}

function fieldset_close(){
	echo '</fieldset>';
}

function form_label( $label = null ){
	if( is_array( $label ) ){
		$label_name = $label[0];
		if( isset( $label[1] ) )
			$label_class = 'class="' . $label[1] . '"';
		else
			$label_class = '';
		return ' <label for="'. do_slug($label_name) .' ' . $label_class . '">' . $label_name . '</label> ';
	}
	return ( $label == null ) ? ' ' : ' <label for="'. do_slug($label) .'">' . $label . '</label> ';
}

function form_open( $specificities = null){

	$default = array( 'method' => 'post' );

	$specificities = array_merge( $default , (array)$specificities );
		
	echo '<form ' . html_get_specificities( $specificities ) . '>';
}
function form_close( $output = null ){
	echo $output;
	echo '</form>';
}

function form_input( $specificities , $label = null , $extras = null ) {

	if( !is_null( $label ) && !isset( $specificities['id'] ) ) :
		$specificities['id'] = do_slug($label); 
		if( !isset( $specificities['name'] ) ) :
			$specificities['name'] = $specificities['id'];
		endif;
	elseif( is_null( $label ) && !isset( $specificities['id'] ) ) :
		$specificities['id'] = $specificities['name'];
	endif;
	$specificities['id'] = do_slug( $specificities['id'] );
	
	$default = array( 'type' => 'text' );
	
	$specificities = array_merge( $default , $specificities );
	
	echo ' ' . form_label( $label ) . ' ';
	echo ' <input ' . html_get_specificities( $specificities ) . ' ' . $extras . '> ';
	
}

function form_password( $specificities , $label = null , $extras = null ){
	$specificities['type'] = 'password';

	form_input( $specificities , $label , $extras );
}

function form_hidden( $specificities ){
	$specificities['type'] = 'hidden';

	form_input( $specificities );
}


function form_textarea( $specificities , $label = null , $content = null , $extras = null){

	if( !is_null( $label ) && !isset( $specificities['id'] ) ) :
		$specificities['id'] = do_slug($label); 
		if( !isset( $specificities['name'] ) ) :
			$specificities['name'] = $specificities['id'];
		endif;
	elseif( is_null( $label ) && !isset( $specificities['id'] ) ) :
		$specificities['id'] = $specificities['name'];
	endif;
	$specificities['id'] = do_slug( $specificities['id'] );
	if( isset( $specificities['value'] ) ) :
		$content = $specificities['value'];
		unset( $specificities['value'] );
	endif;
	call_triggers('before_form_textarea' , $specificities , $label , $content );
	echo ' ' . form_label( $label ) . ' ';
	echo ' <textarea ' . html_get_specificities( $specificities ) . ' ' . $extras . '>' . htmlentities($content) . '</textarea> ';
	call_triggers('after_form_textarea' , $specificities , $label , $content );
}

function form_select( $specificities , $label , $options , $selected = null ){
	if( !is_null( $label ) ) :
		$specificities['id'] = do_slug($label); 
		if( empty( $specificities['name'] ) )
			$specificities['name'] = $specificities['id'];
	endif;
	echo form_label( $label );
	
	echo ' <select ' . html_get_specificities( $specificities ) . '>';
	foreach( $options as $key => $option )
		if( $key == $selected )
			echo '<option value="' . $key . '" selected>' . $option . '</option>';
		else
			echo '<option value="' . $key . '">' . $option . '</option>';

	echo '</select> ';
}

function form_radio( $specificities , $label = null , $extras = null ){
	$specificities['type'] = 'radio';
	echo '<label>';
	form_input( $specificities , null , $extras );
	echo $label;
	echo '</label>';
}

function form_checkbox( $specificities , $label = null , $extras = null ){
	$specificities['type'] = 'checkbox';
	echo '<label>';
	form_input( $specificities , null , $extras );
	echo $label;
	echo '</label>';
}


function form_submit( $specificities = null ){
	if( !is_array( $specificities ) && !empty( $specificities ) ) :
		$value = $specificities;
		unset( $specificities );
		$specificities['value'] = $value;
	endif;

	$default = array( 'value' => 'Send' );

	$specificities = array_merge( $default , (array)$specificities );
	$specificities['type'] = 'submit';

	$value = array_shift( $specificities );

	echo ' <button ' . html_get_specificities( $specificities ) . '>' . $value . '</button> ';
}

