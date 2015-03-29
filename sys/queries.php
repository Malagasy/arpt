<?php

class Queries{
	
	public function __construct( $action , $table , $specificities = null ){
		$this->mysqli =  new mysqli(MYSQLI_LOCALHOST,MYSQLI_ROOT,MYSQLI_PASSWORD,MYSQLI_DATABASE);
		$this->mysqli->set_charset("utf8");
		$this->datas = new stdClass;
		call_user_func( array( get_class()  , $action ) , $table , $specificities );
		
	}
	
	private function select( $table , $specificities ){
		//logr( $specificities); 
		$default = array( 'selection' => '*' , 'where' => null , 'orderby' => null );

		$specificities = array_merge( $default , $specificities );
		//logr( $specificities);

		$selection = $specificities['selection'];
		$rest = clause( ' WHERE ' , $specificities['where'] ) . ' ' . clause_orderby( $specificities['orderby'] );
		
		//$rest = $this->sanitize_string( $rest );
		//logr("SELECT {$selection} FROM {$table} {$rest}");
		$this->query = "/*qc=on*/" . "SELECT {$selection} FROM {$table} {$rest}";

		if( $this->result = $this->mysqli->query( $this->query ) )
			$this->total =  $this->result->num_rows;
		else
			$this->total =  0;
	}
	
	private function insert( $table , $specificities ){
	
		$specificities = $this->clean( $specificities );
		
		$values = '(' . implode( ', ' , array_keys( $specificities ) ) . ') ';
		$values .= 'VALUES(\'' . implode( '\',\'' , $specificities  ) . '\')';

		//$values = $this->sanitize_string( $values );
		$this->query = "INSERT INTO {$table} {$values}";
		$this->mysqli->query( $this->query );;
		$this->result = $this->mysqli->insert_id;
	}
	
	private function update( $table , $specificities ){
		$set = '';
		if( is_array( $specificities['set'] ) ){
			$specificities['set'] = $this->clean( $specificities['set'] );
			foreach( $specificities['set'] as $k => $v ){
				$set .= $k . '=\'' . $v . '\',';
			}
			unset( $specificities['set'] );
			$specificities['set'] = substr( $set, 0 , -1 );
		}


		$set = ( isset( $specificities['set'] ) ) ? ' SET ' . $specificities['set'] : '';
		$where = ( isset( $specificities['where'] ) ) ? ' WHERE ' . $specificities['where'] : '';
		$this->query = "UPDATE {$table} {$set} {$where} ";
		$this->result = $this->mysqli->query( $this->query);
	}
	
	private function delete( $table , $specificities ){
		$where = ( isset( $specificities['where'] ) ) ? ' WHERE ' . $specificities['where'] : '';
		$this->result = $this->mysqli->query( "DELETE FROM {$table} {$where}" );
	}


	public static function paginate( $extension , $cp , $max ){

		if( $max == 1 ) return;

		$after = array();
		$before = array();

		for( $i = $cp-1 ; $i > $cp - $extension ; $i--) :
			if( $i <= 0 ) break;
			$before[] = a( get_clean_url() . '?p=' . $i , ' ' . $i . ' ' );
			if( $i == 1 ) break;
			
			if( $i == $cp - $extension + 1 )
				$before[] = a( get_clean_url() , ' Début ' );
		endfor;
		$before = array_reverse( $before );
		for( $i = $cp+1 ; $i < $cp + $extension ; $i++ ) :
			if( $i > $max ) break;
			$after[] = a( get_clean_url() . '?p=' . $i , ' ' . $i . ' ' );
			if( $i == $max ) break;

			if( $i == $cp + $extension - 1 )
				$after[] = a( get_clean_url() . '?p=' . $max , ' Fin ' );
		endfor;

		$before = call_layers( 'paginate_element_layer' , $before , 'before' );
		$after = call_layers( 'paginate_element_layer' , $after , 'after' );


		echo call_layers('paginate_element_ul_layer' , '<ul class="pagination">' );
		foreach( $before as $elem )
			li( array() , $elem );
		li( array( 'class' => 'active' ) , a ( '#' , $cp ) );
		foreach( $after as $elem )
			li( array() , $elem );
		echo '</ul>';
	}
	
	
	private function clean( $specificities ){ 
		if( !is_array( $specificities ) ) return $this->mysqli->real_escape_string( $specificities );
		foreach( $specificities as $key => $value )
			$array[$key] = $this->mysqli->real_escape_string( $value );
		return $array;
	}
	public function get(){
		return $this->result; 
	}
	
	public function getAll(){
		$this->reset();
		$tab = array();
		$i = -1;

		while( $this->next() ) :
			$i++;
			foreach( $this->datas as $k => $v )
				$tab[$i][$k] = $v;
		endwhile;

		$this->reset();
		return $tab;

	/*
	if( $form == '' )
		return $this->result->fetch_all();
	else
		return $this->result->fetch_all( $form );*/
	}

	public function getFirst(){
		$r = $this->getAll();
		
		return current( $r );
	}
	
	public function reset(){
		$this->result->data_seek(0);
	}

	public function has(){
		if( $this->total )
			return true;
		return false;
	}

	public function free(){
	
		return $this->result->free();
	}

	public function is_null(){
		if( $this->total == 0 )
			return true;
		return false;
	}
	
	public function next(){
		if( $this->is_null() ) return false;
		if( $object = $this->result->fetch_object() ) : 
			foreach( $object as $k => $attr )
				$this->datas->$k = stripslashes( html_entity_decode( $attr ) );
			return true;
		endif;
		return false;
	}

	public function qdatas(){
		return $this->datas;
	}

}