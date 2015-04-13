<?php


function insert_new_property( $where , $pid , $label , $value ){
	
	$label = sanitize_str( $label );
	$value = sanitize_str( $value );

	$where = 'arpt_'.$where.'_properties';

	return new_query( 'insert' , $where , array( 'parent_id' => $pid , 'label' => $label , 'value' => $value ) );
}

function get_property( $where , $pid , $label ){	

	$label = sanitize_str( $label );

	$where = 'arpt_'.$where.'_properties';
	$q = new_query( 'select' , $where , array( 'where' => ' parent_id=\''. $pid .'\' AND label=\''.$label.'\'' , 'selection' => 'value') );
//	logr($q); exit();
	if( $q->total == 0 ) return false;

	if( $q->total == 1 ) :
		if( $q->next() ) :
			return $q->datas->value;
		endif;
	else :
		while($q->next()) $return[] = $q->datas->value;
		return $return;
	endif;

	return false;
}
/*
	** Update the property of a subject

	** Params : - ( String ), the subject of the property, should be 'users' or 'contents'.
				- ( Int ), the ID of the subject.
				- ( String ), the title (or label) of the property.
				- ( String ), the new value of the property

	** Return Queries Object.

*/

function update_property( $where , $pid , $label , $value ){

	$label = sanitize_str( $label );
	$value = sanitize_str( $value );

	$to = 'arpt_'.$where.'_properties';
	
	if( get_property( $where , $pid , $label ) === false ) 
		return insert_new_property( $where , $pid , $label , $value );
	else
		return new_query( 'update' , $to , array( 'set' => 'value=\''.$value.'\'' , 'where' => 'parent_id=\''. $pid . '\' AND label=\''. $label .'\'' ) );
	
}

/*
	** Delete the property of a subject

	** Params : - ( String ), the subject of the property, should be 'users' or 'contents'.
				- ( Int ), the ID of the subject.
				- ( String ), the title (or label) of the property.

	** Return Queries Object.

*/

function delete_property( $where , $pid , $label ){

	$label = sanitize_str( $label );

	$where = 'arpt_'.$where.'_properties';

	return new_query( 'delete' , $where , array( 'where' => "parent_id='". $pid . "' AND label='". $label ."' " ) );

}