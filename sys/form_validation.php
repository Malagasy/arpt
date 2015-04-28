<?php

class FormValidation extends AT_Errors{

	public function __construct(){
		parent::__construct();
	}

	public function required( $name , $label = null ){
		if( $this->exists( $name ) ) :
			if( $_POST[$name] == '' ) :
				$subject = !$label ? $name : $label;
				$this->add('required', $subject . ' est vide.');
				return false;
			endif;
			return true;
		endif;
		return false;
	}

	public function image( $name ){
		if( $this->exists( $name ) ) :
			if( !is_image( $_FILES[$name] ) ) :
				$this->add('image', $name . ' n\'est pas une image.' );
				return false;
			endif;
			return true;
		endif;
	}

	public function email( $name , $label = null ){
		if( $this->exists( $name ) ) :
			if( filter_var( $_POST[$name] , FILTER_VALIDATE_EMAIL ) === false ) :
				$subject = !$label ? $name : $label;
				$this->add('email', ucfirst( $subject ) . ' n\'est pas un mail.');
				return false;
			endif;
			return true;
		endif;
		return false;
	}

	public function equals( $first , $second ){
		if( $this->exists( $first ) ) :
			if( diffstr( $_POST[$first] , $second ) ) :
				$this->add('equals', ucfirst( $first ) . ' et ' . $second . ' sont différents.' );
				return false;
			endif;
			return true;
		endif;
		return false;
	}

	public function notequals( $first , $second ){
		if( $this->exists( $first ) ) :

			if( samestr( $_POST[$first] , $second ) ) :
				$this->add('not-equals', ucfirst( $first ) . ' et ' . $second . ' sont équivalents.' );
				return false;
			endif;
			return true;
		endif;
		return false;
	}

	private function exists( $name ){
		if( filter_has_var( INPUT_POST, $name ) || isset( $_FILES[$name] ) ) return true;
		return false;
	}
	
	public function isValid(){
		if( !empty( $_POST ) AND $this->has() == false )
			return true;
		return false;
	}
}