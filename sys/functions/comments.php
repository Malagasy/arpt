<?php

function allow_post_untitled_comments( $validation ,$datas){

	if( $datas['parentid'] == 0 || empty( $datas['message'] ) ) return $validation;
	
	$validation->reset();
	
	return $validation;
}
add_layer( 'pre_inc_commentaire' , 'allow_post_untitled_comments' );

function get_comments( $args ){
	$default = array( 'type' => 'commentaire' );

	if( is_array( $args ) ) :
		$args = array_merge( $default , $args ); 
		return get_contents( $args );
	else : ($args);
		return get_contents( array( 'type' => 'commentaire' , 'parent_id' => $args ) );
	endif;
}