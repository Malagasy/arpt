<?php

class Config{

	private function __construct(){
	}
	
	
	public static function getInstance(){
		global $arpt_config;
		if( is_null($arpt_config) )
			return new Config();
		return $arpt_config;
	}
	public function add_option( $name , $value ){
		$rs = new_query( 'insert' , 'arpt_options' , array( 'name' => $name , 'value' => $value ) );
		return $rs->get();
	}
	
	public function get_option( $name ){
		$option = new_query( 'select' , 'arpt_options' , array( 'where' => 'name=\''. $name . '\'' ) );
		if( $option->next() )
			return $option->qdatas()->value;
		return false;
	}
	
	public function set_option( $name , $value ){
		$query['set'] = clause_where( 'value' , '=' , $value );
		$query['where'] = clause_where( 'name' , '=' , $name );
		$rs =  new_query( 'update' , 'arpt_options' , $query );
		return $rs->get();
	}
	public function delete_option( $nom ){
		$query['where'] = clause_where( 'name' , '=' , $nom );
		$rs = new_query( 'delete' , 'arpt_options' , $query );
		return $rs->get();
	}

	public function add_setting( $name , $value ){
		$rs = new_query( 'insert' , 'arpt_general' , array( 'setting' => $name , 'value' => $value ) );
		return $rs->get();
	}
	
	public function get_setting( $name ){
		$setting = new_query( 'select' , 'arpt_general' , array( 'where' => 'setting=\''. $name . '\'' ) );
		if( $setting->next() )
			return $setting->qdatas()->value;
		return false;
	}
	
	public function set_setting( $name , $value ){
		$query['set'] = clause_where( 'value' , '=' , $value );
		$query['where'] = clause_where( 'setting' , '=' , $name );
		$rs = new_query( 'update' , 'arpt_general' , $query );
		return $rs->get();
	}


}