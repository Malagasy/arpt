<?php

function config_instance(){
	global $arpt;
	return $arpt->get_config();
}

function get_option( $name ){
	$option = config_instance()->get_option( $name );
	//logr($option);
	return $option;
}

function set_option( $name , $value ){
	$v = get_option( $name );
	if( $v === false )
		return add_option( $name , $value );
	return config_instance()->set_option( $name , $value );
}

function add_option( $name , $value ){
	return config_instance()->add_option( $name , $value );
}

function delete_option( $name ){
	return config_instance()->delete_option( $name );
}

function option( $name ){
	if( get_option( $name ) === false )
		add_option( $name , null );
	return 'opt_' . $name;
}

function get_setting( $name ){
	$setting = config_instance()->get_setting( $name );
	return $setting;
}
function add_setting( $name , $value ){
	return config_instance()->add_setting( $name , $value );
}

function set_setting( $name , $value ){
	$v = get_setting( $name );
	if( $v === false )
		return add_setting( $name , $value );
	return config_instance()->set_setting( $name , $value );
}

function db_slug_exists( $table , $slug_label , $slug ){
	$slug_count = new_query( 'select' , $table , array( 'where' => $slug_label . '=\''. sanitize_str( $slug ) .'\'' ) );
	if( $slug_count->next() )
		return true;
	return false;
}

function new_transient( $name , $content , $delay = 86400 ){
	set_option( '_transient_' . $name , $content );
	set_option( '_transient_delay_' . $name , time() + $delay );
}

function get_transient( $name ){
	$transient_content = get_option( '_transient_' . $name );
	$transient_delay = get_option( '_transient_delay_' . $name );

	if( false === $transient_content )
		return false;
	
	if( time() > $transient_delay )
		return false;
	return $transient_content;
}


function db_uniq_slug( $table , $slug_label , $string ){
	$string = html_entity_decode( $string );
	if( empty( $string ) ) return;

	$count = 1;
	$string = do_slug( $string );
	
	while( db_slug_exists( $table , $slug_label , $string ) ) :
		
		$count++;
		
		if( $count == 2 ){
			$string .= '+' . $count;
		}else{
			$string = substr( $string , 0 , -1 );
			$string .= $count;
		}
		
	endwhile;
		
	return $string;

}
