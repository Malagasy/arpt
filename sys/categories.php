<?php

class Categories extends Queries{


	function __construct( $specificities , $action = 'select' ){
		if( $action == 'select' ) :
			$default = array(
				'selection' => '*',
				'id' => null,
				'name' => null,
				'content_type' => null );

			$specificities = array_merge( $default , (array)$specificities );

			$where = ' 1 = 1 ' . clause_where( 'id' , '=' , $specificities['id'] , ' AND ' );
			$where .= clause_where( 'name' , '=' , $specificities['name'] , ' AND ' );
			$where .= clause_where( 'content_type' , '=' , $specificities['content_type'] , ' AND ' );

			$specs['where'] = $where;
			$specs['selection'] = $specificities['selection'];
		elseif( $action == 'update' ) :
			$default = array(
				'selection' => '*',
				'id' => null,
				'name' => null,
				'description' => null,
				'content_type' => null );

			$specificities = array_merge( $default , (array)$specificities );

			$specificities = $this->clean( $specificities );
			
			logr($specificities);
			die();


			$query['set'] = clause_where( 'id' , '=' , $specificities['id'] ) . clause_where( 'name' , '=' , $specificities['name'] , ',' ) . clause_where( 'description' , '=' , $specificities['description'] , ',' );
			$query['where'] = clause_where( 'id' , '=' , $specificities['id'] );
			$specs = $query;		
		elseif( $action == 'delete' ) :
			if( is_array( $specificities ) )
				$id = array_shift( $specificities );
			else
				$id = $specificities;

			$specs['where'] = clause_where( 'id' , '=' , $id );
		endif;


			$this->specificities = $specificities;
		parent::__construct( $action , 'arpt_categories' , $specs );
	}

	public function get_contentsids(){
		return get_contentsids_by_categoryid( $this->qid() );
	}


	public function qid(){
		return $this->datas->id;
	}

	public function qname(){
		return $this->datas->name;
	}

	public function qdescr(){
		return $this->datas->description;
	}

	public function qtype(){
		return $this->datas->content_type;
	}
}