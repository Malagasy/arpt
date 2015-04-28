<?php

class AT_Errors{
	
	public function AT_Errors( $array = array() ){
		$this->errors = $array;
	}
	
	public function add( $code = 'unnamed' , $message = '' ){
		$this->errors[$code] = $message;
	}
	
	public function get( $code = null ){
		if( $code == null )
			return $this->errors;
		return $this->errors[$code];
			
	}
	
	public function has(){
		if( empty( $this->errors ) )
			return false;
		return true;
	}
	
	public function get_first(){
		if( $this->has() )
			return reset( $this->errors );
		return false;
	}
	
	public function reset(){
		unset($this->errors);
		$this->errors = null;
	}

}