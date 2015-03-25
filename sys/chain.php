<?php

class Chain{


	public function Chain(){
		$this->content = array();
	}

	public function add( $func , $position = null ){ 
		if( !$this->check( $func ) )
			if( !is_null( $position ) )
				if( empty( $this->content[$position] ) ) :
					$this->content[$position] = $func;
				else :
					$tmp = $this->content;
					for( $i = $position ; $i < sizeof( $this->content ) ; $i++ )
						$tmp[$i+1] = $this->content[$i]; 
					$tmp[$position] = $func;
					$this->content = $tmp;
				endif;
			else
				$this->content[] = $func;
		else
			return false;

		ksort( $this->content );

		return true;
	}

	public function has(){
		if( empty( $this->content ) ) return false;
		return true;
	}

	public function rm( $func ){
		if( !$this->check( $func ) ) return false;

		$key_to_target = $this->getKey( $func );
		unset( $this->content[ $key_to_target ] );

		return ksort( $this->content );
	}
	public function rmKey( $position ){
		$elem = $this->getVal( $position );
		return $this->rm( $elem );
	}
	public function get(){
		return $this->content;
	}

	public function mv( $func , $position ){
		$key = $this->getKey( $func1 );
		$val = $this->getVal( $position );

		$this->setVal( $position , $func );
		$this->setVal( $key , $val );
	}

	public function mvVal( $pos1 , $pos2 ){
		$val = $this->getVal( $pos1 );
		$this->mv( $val , $pos2 );
	}

	public function setVal( $key , $val ){
		$this->content[$key] = $val;
	}

	public function getVal( $key ){
		return $this->content[$key];
	}

	public function getKey( $value ){
		return array_search( $value , $this->content);
	}

	public function check( $value ){
		if( in_array( $value , $this->content ) )
			return true;
		return false;
	}

	public function set($value){
		if( !is_array( $value ) ) return;
		$this->content = $value;
	}

}

class Menu extends Chain{ 
	public function Menu(){
		parent::__construct();
	}
	
	public function display( $before = null , $after = null , $position = null ){
		$i = 0;
		if( $position !== null && !is_array( $position ) )
			$position = explode(',',$position);
		foreach( $this->content as $func ) : $i++;
			if( $position !== null && in_array($i,$position) )
				echo $before;
			elseif( $position === null )
				echo $before;
			if( !is_at_errors( $func ) )
				if( function_exists( $func ) )
					call_user_func( $func );
			if( $position !== null && in_array($i,$position) )
				echo $after;
			elseif( $position === null )
				echo $after;
		endforeach;
	}
	
	
}