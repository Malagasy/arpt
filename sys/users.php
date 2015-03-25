<?php

class Users extends Queries{

	public function __construct( $specificities = null , $func = 'select' ){
		if( is_null( $specificities ) ) $specificities = get_currentuserid();

		if( $func == 'select' ) :

			$default = array(
				'selection' => '*',
				'id' => null,
				'email' => null,
				'name' => null,
				'slug' => null,
				'pass' => null,
				'offset' => 0,
				'limit' => 20
				);

			if( is_array( $specificities ) )
				$merged = array_merge($default , $specificities);
			else
				$merged = array_merge($default , array( 'id' => $specificities ) );

			$this->specificities = $merged;
			$this->currentpage = ( $merged['offset'] == 0 ) ? 1 : $merged['offset'];
			$this->limit = $merged['limit'];
			$merged['offset'] = ( $merged['offset'] == 1 ) ? 0 : $merged['offset'];

			$r['where'] = '1 = 1 ' . clause_where( 'id' , '=' , $merged['id'] , ' AND ' ) . clause_like( 'email' , $merged['email'] , ' AND ' ) . clause_like( 'username' , $merged['name'] , ' AND ' ) . clause_like( 'slug' , $merged['slug'] , ' AND ' ) . clause_where( 'pass' , '=' , pwd_crypt( $merged['pass'] ) , ' AND ' ) .  clause_limit( $merged['limit'] , ($merged['offset'])*$merged['limit'] );

		elseif( $func == 'update' ) :

			$default = array(
				'id'	=> null,
				'email' => null,
				'name'	=> null,
				'pass'	=> null,
				'date_registered' => null
				);

			$specificities = array_merge( $default , $specificities );

			$query['set'] = clause_where( 'id' , '=' , $specificities['id'] ) . clause_where( 'email' , '=' , $specificities['email'] , ',' ) . clause_where( 'username' , '=' , $specificities['name'] , ',' ) . clause_where( 'pass' , '=' , pwd_crypt( $specificities['pass'] ) , ',' ) . clause_where( 'date_registered' , '=' , $specificities['date_registered'] , ',' );
			$query['where'] = clause_where( 'id' , '=' , $specificities['id'] );

			$r = $query;

		elseif( $func == 'delete' ) :
			if( is_array( $specificities ) )
				$id = array_shift( $specificities );
			else
				$id = $specificities;

			unset( $specificities );

			$r['where'] = clause_where( 'id' , '=' , $id );
		endif;
			
		parent::__construct( $func, 'arpt_users' , $r );
	}
	public function pagination( $extension = 3 ){
		$mirror = $this->new_based_on_current( array( 'limit' => 'nolimit' ) );
		$max =  ceil( $mirror->total / $this->limit );

		$this->paginate( $extension , $this->currentpage , $max );
	}
	public function new_based_on_current( $args ){
		return new Users( array_merge( $this->specificities , $args ) );
	}

	public function can( $access ){
		return usercan( $this->qid() , $access );	
	}	

	public function qdatas(){
		return $this->datas;
	}

	public function qid(){
		return $this->datas->id;
	}

	public function qpwd(){
		return $this->datas->pass;
	}

	public function qemail(){
		return $this->datas->email;
	}

	public function qname(){
		return $this->datas->username;
	}
	public function qslug(){
		return $this->datas->slug;
	}

	public function qrole(){
		return get_userrole( $this->qid() );
	}
	public function qdateregistered(){
		return $this->datas->date_registered;
	}
	public function qstatus(){
		return $this->datas->status;
	}
	public function qproperty( $name ){
		return get_userproperty( $this->qid() , $name );
	}



}

